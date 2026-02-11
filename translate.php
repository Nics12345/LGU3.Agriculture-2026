<?php
header("Content-Type: application/json");
$text = $_POST['text'] ?? '';
$target = $_POST['target'] ?? 'tl';

// For simplicity, you can use Google Translate API or OpenAI with a translation prompt
// Example with OpenAI:
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$apiKey = $_ENV['OPENAI_API_KEY'];

$payload = [
  "model" => "gpt-4o-mini",
  "messages" => [
    ["role" => "system", "content" => "Isalin ang teksto sa Tagalog, paragraph format lamang."],
    ["role" => "user", "content" => $text]
  ]
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
echo json_encode(["translated" => $data["choices"][0]["message"]["content"] ?? $text]);