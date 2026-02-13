<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Content-Type: application/json");
  echo json_encode(["status"=>"error","message"=>"Access denied. Not logged in."]);
  exit;
}

include 'db.php';

// Debug function
function logError($msg) {
    $logFile = __DIR__ . '/error_guide_log.txt';
    file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] " . $msg . "\n", FILE_APPEND);
}

/* -------------------- VIDEO UPLOAD -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
    header("Content-Type: application/json");
    
    try {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($title === '') $title = "Untitled Video";

        $uploadDir = __DIR__ . "/uploads/farm_videos/";
        
        // Check if directory exists and create if needed
        if (!is_dir($uploadDir)) {
            if (!@mkdir($uploadDir, 0777, true)) {
                throw new Exception("Failed to create upload directory. Check permissions.");
            }
        }
        
        // Verify directory is writable
        if (!is_writable($uploadDir)) {
            throw new Exception("Upload directory is not writable. Check folder permissions.");
        }

        $fileName = time() . "_" . basename($_FILES['video_file']['name']);
        $targetPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['video_file']['tmp_name'], $targetPath)) {
            throw new Exception("Failed to move uploaded file. Check server permissions and disk space.");
        }
        
        $dbPath = "uploads/farm_videos/" . $fileName;
        $stmt = $conn->prepare("INSERT INTO farm_videos (file_path, title, description) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("sss", $dbPath, $title, $description);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert video record: " . $stmt->error);
        }
        $stmt->close();

        $msg  = "📘 New farm video uploaded: $title";
        $link = "guides.php";
        $nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
        if ($nstmt) {
            $nstmt->bind_param("ss", $msg, $link);
            $nstmt->execute();
            $nstmt->close();
        }

        echo json_encode(["status"=>"success","message"=>"✓ Video uploaded successfully"]);
    } catch (Exception $e) {
        logError("VIDEO UPLOAD ERROR: " . $e->getMessage());
        echo json_encode(["status"=>"error","message"=>"Video upload failed: " . $e->getMessage()]);
    }
    exit;
}

/* -------------------- IMAGE UPLOAD -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    header("Content-Type: application/json");
    
    try {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($title === '') $title = "Untitled Image";

        $uploadDir = __DIR__ . "/uploads/farm_images/";
        
        // Check if directory exists and create if needed
        if (!is_dir($uploadDir)) {
            if (!@mkdir($uploadDir, 0777, true)) {
                throw new Exception("Failed to create upload directory. Check permissions.");
            }
        }
        
        // Verify directory is writable
        if (!is_writable($uploadDir)) {
            throw new Exception("Upload directory is not writable. Check folder permissions.");
        }

        $fileName = time() . "_" . basename($_FILES['image_file']['name']);
        $targetPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
            throw new Exception("Failed to move uploaded file. Check server permissions and disk space.");
        }
        
        $dbPath = "uploads/farm_images/" . $fileName;
        $stmt = $conn->prepare("INSERT INTO farm_images (file_path, title, description) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("sss", $dbPath, $title, $description);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert image record: " . $stmt->error);
        }
        $stmt->close();

        $msg  = "📘 New farm image uploaded: $title";
        $link = "guides.php";
        $nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
        if ($nstmt) {
            $nstmt->bind_param("ss", $msg, $link);
            $nstmt->execute();
            $nstmt->close();
        }

        echo json_encode(["status"=>"success","message"=>"✓ Image uploaded successfully"]);
    } catch (Exception $e) {
        logError("IMAGE UPLOAD ERROR: " . $e->getMessage());
        echo json_encode(["status"=>"error","message"=>"Image upload failed: " . $e->getMessage()]);
    }
    exit;
}

/* -------------------- YOUTUBE LINK -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['youtube_link'])) {
    header("Content-Type: application/json");
    
    try {
        $link = trim($_POST['youtube_link']);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([^\&\?\/]+)/', $link, $matches);
        $youtube_id = $matches[1] ?? '';

        if ($youtube_id === '') {
            throw new Exception("Invalid YouTube link format. Use full YouTube URL.");
        }
        
        if ($title === '') $title = "Untitled Video";
        
        $stmt = $conn->prepare("INSERT INTO farm_videos (youtube_id, title, description) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("sss", $youtube_id, $title, $description);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert YouTube video: " . $stmt->error);
        }
        $stmt->close();

        $msg  = "📘 New farm guide added: $title";
        $link = "guides.php";
        $nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
        if ($nstmt) {
            $nstmt->bind_param("ss", $msg, $link);
            $nstmt->execute();
            $nstmt->close();
        }

        echo json_encode(["status"=>"success","message"=>"✓ YouTube video added successfully"]);
    } catch (Exception $e) {
        logError("YOUTUBE LINK ERROR: " . $e->getMessage());
        echo json_encode(["status"=>"error","message"=>"YouTube guide failed: " . $e->getMessage()]);
    }
    exit;
}

/* -------------------- EDIT / DELETE -------------------- */
// Delete video
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM farm_videos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute(); $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Video deleted"]);
    exit;
}

// Edit video
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $title = trim($_POST['new_title']);
    $description = trim($_POST['new_description']);
    $stmt = $conn->prepare("UPDATE farm_videos SET title=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $description, $id);
    $stmt->execute(); $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Video updated"]);
    exit;
}

