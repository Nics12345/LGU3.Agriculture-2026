<?php
require 'vendor/autoload.php';
include 'db.php';

use Smalot\PdfParser\Parser;

// --- 1. Download DA PDF ---
$pdfUrl  = "https://www.da.gov.ph/wp-content/uploads/2026/02/Daily-Price-Index-February-10-2026.pdf";
$pdfFile = __DIR__ . "/Daily-Price-Index-February-10-2026.pdf";
file_put_contents($pdfFile, file_get_contents($pdfUrl));

// --- 2. Parse PDF ---
$parser = new Parser();
$pdf    = $parser->parseFile($pdfFile);
$text   = $pdf->getText();

// --- 3. Split into lines ---
$lines = explode("\n", $text);

// --- Debug: dump raw text ---
file_put_contents(__DIR__ . "/pdf_dump.txt", $text);
echo "📄 PDF text dumped to pdf_dump.txt\n";

// --- Normalization map for cleaner product names ---
$normalize = [
    "Well Milled 1-19% bran streak" => "Well Milled Rice (1–19% bran streak)",
    "Regular Milled 20-40% bran streak" => "Regular Milled Rice (20–40% bran streak)",
    "Other Special Rice White Rice" => "Other Special Rice (White)",
    "Premium 5% broken" => "Premium Rice (5% broken)"
];

// --- Track current category (Imported vs Local) ---
$currentCategory = null;

// --- 4. Process each line ---
foreach ($lines as $line) {
    $line = trim($line);
    echo "Line: $line\n";

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

            echo "Matched Rice: $product | $unit | $price | Category: $currentCategory\n";

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