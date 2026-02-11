<?php
session_start();
include 'db.php';
?>

<h2>📘 Farming Guides</h2>

<!-- Tabs -->
<div class="tabs">
  <button class="tab-button active" onclick="showTab('videos')">🎥 Videos</button>
  <button class="tab-button" onclick="showTab('images')">🖼 Images</button>
</div>

<!-- Video Section -->
<div id="videos" class="tab-content active">
  <div class="video-cards">
    <?php
    $result = $conn->query("SELECT id, youtube_id, file_path, title, description, created_at FROM farm_videos ORDER BY created_at DESC");
    if ($result->num_rows > 0) {
        while ($video = $result->fetch_assoc()) {
            $title = htmlspecialchars($video['title'], ENT_QUOTES);
            $desc = htmlspecialchars($video['description'], ENT_QUOTES);
            $date = date("M d, Y", strtotime($video['created_at']));

            if (!empty($video['youtube_id'])) {
    $thumbnail = "https://img.youtube.com/vi/{$video['youtube_id']}/hqdefault.jpg";
    echo "
    <div class='video-card' onclick=\"openModal('youtube', '{$video['youtube_id']}', '{$title}', '{$desc}')\">
        <div class='video-thumb'>
            <img src='{$thumbnail}' alt='{$title}'>
            <span class='play-icon'>▶</span>
        </div>
        <h3>{$title}</h3>
        <p class='upload-date'>Uploaded on {$date}</p>
        <p class='video-desc'>{$desc}</p>
    </div>";
} elseif (!empty($video['file_path'])) {
    echo "
    <div class='video-card' onclick=\"openModal('file', '{$video['file_path']}', '{$title}', '{$desc}')\">
        <div class='video-thumb'>
            <video preload='metadata' muted>
                <source src='{$video['file_path']}' type='video/mp4'>
            </video>
            <span class='play-icon'>▶</span>
        </div>
        <h3>{$title}</h3>
        <p class='upload-date'>Uploaded on {$date}</p>
        <p class='video-desc'>{$desc}</p>
    </div>";
            }
        }
    } else {
        echo "<div class='no-videos'><p>🚫 No farming guides available yet.</p></div>";
    }
    ?>
  </div>
</div>

<!-- Image Section -->
<div id="images" class="tab-content">
  <div class="image-cards">
    <?php
    $result = $conn->query("SELECT id, file_path, title, description, created_at FROM farm_images ORDER BY created_at DESC");
    if ($result && $result->num_rows > 0) {
        while ($img = $result->fetch_assoc()) {
            $title = htmlspecialchars($img['title'], ENT_QUOTES);
            $desc = htmlspecialchars($img['description'], ENT_QUOTES);
            $date = date("M d, Y", strtotime($img['created_at']));
            echo "
<div class='image-card' onclick=\"openImageModal('{$img['file_path']}', '{$title}', '{$desc}')\">
    <div class='image-flex'>
      <img src='{$img['file_path']}' alt='{$title}' class='image-thumb'>
      <div class='image-info'>
        <h3>{$title}</h3>
        <p class='upload-date'>Uploaded on {$date}</p>
        <p class='image-desc'>{$desc}</p>
      </div>
    </div>
</div>";
        }
    } else {
        echo "<div class='no-videos'><p>🚫 No farming images available yet.</p></div>";
    }
    ?>
  </div>
</div>

<!-- Video Modal -->
<div id="videoModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">✖</span>
    <div id="modalVideoContainer"></div>
    <h3 id="modalTitle"></h3>
    <p id="modalDesc" class="video-desc"></p> <!-- ✅ Added description -->
  </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
  <div class="modal-content image-modal">
    <span class="close-btn" onclick="closeImageModal()">✖</span>
    <div class="image-modal-flex">
      <img id="modalImage" src="" alt="">
      <div class="image-modal-info">
        <h3 id="imageTitle"></h3>
        <p id="imageDesc" class="image-desc"></p>
      </div>
    </div>
  </div>
</div>