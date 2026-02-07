<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$title = "Untitled Chat";

$stmt = $conn->prepare("INSERT INTO conversations (user_id, title, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param("is", $_SESSION['user_id'], $title);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "id" => $stmt->insert_id,
        "title" => $title
    ]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>