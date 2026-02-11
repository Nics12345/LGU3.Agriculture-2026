<?php
// pest.php
session_start();
include 'db.php'; // ensure this connects to your lgu3_platform database

echo "<h2>🐛 Pest Control Guides</h2>";

// Fetch pest guides directly
$videos = $conn->query("SELECT id, title, description, video_path, youtube_id FROM pest_videos ORDER BY created_at DESC");

if ($videos && $videos->num_rows > 0) {
    echo "<div class='video-cards'>";
    while ($video = $videos->fetch_assoc()) {
        echo "<div class='video-card'>";

        // Thumbnail + click handler
        if (!empty($video['video_path'])) {
            echo "<img src='assets/video-thumb.png' alt='Thumbnail'
                     onclick='openModal(\"file\", \"uploads/pest_videos/{$video['video_path']}\",
                     \"" . htmlspecialchars($video['title'], ENT_QUOTES) . "\",
                     \"" . htmlspecialchars($video['description'], ENT_QUOTES) . "\")'>";
        } elseif (!empty($video['youtube_id'])) {
            echo "<img src='https://img.youtube.com/vi/{$video['youtube_id']}/hqdefault.jpg' alt='Thumbnail'
                     onclick='openModal(\"youtube\", \"{$video['youtube_id']}\",
                     \"" . htmlspecialchars($video['title'], ENT_QUOTES) . "\",
                     \"" . htmlspecialchars($video['description'], ENT_QUOTES) . "\")'>";
        }

        // Title + description
        echo "<h3>" . htmlspecialchars($video['title']) . "</h3>";
        echo "<p>" . nl2br(htmlspecialchars($video['description'])) . "</p>";

        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<div class='no-videos'>
            <p>🚫 No pest control guides available yet. Please check back later.</p>
          </div>";
}
?>