<?php
session_start();
include 'db.php';

$result = $conn->query("SELECT id, message, link, is_read, created_at 
                        FROM notifications 
                        ORDER BY created_at DESC");

echo "<h2>🔔 Notifications Archive</h2>";
echo "<table class='notif-table'>";
echo "<tr><th>Message</th><th>Status</th><th>Date</th></tr>";

while ($row = $result->fetch_assoc()) {
    $status = $row['is_read'] ? "Read" : "Unread";
    echo "<tr>
            <td><a href='#' onclick='loadSection(\"{$row['link']}\")'>{$row['message']}</a></td>
            <td>{$status}</td>
            <td>{$row['created_at']}</td>
          </tr>";
}
echo "</table>";

$conn->close();
?>