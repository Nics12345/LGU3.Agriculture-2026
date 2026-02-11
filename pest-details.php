<?php
session_start();
header("Content-Type: application/json");
require __DIR__ . '/vendor/autoload.php';
require_once "db.php"; // assumes $conn is your mysqli connection

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$apiKey = $_ENV['OPENAI_API_KEY'];

$pestName = trim($_POST['pest'] ?? '');
$confidence = isset($_POST['confidence']) ? floatval($_POST['confidence']) : null;

if (!$pestName) {
    echo json_encode(["error" => "No pest name provided"]);
    exit;
}

// 1. Check if pest already exists in DB
$stmt = $conn->prepare("SELECT details FROM pests WHERE name = ?");
$stmt->bind_param("s", $pestName);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    // ✅ Return cached details
    echo json_encode(["details" => $row['details']]);
    exit;
}
$stmt->close();

// 2. If not cached, call OpenAI
$payload = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => "Ikaw ay isang agricultural assistant. Sagutin sa wikang Tagalog, at gumamit lamang ng isang malinaw na talata. Ibigay ang maikling paglalarawan at mga paraan ng pagkontrol sa peste."],
        ["role" => "user", "content" => "Pakibigyan ako ng maikling deskripsyon at paano kontroling ang peste na ito: $pestName."] 
    ],
    "max_tokens" => 200
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data["error"])) {
    echo json_encode(["error" => $data["error"]["message"]]);
    exit;
}

$details = $data["choices"][0]["message"]["content"] ?? "No response.";

// 3. Save new pest details into DB with confidence
$stmt = $conn->prepare("INSERT INTO pests (name, details, confidence) VALUES (?, ?, ?)");
$stmt->bind_param("ssd", $pestName, $details, $confidence);
$stmt->execute();
$stmt->close();

// 4. Return details
echo json_encode(["details" => $details]);