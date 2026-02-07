<?php
// guides.php
session_start();
include 'db.php'; // ensure this connects to your database

echo "<h2>📘 Farming Guides</h2>";
echo "<div class='video-cards'>";

// Fetch videos from the farm_videos table
$result = $conn->query("SELECT youtube_id, title FROM farm_videos ORDER BY created_at DESC");

if ($result->num_rows > 0) {
    while ($video = $result->fetch_assoc()) {
        $thumbnail = "https://img.youtube.com/vi/{$video['youtube_id']}/hqdefault.jpg";
        $url = "https://www.youtube.com/watch?v={$video['youtube_id']}";

        echo "
        <div class='video-card'>
            <a href='{$url}' target='_blank'>
                <img src='{$thumbnail}' alt='{$video['title']}'>
                <h3>{$video['title']}</h3>
            </a>
        </div>
        ";
    }
} else {
    echo "
    <div class='no-videos'>
        <p style='text-align:center; color:#777; font-size:16px;'>
            🚫 No farming guides available yet. Please check back later.
        </p>
    </div>
    ";
}

echo "</div>";
?>