// Delete image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image_id'])) {
    $id = intval($_POST['delete_image_id']);
    $stmt = $conn->prepare("DELETE FROM farm_images WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute(); $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Image deleted"]);
    exit;
}

// Edit image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_image_id'])) {
    $id = intval($_POST['edit_image_id']);
    $title = trim($_POST['new_title']);
    $description = trim($_POST['new_description']);
    $stmt = $conn->prepare("UPDATE farm_images SET title=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $description, $id);
    $stmt->execute(); $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Image updated"]);
    exit;
}
/* -------------------- FETCH VIDEO LIST -------------------- */
if (isset($_GET['list']) && $_GET['list'] === '1') {
    $rows = [];
    $result = $conn->query("SELECT id, youtube_id, file_path, title, description, created_at 
                            FROM farm_videos ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
    exit;
}

/* -------------------- FETCH IMAGE LIST -------------------- */
if (isset($_GET['list_images']) && $_GET['list_images'] === '1') {
    $rows = [];
    $result = $conn->query("SELECT id, file_path, title, description, created_at 
                            FROM farm_images ORDER BY created_at DESC");
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
    <form id="add-video-form" enctype="multipart/form-data" method="POST">
      <label>YouTube Link:</label>
      <input type="text" name="youtube_link" placeholder="Paste YouTube link here">
      <label>Or Upload Video File:</label>
      <input type="file" name="video_file" accept="video/*">
      <label>Or Upload Image File:</label>
      <input type="file" name="image_file" accept="image/*">
      <label>Title:</label>
      <input type="text" name="title" placeholder="Enter title">
      <label>Description:</label>
      <textarea name="description" placeholder="Enter description" rows="3"></textarea>
      <button type="submit" class="btn btn-primary">Add Guide</button>
    </form>
  </div>

  <!-- Render existing videos -->
  <div id="video-list" class="video-cards">
    <?php
    $result = $conn->query("SELECT id,youtube_id,file_path,title,description FROM farm_videos ORDER BY created_at DESC");
    if ($result && $result->num_rows > 0) {
    while ($video = $result->fetch_assoc()) {
        echo "<div class='video-card'>
                <h3>{$video['title']}</h3>
                <p>{$video['description']}</p>";
        if (!empty($video['youtube_id'])) {
            $thumb = "https://img.youtube.com/vi/{$video['youtube_id']}/hqdefault.jpg";
                        echo "<img src='{$thumb}' alt='{$video['title']}'>";
        } elseif (!empty($video['file_path'])) {
            echo "<video width='100%' controls>
                    <source src='{$video['file_path']}' type='video/mp4'>
                  </video>";
        }
        echo "
            <div class='video-actions'>
              <button onclick='deleteVideo({$video['id']})' class='btn btn-danger'>Delete</button>
              <button onclick='editVideo({$video['id']}, \"".htmlspecialchars($video['title'], ENT_QUOTES)."\", \"".htmlspecialchars($video['description'], ENT_QUOTES)."\")' class='btn btn-warning'>Edit</button>
            </div>
          </div>";
    }
    } else {
    echo "<p style='text-align:center; color:#777;'>🚫 No farm guides available yet.</p>";
}
?>
  </div>

  <!-- Render existing images -->
  <div id="image-list" class="image-cards" style="margin-top:30px;">
    <h3>🖼 Uploaded Images</h3>
    <?php
    $result = $conn->query("SELECT id,file_path,title,description FROM farm_images ORDER BY created_at DESC");
    if ($result && $result->num_rows > 0) {
        while ($img = $result->fetch_assoc()) {
            echo "
            <div class='image-card'>
              <img src='{$img['file_path']}' alt='{$img['title']}'>
              <h3>{$img['title']}</h3>
              <p>{$img['description']}</p>
              <div class='video-actions'>
                <button onclick='deleteImage({$img['id']})' class='btn btn-danger'>Delete</button>
                <button onclick='editImage({$img['id']}, \"".htmlspecialchars($img['title'], ENT_QUOTES)."\", \"".htmlspecialchars($img['description'], ENT_QUOTES)."\")' class='btn btn-warning'>Edit</button>
              </div>
            </div>";
        }
    } else {
        echo "<p style='text-align:center; color:#777;'>🚫 No farm images available yet.</p>";
    }
    ?>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <h3>Edit Guide</h3>
    <label>Title:</label>
    <input type="text" id="editTitleInput">
    <label>Description:</label>
    <textarea id="editDescInput" rows="3"></textarea>
    <div class="modal-actions">
      <button id="confirmEditBtn" class="btn btn-success">Save</button>
      <button onclick="closeModal('editModal')" class="btn btn-danger">Cancel</button>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <h3>Confirm Delete</h3>
    <p id="deleteMessage">Are you sure you want to delete this guide?</p>
    <div class="modal-actions">
      <button id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      <button onclick="closeModal('deleteModal')" class="btn btn-warning">Cancel</button>
    </div>
  </div>
</div>

<link rel="stylesheet" href="admin-guides-farm.css">
<script src="admin-guides-farm.js"></script>
