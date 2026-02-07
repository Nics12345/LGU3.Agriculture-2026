<?php
// chatbot-logs.php
session_start();
include 'db.php'; // connect to your MySQL database

// Only allow admins (optional)
if (!isset($_SESSION['admin_id'])) {
    echo "<p style='color:red;'>Access denied.</p>";
    exit;
}

echo "<h2>💬 Chatbot Conversation Logs</h2>";

// Fetch logs
$result = $conn->query("SELECT id, user_id, prompt, reply, created_at FROM chatbot_logs ORDER BY created_at DESC");

if ($result && $result->num_rows > 0) {
    echo "<table class='logs-table'>";
    echo "<thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Prompt</th>
                <th>Reply</th>
                <th>Timestamp</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>" . htmlspecialchars($row['user_id'] ?? 'Guest') . "</td>
                <td>" . htmlspecialchars($row['prompt']) . "</td>
                <td>" . htmlspecialchars($row['reply']) . "</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p style='color:#777;'>🚫 No chatbot logs yet.</p>";
}
?>