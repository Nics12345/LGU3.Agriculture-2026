<?php
require 'vendor/autoload.php';
include 'db.php';

use Smalot\PdfParser\Parser;

$logFile = __DIR__ . "/update-market-data.log";
function logMsg($msg) {
    global $logFile;
    file_put_contents($logFile, "[".date('Y-m-d H:i:s')."] $msg\n", FILE_APPEND);
}

// --- Helper function: fetch URL with cURL ---
function fetchUrl($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => "Mozilla/5.0 (compatible; MarketDataBot/1.0)"
    ]);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        logMsg("❌ cURL error fetching $url: $error");
        curl_close($ch);
        return false;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode !== 200) {
        logMsg("❌ HTTP $httpCode returned for $url");
        return false;
    }
    return $data;
}

// --- 1. Fetch DA page and find latest PDF ---
$daPageUrl = "https://www.da.gov.ph/daily-price-index/";
$html = fetchUrl($daPageUrl);
if (!$html) {
    die("❌ Failed to fetch DA page");
}
logMsg("✅ DA page fetched successfully");

// Regex to find the first PDF link (latest one)
if (preg_match('/https:\/\/www\.da\.gov\.ph\/[^\s"]+\.pdf/i', $html, $matches)) {
    $pdfUrl = $matches[0];
    logMsg("✅ Found PDF URL: $pdfUrl");
} else {
    logMsg("❌ No PDF link found on DA page");
    die("❌ No PDF link found on DA page");
}

// --- 2. Download latest PDF ---
$pdfFile = __DIR__ . "/latest-da-price-index.pdf";
$pdfData = fetchUrl($pdfUrl);
if (!$pdfData) {
    die("❌ Failed to download PDF");
}
file_put_contents($pdfFile, $pdfData);
logMsg("✅ PDF downloaded to $pdfFile");

// --- 3. Parse PDF ---
$parser = new Parser();
try {
    $pdf  = $parser->parseFile($pdfFile);
    $text = $pdf->getText();
    logMsg("✅ PDF parsed successfully");
} catch (Exception $e) {
    logMsg("❌ PDF parsing failed: ".$e->getMessage());
    die("❌ PDF parsing failed");
}

// --- 4. Split into lines ---
$lines = preg_split('/\r\n|\r|\n/', $text);
file_put_contents(__DIR__ . "/pdf_dump.txt", $text);
logMsg("📄 Raw PDF text dumped to pdf_dump.txt");

// --- Normalization map ---
$normalize = [
    "Well Milled 1-19% bran streak" => "Well Milled Rice (1–19% bran streak)",
    "Regular Milled 20-40% bran streak" => "Regular Milled Rice (20–40% bran streak)",
    "Other Special Rice White Rice" => "Other Special Rice (White)",
    "Premium 5% broken" => "Premium Rice (5% broken)"
];

$currentCategory = null;
$rowCount = 0;

// --- 5. Process each line ---
foreach ($lines as $line) {
    $line = trim($line);

    // Detect category headers
    if (stripos($line, "IMPORTED COMMERCIAL RICE") !== false) {
        $currentCategory = "Imported Commercial Rice";
        continue;
    }
    if (stripos($line, "LOCAL COMMERCIAL RICE") !== false) {
        $currentCategory = "Local Commercial Rice";
        continue;
    }

    // Only process lines containing 'Rice' and within a category
    if ($currentCategory && stripos($line, 'Rice') !== false) {
        $tokens = preg_split('/\s+/', $line);

        // Find the last numeric token (the price)
        $price = null;
        foreach (array_reverse($tokens) as $token) {
            if (is_numeric($token)) {
                $price = (float)$token;
                break;
            }
        }

        if ($price !== null) {
            array_pop($tokens);
            $product = trim(implode(' ', $tokens));
            $unit    = "kg";
            $date    = date("Y-m-d");

            if (isset($normalize[$product])) {
                $product = $normalize[$product];
            }

            // --- Trend logic ---
            $trend = "Stable";
            $prevStmt = $conn->prepare("SELECT price FROM market_data WHERE product_name=? AND category=? ORDER BY updated_at DESC LIMIT 1");
            $prevStmt->bind_param("ss", $product, $currentCategory);
            $prevStmt->execute();
            $prevResult = $prevStmt->get_result();
            if ($prevResult && $prevResult->num_rows > 0) {
                $prevPrice = $prevResult->fetch_assoc()['price'];
                if ($price > $prevPrice) $trend = "Increasing";
                elseif ($price < $prevPrice) $trend = "Decreasing";
            }
            $prevStmt->close();

            // --- Insert or update DB ---
            $stmt = $conn->prepare("REPLACE INTO market_data 
                (product_name, price, unit, status, updated_at, category) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdssss", $product, $price, $unit, $trend, $date, $currentCategory);
            $stmt->execute();
            $stmt->close();
            $rowCount++;

            // --- Insert per-product notification (only if trend changed) ---
            if ($trend !== "Stable") {
                $msg  = "📈 Market Update: {$product} ({$currentCategory}) is now ₱{$price}/{$unit} ({$trend})";
                $link = "user-market-data.php";
                $nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
                $nstmt->bind_param("ss", $msg, $link);
                $nstmt->execute();
                $nstmt->close();
            }
        }
    }
}

// --- Summary notification once per run ---
$msg  = "📊 DA Market Data updated for " . date("F j, Y");
$link = "user-market-data.php";
$nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
$nstmt->bind_param("ss", $msg, $link);
$nstmt->execute();
$nstmt->close();

logMsg("✅ Parsing complete. Rows processed: $rowCount");
echo "✅ Parsing complete (Rice separated by category)!\n";
?>