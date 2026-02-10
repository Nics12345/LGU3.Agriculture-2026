<?php
session_start();
header("Content-Type: application/json");
include 'db.php';

// Fetch latest notifications (both read and unread)
$result = $conn->query("SELECT id, message, link, is_read 
                        FROM notifications 
                        ORDER BY created_at DESC 
                        LIMIT 20");

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        "id"       => $row['id'],
        "message"  => $row['message'],
        "link"     => $row['link'],
        "is_read"  => (int)$row['is_read']
    ];
}

echo json_encode($notifications);
$conn->close();
?>