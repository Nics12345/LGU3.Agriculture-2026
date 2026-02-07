<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  echo "<p style='color:red;'>Access denied.</p>";
  exit;
}

include 'db.php'; 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
<link rel="stylesheet" href="admin-dashboard.css">

<h2>👥 User Management</h2>
<p>View, edit, or remove registered users.</p>

<div class="user-table-wrapper">
  <table id="users-table">
    <thead>
      <tr>
        <th>👤 Full Name</th>
        <th>📧 Email</th>
        <th>📞 Phone</th>
        <th>🏠 Address</th>
        <th>📅 Created</th>
        <th>⚙️ Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = $conn->query("SELECT id, fullname, email, phone, address, created_at FROM users ORDER BY created_at DESC");
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $date = date("F j, Y", strtotime($row['created_at']));
          echo "<tr>
                  <td>" . htmlspecialchars($row['fullname']) . "</td>
                  <td>" . htmlspecialchars($row['email']) . "</td>
                  <td>" . htmlspecialchars($row['phone']) . "</td>
                  <td>" . htmlspecialchars($row['address']) . "</td>
                  <td>" . $date . "</td>
                  <td>
                    <button class='btn btn-warning edit-btn'
                            data-id='" . $row['id'] . "'
                            data-name='" . htmlspecialchars($row['fullname']) . "'
                            data-email='" . htmlspecialchars($row['email']) . "'
                            data-phone='" . htmlspecialchars($row['phone']) . "'
                            data-address='" . htmlspecialchars($row['address']) . "'>✏️ Edit</button>
                    <button class='btn btn-danger delete-btn' data-id='" . $row['id'] . "'>🗑 Delete</button>
                  </td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='6' style='text-align:center;'>🚫 No users found</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">✏️ Edit User</div>
    <form id="editForm" class="form-table">
      <table>
        <tr>
          <th>Full Name:</th>
          <td><input type="text" id="edit-name" name="fullname" required></td>
        </tr>
        <tr>
          <th>Email:</th>
          <td><input type="email" id="edit-email" name="email" required></td>
        </tr>
        <tr>
          <th>Phone:</th>
          <td><input type="text" id="edit-phone" name="phone"></td>
        </tr>
        <tr>
          <th>Address:</th>
          <td><input type="text" id="edit-address" name="address"></td>
        </tr>
      </table>
      <input type="hidden" id="edit-id" name="id">
      <div class="modal-actions">
        <button type="button" onclick="closeModal('editModal')" class="btn btn-danger">Cancel</button>
        <button type="submit" class="btn btn-warning">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">Confirm Delete</div>
    <p>Are you sure you want to delete this user?</p>
    <div class="modal-actions">
      <button type="button" onclick="closeModal('deleteModal')" class="btn btn-warning">Cancel</button>
      <button id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
    </div>
  </div>
</div>

<script src="admin-dashboard.js"></script>