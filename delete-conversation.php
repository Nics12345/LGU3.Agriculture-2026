<?php
session_start();
header("Content-Type: application/json");
require_once "db.php";

$convId = $_POST['conv_id'] ?? null;

if (!$convId) {
    echo json_encode(["status" => "error", "message" => "No conversation ID"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM conversations WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $convId, $_SESSION['user_id']);
if ($stmt->execute()) {
    // Also delete messages tied to this conversation
    $stmtMsg = $conn->prepare("DELETE FROM messages WHERE conversation_id = ?");
    $stmtMsg->bind_param("i", $convId);
    $stmtMsg->execute();
    $stmtMsg->close();

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
$stmt->close();
$conn->close();
?>