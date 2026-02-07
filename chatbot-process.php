<?php
session_start();
header("Content-Type: application/json");
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$apiKey = $_ENV['OPENAI_API_KEY'];

require_once "db.php"; // this defines $conn

$convId = $_POST['conv_id'] ?? null;
$prompt = trim($_POST['prompt'] ?? '');
$imagePath = null;

// If no conversation ID, create a new one with placeholder title
if (!$convId) {
    $title = "Untitled Chat";
    $stmt = $conn->prepare("INSERT INTO conversations (user_id, title) VALUES (?, ?)");
    $stmt->bind_param("is", $_SESSION['user_id'], $title);
    $stmt->execute();
    $convId = $stmt->insert_id;
}

// Handle image upload
if (!empty($_FILES['image']['name'])) {
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $imagePath = $uploadDir . time() . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
}

// Save user message
$stmt = $conn->prepare("INSERT INTO messages (conversation_id, role, content, image_path) VALUES (?, 'user', ?, ?)");
$stmt->bind_param("iss", $convId, $prompt, $imagePath);
$stmt->execute();

// --- Auto‑rename conversation to first prompt ---
$stmtCheck = $conn->prepare("SELECT title FROM conversations WHERE id = ?");
$stmtCheck->bind_param("i", $convId);
$stmtCheck->execute();
$result = $stmtCheck->get_result();
$row = $result->fetch_assoc();

if ($row && $row['title'] === "Untitled Chat") {
    if (!empty($prompt)) {
        $newTitle = mb_substr($prompt, 0, 50);
    } elseif ($imagePath) {
        $newTitle = "Image Upload";
    } else {
        $newTitle = "New Conversation";
    }

    $stmtUpdate = $conn->prepare("UPDATE conversations SET title = ? WHERE id = ?");
    $stmtUpdate->bind_param("si", $newTitle, $convId);
    $stmtUpdate->execute();
    $stmtUpdate->close();
}
$stmtCheck->close();

// Build OpenAI request
$payload = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => "You are an AI assistant."],
        ["role" => "user", "content" => $prompt]
    ]
];
if ($imagePath) {
    $payload["messages"][] = [
        "role" => "user",
        "content" => [
            ["type" => "text", "text" => $prompt],
            ["type" => "image_url", "image_url" => ["url" => "file://" . $imagePath]]
        ]
    ];
}

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
    $reply = "OpenAI error: " . $data["error"]["message"];
} else {
    $reply = $data["choices"][0]["message"]["content"] ?? "No response.";
}

// Save bot reply
$stmt = $conn->prepare("INSERT INTO messages (conversation_id, role, content) VALUES (?, 'bot', ?)");
$stmt->bind_param("is", $convId, $reply);
$stmt->execute();

// Get updated title
$stmtTitle = $conn->prepare("SELECT title FROM conversations WHERE id = ?");
$stmtTitle->bind_param("i", $convId);
$stmtTitle->execute();
$titleRow = $stmtTitle->get_result()->fetch_assoc();
$stmtTitle->close();

// ✅ Only echo once
echo json_encode([
    "reply" => $reply,
    "conv_id" => $convId,
    "title" => $titleRow['title'] ?? "Untitled Chat"
]);
?>