<?php
// pest.php
session_start();
include 'db.php'; // ensure this connects to your lgu3_platform database

echo "<h2>🐛 Pest Control Guides</h2>";


// Fetch categories first
$cats = $conn->query("SELECT id, name FROM pest_categories ORDER BY name ASC");

if ($cats && $cats->num_rows > 0) {
    echo "<div class='categories-row'>"; // horizontal row

    while ($cat = $cats->fetch_assoc()) {
        echo "<div class='category-card' onclick='toggleCategory(this)'>"; // one box per category, clickable

        // Category header
        echo "<h3 class='category-title'>🎬 " . htmlspecialchars($cat['name']) . "</h3>";

        // Fetch videos for this category
        $videos = $conn->query("SELECT youtube_id, title FROM pest_videos WHERE category_id={$cat['id']} ORDER BY created_at DESC");

        if ($videos && $videos->num_rows > 0) {
            echo "<div class='video-deck'>"; // stack of video previews
            while ($video = $videos->fetch_assoc()) {
                $thumbnail = "https://img.youtube.com/vi/{$video['youtube_id']}/hqdefault.jpg";
                $url       = "https://www.youtube.com/watch?v={$video['youtube_id']}";

                echo "
                <div class='video-card'>
                    <a href='{$url}' target='_blank'>
                        <img src='{$thumbnail}' alt='" . htmlspecialchars($video['title'], ENT_QUOTES) . "'>
                        <h4>" . htmlspecialchars($video['title']) . "</h4>
                    </a>
                </div>
                ";
            }
            echo "</div>"; // close video-deck
        } else {
            echo "<p class='no-videos'>🚫 No videos in this category yet.</p>";
        }

        echo "</div>"; // close category-card
    }

    echo "</div>"; // close categories-row
} else {
    echo "
    <div class='no-videos'>
        <p>🚫 No pest control categories available yet. Please check back later.</p>
    </div>
    ";
}
?>