<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  echo "<p style='color:red;'>Access denied.</p>";
  exit;
}

include 'db.php';

// Handle AJAX add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['youtube_link'])) {
    $link = trim($_POST['youtube_link']);
    $title = trim($_POST['title'] ?? '');

    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([^\&\?\/]+)/', $link, $matches);
    $youtube_id = $matches[1] ?? '';

    if ($youtube_id !== '') {
        if ($title === '') $title = "Untitled Video";
        $stmt = $conn->prepare("INSERT INTO farm_videos (youtube_id, title) VALUES (?, ?)");
        $stmt->bind_param("ss", $youtube_id, $title);
        $stmt->execute();
        $stmt->close();
        echo json_encode(["status"=>"success","message"=>"Video added"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Invalid YouTube link"]);
    }
    exit;
}

// Handle AJAX delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM farm_videos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Video deleted"]);
    exit;
}

// Handle AJAX edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $title = trim($_POST['new_title']);
    $stmt = $conn->prepare("UPDATE farm_videos SET title=? WHERE id=?");
    $stmt->bind_param("si", $title, $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Title updated"]);
    exit;
}

// Handle AJAX fetch list
if (isset($_GET['list']) && $_GET['list'] === '1') {
    $rows = [];
    $result = $conn->query("SELECT id,youtube_id,title FROM farm_videos ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
    exit;
}
?>
<div class="guides-container">
  <h2>🌾 Farm Guides</h2>
  <div class="add-video-form">
    <form id="add-video-form">
      <label>YouTube Link:</label>
      <input type="text" name="youtube_link" required placeholder="Paste YouTube link here">
      <label>Title (optional):</label>
      <input type="text" name="title" placeholder="Enter video title">
      <button type="submit" class="btn btn-primary">Add Video</button>
    </form>
  </div>

  <!-- Render existing videos immediately -->
  <div id="video-list" class="video-cards">
    <?php
    $result = $conn->query("SELECT id,youtube_id,title FROM farm_videos ORDER BY created_at DESC");
    if ($result->num_rows > 0) {
        while ($video = $result->fetch_assoc()) {
            $thumb = "https://img.youtube.com/vi/{$video['youtube_id']}/hqdefault.jpg";
            $url = "https://www.youtube.com/watch?v={$video['youtube_id']}";
            echo "
            <div class='video-card'>
              <a href='{$url}' target='_blank'>
                <img src='{$thumb}' alt='{$video['title']}'>
                <h3>{$video['title']}</h3>
              </a>
              <div class='video-actions'>
                <button onclick='deleteVideo({$video['id']})' class='btn btn-danger'>Delete</button>
                <button onclick='editVideo({$video['id']}, \"".htmlspecialchars($video['title'], ENT_QUOTES)."\")' class='btn btn-warning'>Edit Title</button>
              </div>
            </div>
            ";
        }
    } else {
        echo "<p style='text-align:center; color:#777;'>🚫 No farm guides available yet.</p>";
    }
    ?>
  </div>
</div>

<!-- Modals for edit/delete -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <h3>Edit Video Title</h3>
    <input type="text" id="editTitleInput" style="width:100%; padding:8px;">
    <div class="modal-actions">
      <button class="btn btn-warning" onclick="confirmEdit()">Save</button>
      <button class="btn btn-danger" onclick="closeModal('editModal')">Cancel</button>
    </div>
  </div>
</div>

<div id="deleteModal" class="modal">
  <div class="modal-content">
    <h3>Confirm Delete</h3>
    <p>Are you sure you want to delete this video?</p>
    <div class="modal-actions">
      <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
      <button class="btn btn-warning" onclick="closeModal('deleteModal')">Cancel</button>
    </div>
  </div>
</div>