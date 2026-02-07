<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  echo "<p style='color:red;'>Access denied.</p>";
  exit;
}

include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Handle category creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_category'])) {
    $name = trim($_POST['new_category']);
    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO pest_categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        echo json_encode(["status"=>"success","message"=>"Category added"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Category name required"]);
    }
    exit;
}

// Handle video add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['youtube_link'])) {
    $link  = trim($_POST['youtube_link']);
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);

    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([^\&\?\/]+)/', $link, $matches);
    $youtube_id = $matches[1] ?? '';

    if ($youtube_id !== '' && $category_id > 0) {
        if ($title === '') $title = "Untitled Video";
        $stmt = $conn->prepare("INSERT INTO pest_videos (youtube_id, title, category_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $youtube_id, $title, $category_id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(["status"=>"success","message"=>"Video added"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Invalid input"]);
    }
    exit;
}

// Handle category edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category_id'])) {
    $id   = intval($_POST['edit_category_id']);
    $name = trim($_POST['new_category_name']);
    if ($name !== '') {
        $stmt = $conn->prepare("UPDATE pest_categories SET name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(["status"=>"success","message"=>"Category updated"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Category name required"]);
    }
    exit;
}

// Handle category delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category_id'])) {
    $id = intval($_POST['delete_category_id']);
    $stmt = $conn->prepare("DELETE FROM pest_categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Category deleted"]);
    exit;
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM pest_videos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Video deleted"]);
    exit;
}

// Handle edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id    = intval($_POST['edit_id']);
    $title = trim($_POST['new_title']);
    $stmt  = $conn->prepare("UPDATE pest_videos SET title=? WHERE id=?");
    $stmt->bind_param("si", $title, $id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(["status"=>"success","message"=>"Title updated"]);
    exit;
}
?>

<div class="guides-container">
  <h2>🐛 Pest Control Guides</h2>

  <!-- Add Category Form -->
  <div class="add-video-form">
    <form id="add-category-form">
      <label>New Category:</label>
      <input type="text" name="new_category" required placeholder="Enter category name">
      <button type="submit" class="btn btn-primary">Add Category</button>
    </form>
  </div>

  <!-- Add Video Form -->
  <div class="add-video-form">
    <form id="add-video-form">
      <label>YouTube Link:</label>
      <input type="text" name="youtube_link" required placeholder="Paste YouTube link here">
      <label>Title (optional):</label>
      <input type="text" name="title" placeholder="Enter video title">
      <label>Category:</label>
      <select name="category_id" required>
        <option value="">-- Select Category --</option>
        <?php
        $cats = $conn->query("SELECT id,name FROM pest_categories ORDER BY name ASC");
        while ($cat = $cats->fetch_assoc()) {
            echo "<option value='{$cat['id']}'>".htmlspecialchars($cat['name'])."</option>";
        }
        ?>
      </select>
      <button type="submit" class="btn btn-primary">Add Video</button>
    </form>
  </div>

  <!-- Render existing videos grouped by category -->
  <?php
  $cats = $conn->query("SELECT id,name FROM pest_categories ORDER BY name ASC");
  if ($cats->num_rows > 0) {
      while ($cat = $cats->fetch_assoc()) {
          echo "<h3 style='margin-top:20px;'>🎬 ".htmlspecialchars($cat['name'])."</h3>";
          echo "<div class='category-actions'>
          <button onclick='editCategory({$cat['id']}, \"".htmlspecialchars($cat['name'], ENT_QUOTES)."\")' class='btn btn-warning'>Edit Category</button>
          <button onclick='deleteCategory({$cat['id']})' class='btn btn-danger'>Delete Category</button>
          </div>";
          echo "<div class='video-cards'>";
          $videos = $conn->query("SELECT id,youtube_id,title FROM pest_videos WHERE category_id={$cat['id']} ORDER BY created_at DESC");
          if ($videos->num_rows > 0) {
              while ($video = $videos->fetch_assoc()) {
                  $thumb = "https://img.youtube.com/vi/{$video['youtube_id']}/hqdefault.jpg";
                  $url   = "https://www.youtube.com/watch?v={$video['youtube_id']}";
                  echo "
                  <div class='video-card'>
                    <a href='{$url}' target='_blank'>
                      <img src='{$thumb}' alt='".htmlspecialchars($video['title'], ENT_QUOTES)."'>
                      <h3>".htmlspecialchars($video['title'])."</h3>
                    </a>
                    <div class='video-actions'>
                      <button onclick='deleteVideo({$video['id']})' class='btn btn-danger'>Delete</button>
                      <button onclick='editVideo({$video['id']}, \"".htmlspecialchars($video['title'], ENT_QUOTES)."\")' class='btn btn-warning'>Edit Title</button>
                    </div>
                  </div>
                  ";
              }
          } else {
              echo "<p style='color:#777;'>🚫 No videos in this category yet.</p>";
          }
          echo "</div>";
      }
  } else {
      echo "<p style='text-align:center; color:#777;'>🚫 No categories yet. Please add one.</p>";
  }
  ?>
</div>

<!-- Modals same as before -->
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

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal">
  <div class="modal-content">
    <h3>Edit Category</h3>
    <input type="text" id="editCategoryInput" style="width:100%; padding:8px;">
    <div class="modal-actions">
      <button class="btn btn-warning" onclick="confirmEditCategory()">Save</button>
      <button class="btn btn-danger" onclick="closeModal('editCategoryModal')">Cancel</button>
    </div>
  </div>
</div>

<!-- Delete Category Modal -->
<div id="deleteCategoryModal" class="modal">
  <div class="modal-content">
    <h3>Confirm Delete</h3>
    <p>Are you sure you want to delete this category? All videos inside will also be deleted.</p>
    <div class="modal-actions">
      <button class="btn btn-danger" onclick="confirmDeleteCategory()">Delete</button>
      <button class="btn btn-warning" onclick="closeModal('deleteCategoryModal')">Cancel</button>
    </div>
  </div>
</div>