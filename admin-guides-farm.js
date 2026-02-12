// admin-guides-farm.js

function openEditModal(id, currentTitle, currentDesc) {
  // populate modal inputs
  document.getElementById("editTitleInput").value = currentTitle;
  document.getElementById("editDescInput").value = currentDesc;

  // store the id temporarily
  document.getElementById("confirmEditBtn").setAttribute("data-id", id);

  // show modal
  document.getElementById("editModal").style.display = "block";
}

function openDeleteModal(id) {
  document.getElementById("deleteMessage").textContent = "Are you sure you want to delete this guide?";
  document.getElementById("confirmDeleteBtn").setAttribute("data-id", id);

  // show modal
  document.getElementById("deleteModal").style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

// Hook up confirm buttons once DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  const confirmEditBtn = document.getElementById("confirmEditBtn");
  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

  if (confirmEditBtn) {
    confirmEditBtn.addEventListener("click", function() {
      const id = this.getAttribute("data-id");
      const newTitle = document.getElementById("editTitleInput").value;
      const newDesc = document.getElementById("editDescInput").value;

      const formData = new FormData();
      formData.append("edit_id", id);
      formData.append("new_title", newTitle);
      formData.append("new_description", newDesc);

      fetch("admin-guides-farm.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(result => {
          alert(result.message);
          if (result.status === "success") {
            closeModal("editModal");
            loadPage("admin-guides-farm.php");
          }
        });
    });
  }

  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", function() {
      const id = this.getAttribute("data-id");

      const formData = new FormData();
      formData.append("delete_id", id);

      fetch("admin-guides-farm.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(result => {
          alert(result.message);
          if (result.status === "success") {
            closeModal("deleteModal");
            loadPage("admin-guides-farm.php");
          }
        });
    });
  }
});