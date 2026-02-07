<?php
header("Content-Type: application/json");
require __DIR__ . '/vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad(); // safeLoad won't throw fatal error if .env is missing

$apiKey = $_ENV['OPENAI_API_KEY'] ?? null;

// Check if API key exists
if (!$apiKey) {
    echo json_encode([
        "error" => "API key not found. Please configure your .env file."
    ]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$prompt = $input["prompt"] ?? "";

$requestBody = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => "You are a helpful farming assistant chatbot."],
        ["role" => "user", "content" => $prompt]
    ],
    "max_tokens" => 300
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$reply = $data["choices"][0]["message"]["content"] ?? "No response from OpenAI.";

echo json_encode(["reply" => $reply]);
?>