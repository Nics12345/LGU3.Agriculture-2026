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
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Debug function
function logPestError($msg) {
    $logFile = __DIR__ . '/error_pest_guide_log.txt';
    file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] " . $msg . "\n", FILE_APPEND);
}

/* -------------------- UPLOAD -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    header("Content-Type: application/json");
    
    try {
        $title = trim($_POST['title'] ?? 'Untitled Video');
        $description = trim($_POST['description'] ?? '');

        $videoPath = null;
        $youtubeId = null;

        // File upload
        if (!empty($_FILES['video']['name'])) {
            $uploadDir = __DIR__ . "/uploads/pest_videos/";
            
            // Check directory and create if needed
            if (!is_dir($uploadDir)) {
                if (!@mkdir($uploadDir, 0777, true)) {
                    throw new Exception("Failed to create pest_videos directory. Check permissions.");
                }
            }
            
            // Verify writable
            if (!is_writable($uploadDir)) {
                throw new Exception("pest_videos directory is not writable. Check folder permissions.");
            }

            $fileName = time() . "_" . basename($_FILES['video']['name']);
            $filePath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['video']['tmp_name'], $filePath)) {
                throw new Exception("Failed to move uploaded file. Check server permissions and disk space.");
            }
            // store relative path for consistency
            $videoPath = "uploads/pest_videos/" . $fileName;
        }

        // YouTube link
        if (!empty($_POST['youtube_link'])) {
            $link = trim($_POST['youtube_link']);
            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([^\&\?\/]+)/', $link, $matches);
            $youtubeId = $matches[1] ?? '';
            if ($youtubeId === '') {
                throw new Exception("Invalid YouTube link format. Use full YouTube URL.");
            }
        }

        if (!$videoPath && !$youtubeId) {
            throw new Exception("Please upload a file or provide a YouTube link");
        }

        $stmt = $conn->prepare("INSERT INTO pest_videos (title, description, video_path, youtube_id) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("ssss", $title, $description, $videoPath, $youtubeId);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert pest guide: " . $stmt->error);
        }
        $stmt->close();

        $msg  = "🐛 New pest guide added: $title";
        $link = "pest.php";
        $nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
        if ($nstmt) {
            $nstmt->bind_param("ss", $msg, $link);
            $nstmt->execute();
            $nstmt->close();
        }

        echo json_encode(["status"=>"success","message"=>"✓ Pest guide added successfully"]);
    } catch (Exception $e) {
        logPestError("UPLOAD ERROR: " . $e->getMessage());
        echo json_encode(["status"=>"error","message"=>"Pest guide failed: " . $e->getMessage()]);
    }
    exit;
}

/* -------------------- DELETE -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    header("Content-Type: application/json");
    
    try {
        $id = intval($_POST['delete_id']);
        $stmt = $conn->prepare("SELECT video_path FROM pest_videos WHERE id=?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to query pest guide: " . $stmt->error);
        }
        
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if (!empty($row['video_path'])) {
                $filePath = __DIR__ . "/" . $row['video_path'];
                if (file_exists($filePath)) {
                    if (!@unlink($filePath)) {
                        logPestError("Warning: Could not delete file: " . $filePath);
                    }
                }
            }
        }
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM pest_videos WHERE id=?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to delete pest guide: " . $stmt->error);
        }
        $stmt->close();

        $msg  = "🐛 Pest guide deleted (ID: $id)";
        $link = "pest.php";
        $nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
        if ($nstmt) {
            $nstmt->bind_param("ss", $msg, $link);
            $nstmt->execute();
            $nstmt->close();
        }

        echo json_encode(["status"=>"success","message"=>"✓ Pest guide deleted successfully"]);
    } catch (Exception $e) {
        logPestError("DELETE ERROR: " . $e->getMessage());
        echo json_encode(["status"=>"error","message"=>"Delete failed: " . $e->getMessage()]);
    }
    exit;
}

/* -------------------- EDIT -------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id    = intval($_POST['edit_id']);
    $title = trim($_POST['new_title']);
    $description = trim($_POST['new_description'] ?? '');

    $stmt  = $conn->prepare("UPDATE pest_videos SET title=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $description, $id);
    $stmt->execute();
    $stmt->close();

    $msg  = "🐛 Pest guide updated: $title";
    $link = "pest.php";
    $nstmt = $conn->prepare("INSERT INTO notifications (message, link) VALUES (?, ?)");
    $nstmt->bind_param("ss", $msg, $link);
    $nstmt->execute();
    $nstmt->close();

    echo json_encode(["status"=>"success","message"=>"Guide updated"]);
    exit;
}
?>

<link rel="stylesheet" href="admin-guides-pest.css">

<div class="guides-container">
  <h2>🐛 Pest Control Guides</h2>

  <!-- Unified Upload Form -->
  <div class="add-video-form">
    <form id="add-pest-video-form" enctype="multipart/form-data" method="POST">
      <label>Title:</label>
      <input type="text" name="title" required>
      <label>Description:</label>
      <textarea name="description" rows="4"></textarea>
      <label>Upload Video (optional):</label>
      <input type="file" name="video" accept="video/*">
      <label>YouTube Link (optional):</label>
      <input type="text" name="youtube_link" placeholder="Paste YouTube link">
      <button type="submit" class="btn btn-primary">Add Guide</button>
    </form>
  </div>

  <!-- Render existing guides -->
  <div class="video-cards">
    <?php
    $videos = $conn->query("SELECT id,title,description,video_path,youtube_id FROM pest_videos ORDER BY created_at DESC");
    if ($videos->num_rows > 0) {
        while ($video = $videos->fetch_assoc()) {
            echo "<div class='video-card'>";
            if (!empty($video['video_path'])) {
                echo "<video width='240' height='135' preload='metadata'
                        onclick='openModal(\"file\", \"{$video['video_path']}\",
                        \"".htmlspecialchars($video['title'], ENT_QUOTES)."\",
                        \"".htmlspecialchars($video['description'], ENT_QUOTES)."\")'>
                        <source src='{$video['video_path']}' type='video/mp4'>
                        Your browser does not support the video tag.
                      </video>";
            } elseif (!empty($video['youtube_id'])) {
                echo "<img src='https://img.youtube.com/vi/{$video['youtube_id']}/hqdefault.jpg' alt='Thumbnail'
                        onclick='openModal(\"youtube\", \"{$video['youtube_id']}\",
                        \"".htmlspecialchars($video['title'], ENT_QUOTES)."\",
                        \"".htmlspecialchars($video['description'], ENT_QUOTES)."\")'>";
            }
            echo "<h3>".htmlspecialchars($video['title'])."</h3>";
            echo "<p>".nl2br(htmlspecialchars($video['description']))."</p>";
            echo "<div class='video-actions'>
                    <button onclick='openDeletePestModal({$video['id']})' class='btn btn-danger'>Delete</button>
                    <button onclick='openEditPestModal({$video['id']}, \"".htmlspecialchars($video['title'], ENT_QUOTES)."\", \"".htmlspecialchars($video['description'], ENT_QUOTES)."\")' class='btn btn-warning'>Edit</button>
                  </div>";
            echo "</div>";
        }
    } else {
        echo "<div class='no-videos'><p>🚫 No pest guides yet.</p></div>";
    }
    ?>
  </div>
</div>

<!-- Video Modal -->
<div id="videoModal" class="modal">
  <div class="modal-content">
    <h3 id="modalTitle"></h3>
    <div id="modalVideoContainer"></div>
    <p id="modalDesc"></p>
    <div class="modal-actions">
      <button class="btn btn-danger" onclick="closeModal()">Close</button>
    </div>
  </div>
</div>

<!-- Delete Pest Guide Modal -->
<div id="deletePestModal" class="modal">
  <div class="modal-content">
    <h3>Confirm Delete</h3>
    <p>Are you sure you want to delete this pest guide?</p>
    <div class="modal-actions">
      <button class="btn btn-danger" onclick="confirmDeletePest()">Delete</button>
      <button class="btn btn-secondary" onclick="closeModal('deletePestModal')">Cancel</button>
    </div>
  </div>
</div>

<!-- Edit Pest Guide Modal -->
<div id="editPestModal" class="modal">
  <div class="modal-content">
    <h3>Edit Pest Guide</h3>
    <label>Title:</label>
    <input type="text" id="editPestTitle">
    <label>Description:</label>
    <textarea id="editPestDesc" rows="4"></textarea>
    <div class="modal-actions">
      <button class="btn btn-primary" onclick="confirmEditPest()">Save</button>
      <button class="btn btn-secondary" onclick="closeModal('editPestModal')">Cancel</button>
    </div>
  </div>
</div>

<!-- Link the pest-specific CSS and JS -->
<link rel="stylesheet" href="admin-guides-pest.css">
<script src="admin-guides-pest.js"></script>