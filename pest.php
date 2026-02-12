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
          echo "<video width='240' height='135' preload='metadata'
             onclick='openModal(\"file\", \"{$video['video_path']}\",
             \"" . htmlspecialchars($video['title'], ENT_QUOTES) . "\",
             \"" . htmlspecialchars($video['description'], ENT_QUOTES) . "\")'>
             <source src='{$video['video_path']}' type='video/mp4'>
             Your browser does not support the video tag.
          </video>";
}        elseif (!empty($video['youtube_id'])) {
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

<!-- Video Modal -->
<div id="videoModal" class="modal">
  <div class="modal-content video-modal">
    <h3 id="modalTitle"></h3>
    <div id="modalVideoContainer" class="video-wrapper"></div>
    <p id="modalDesc"></p>
    <div class="modal-actions">
      <button class="btn btn-secondary" onclick="closeModal()">Close</button>
    </div>
  </div>
</div>

<link rel="stylesheet" href="admin-guides-pest.css">

<script>
// Open modal with video
function openModal(type, src, title, desc) {
  const modal = document.getElementById("videoModal");
  const container = document.getElementById("modalVideoContainer");
  const modalTitle = document.getElementById("modalTitle");
  const modalDesc = document.getElementById("modalDesc");

  modalTitle.textContent = title;
  modalDesc.textContent = desc;

  if (type === "file") {
    container.innerHTML = `
      <video width="100%" controls autoplay>
        <source src="${src}" type="video/mp4">
        Your browser does not support the video tag.
      </video>`;
  } else if (type === "youtube") {
    container.innerHTML = `
      <iframe width="100%" height="450" src="https://www.youtube.com/embed/${src}" frameborder="0" allowfullscreen></iframe>`;
  }

  modal.classList.add("show");
}

// Close modal
function closeModal() {
  const modal = document.getElementById("videoModal");
  const container = document.getElementById("modalVideoContainer");
  modal.classList.remove("show");
  container.innerHTML = ""; // clear video
}
</script>