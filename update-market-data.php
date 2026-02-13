<?php
require 'vendor/autoload.php';
include 'db.php';

use Smalot\PdfParser\Parser;

// --- 1. Fetch DA page and find latest PDF ---
$daPageUrl = "https://www.da.gov.ph/daily-price-index/"; // DA page listing PDFs
$html = @file_get_contents($daPageUrl);
if (!$html) {
    die("❌ Failed to fetch DA page");
}

// Regex to find the first PDF link (latest one)
if (preg_match('/https:\/\/www\.da\.gov\.ph\/wp-content\/uploads\/[^\s"]+\.pdf/i', $html, $matches)) {
    $pdfUrl = $matches[0];
} else {
    die("❌ No PDF link found on DA page");
}

// --- 2. Download latest PDF ---
$pdfFile = __DIR__ . "/latest-da-price-index.pdf";
file_put_contents($pdfFile, file_get_contents($pdfUrl));

// --- 3. Parse PDF ---
$parser = new Parser();
$pdf    = $parser->parseFile($pdfFile);
$text   = $pdf->getText();

// --- 4. Split into lines ---
$lines = explode("\n", $text);

// --- Debug: dump raw text ---
file_put_contents(__DIR__ . "/pdf_dump.txt", $text);
echo "📄 Latest DA PDF text dumped to pdf_dump.txt\n";

// --- Normalization map for cleaner product names ---
$normalize = [
    "Well Milled 1-19% bran streak" => "Well Milled Rice (1–19% bran streak)",
    "Regular Milled 20-40% bran streak" => "Regular Milled Rice (20–40% bran streak)",
    "Other Special Rice White Rice" => "Other Special Rice (White)",
    "Premium 5% broken" => "Premium Rice (5% broken)"
];

// --- Track current category ---
$currentCategory = null;

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
            $prev = $conn->query("SELECT price FROM market_data WHERE product_name='$product' AND category='$currentCategory' ORDER BY updated_at DESC LIMIT 1");
            if ($prev && $prev->num_rows > 0) {
                $prevPrice = $prev->fetch_assoc()['price'];
                if ($price > $prevPrice) $trend = "Increasing";
                elseif ($price < $prevPrice) $trend = "Decreasing";
            }

            // --- Insert or update DB ---
            $stmt = $conn->prepare("REPLACE INTO market_data 
                (product_name, price, unit, status, updated_at, category) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdssss", $product, $price, $unit, $trend, $date, $currentCategory);
            $stmt->execute();
            $stmt->close();

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

echo "✅ Parsing complete (Rice separated by category)!\n";
?>