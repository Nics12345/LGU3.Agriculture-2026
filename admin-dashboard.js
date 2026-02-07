// Load content dynamically into admin-content
function loadPage(page) {
  const container = document.getElementById("admin-content");
  container.innerHTML = "<p>Loading...</p>";

  fetch(page)
    .then(response => {
      if (!response.ok) throw new Error("Network error");
      return response.text();
    })
    .then(html => {
      // ✅ Inject the HTML
      container.innerHTML = html;

      // ✅ Re-bind admin form handler after content is loaded
      const adminForm = document.getElementById("create-admin-form");
      if (adminForm) bindCreateAdminForm(adminForm);

      // ✅ Re-bind video form handler after content is loaded
      const videoForm = document.getElementById("add-video-form");
      if (videoForm) bindAddVideoForm(videoForm);

      // ✅ Re-bind category form handler after content is loaded
      const categoryForm = document.getElementById("add-category-form");
      if (categoryForm) bindAddCategoryForm(categoryForm);

      // ✅ Re-bind pest video form handler after content is loaded
      const pestVideoForm = document.getElementById("add-video-form");
      if (pestVideoForm) bindAddPestVideoForm(pestVideoForm);

      // ✅ Re-bind user management handlers after content is loaded
      if (document.getElementById("users-table")) {
        bindUserManagement();
      }
    })
    .catch(err => {
      container.innerHTML = "<p>Error loading content.</p>";
      console.error(err);
    });
}
function loadPage(page) {
  const container = document.getElementById("admin-content");
  container.innerHTML = "<p>Loading...</p>";

  fetch(page)
    .then(response => response.text())
    .then(html => {
      container.innerHTML = html;

      // --- ADD THESE NEW BINDINGS BELOW ---
      
      // Re-bind Tech Assistant Form
      const assistantForm = document.getElementById("add-assistant-form");
      if (assistantForm) bindAssistantForm(assistantForm);

      // Re-bind Market Data Form
      const marketForm = document.getElementById("add-market-form");
      if (marketForm) bindMarketForm(marketForm);
async function deleteMarketItem(id) {
    if (!confirm("Are you sure you want to delete this product?")) return;

    const formData = new FormData();
    formData.append('delete_id', id);

    try {
        const response = await fetch('admin-market-data.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        // Use your existing showToast function if available
        if (window.showToast) {
            showToast(result.message, result.status);
        } else {
            alert(result.message);
        }

        // Reload the section to show it's gone
        if (result.status === 'success') {
            loadPage('admin-market-data.php');
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred while deleting.");
    }
}

// Make it globally accessible
window.deleteMarketItem = deleteMarketItem;
      // --- EXISTING BINDINGS ---
      const adminForm = document.getElementById("create-admin-form");
      if (adminForm) bindCreateAdminForm(adminForm);
      // ... rest of your existing bindings ...
    });
}

// Add these helper functions at the bottom of admin-dashboard.js
function bindAssistantForm(form) {
    form.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const res = await fetch("admin-tech-assistant.php", { method: "POST", body: formData });
        const result = await res.json();
        showToast(result.message, result.status);
        if (result.status === "success") loadPage("admin-tech-assistant.php");
    };
}

function bindMarketForm(form) {
    form.onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const res = await fetch("admin-market-data.php", { method: "POST", body: formData });
        const result = await res.json();
        showToast(result.message, result.status);
        if (result.status === "success") loadPage("admin-market-data.php");
    };
}
// Bind admin creation form
function bindCreateAdminForm(form) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("admin-create.php", {
        method: "POST",
        body: formData
      });
      const result = await response.json();

      if (result.status === "success") {
        showToast(result.message, "success");
        form.reset();
        refreshAdminTable();
      } else {
        showToast(result.message, "error");
        if (result.field) {
          document.getElementById(result.field + "-error").textContent = result.message;
        }
      }
    } catch (err) {
      showToast("Error submitting form", "error");
    }
  });
}

// Bind add video form
function bindAddVideoForm(form) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("admin-guides-farm.php", {
        method: "POST",
        body: formData
      });
      const result = await response.json();
      showToast(result.message, result.status);
      if (result.status === "success") {
        form.reset();
        loadFarmVideos(); // refresh list
      }
    } catch (err) {
      showToast("Error adding video", "error");
    }
  });
}

// Toggle Guides Management dropdown
function toggleDropdown() {
  const dropdown = document.getElementById("guidesDropdown");
  dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
}

// Logout function
function logout() {
  window.location.href = "logout.php";
}

// Toast notifications
function showToast(message, type = "success") {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.className = "toast " + type;
  toast.classList.add("show");

  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
}

// Refresh admin table
async function refreshAdminTable() {
  try {
    const response = await fetch("admin-create.php?list=1");
    const admins = await response.json();

    const tbody = document.querySelector("#admins-table tbody");
    tbody.innerHTML = "";

    admins.forEach(admin => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${admin.fullname}</td>
        <td>${admin.email}</td>
        <td>${admin.created_at}</td>
      `;
      tbody.appendChild(row);
    });
  } catch (err) {
    console.error("Error refreshing admin table", err);
  }
}

// Load farm videos dynamically
async function loadFarmVideos() {
  const container = document.getElementById("video-list");
  // Show loading message while fetching
  container.innerHTML = "<p class='loading'>🔄 Updating videos...</p>";

  try {
    const response = await fetch("admin-guides-farm.php?list=1");
    const videos = await response.json();
    container.innerHTML = "";

    if (videos.length > 0) {
      videos.forEach(video => {
        const thumb = `https://img.youtube.com/vi/${video.youtube_id}/hqdefault.jpg`;
        const url = `https://www.youtube.com/watch?v=${video.youtube_id}`;

        const card = document.createElement("div");
        card.className = "video-card";
        card.innerHTML = `
          <a href="${url}" target="_blank">
            <img src="${thumb}">
            <h3>${video.title}</h3>
          </a>
          <div class="video-actions">
            <button onclick="deleteVideo(${video.id})" class="btn btn-danger">Delete</button>
            <button onclick="editVideo(${video.id}, '${video.title.replace(/'/g,"&#39;")}')" class="btn btn-warning">Edit Title</button>
          </div>
        `;
        container.appendChild(card);
      });
    } else {
      container.innerHTML = "<p style='text-align:center; color:#777;'>🚫 No farm guides available yet.</p>";
    }
  } catch (err) {
    container.innerHTML = "<p style='text-align:center; color:red;'>❌ Error loading videos.</p>";
    console.error("Error loading videos", err);
  }
}

// === Modal Helpers ===
let editVideoId = null;
let deleteVideoId = null;

function openModal(id) {
  document.getElementById(id).style.display = "flex";
}
function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

// Edit video (open modal)
function editVideo(id, currentTitle) {
  editVideoId = id;
  document.getElementById("editTitleInput").value = currentTitle;
  openModal("editModal");
}

// Confirm edit
async function confirmEdit() {
  const newTitle = document.getElementById("editTitleInput").value.trim();
  if (newTitle === "") return;
  const formData = new FormData();
  formData.append("edit_id", editVideoId);
  formData.append("new_title", newTitle);
  const response = await fetch("admin-guides-farm.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") loadFarmVideos();
  closeModal("editModal");
}

// Delete video (open modal)
function deleteVideo(id) {
  deleteVideoId = id;
  openModal("deleteModal");
}

// Confirm delete
async function confirmDelete() {
  const formData = new FormData();
  formData.append("delete_id", deleteVideoId);
  const response = await fetch("admin-guides-farm.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") loadFarmVideos();
  closeModal("deleteModal");
}

// ✅ Single initialization block
document.addEventListener("DOMContentLoaded", () => {
  const adminForm = document.getElementById("create-admin-form");
  if (adminForm) bindCreateAdminForm(adminForm);

  const videoForm = document.getElementById("add-video-form");
  if (videoForm) bindAddVideoForm(videoForm);

  // ✅ Bind User Management if the table exists
  if (document.getElementById("users-table")) {
    bindUserManagement();
  }

  loadFarmVideos();
});

// === User Management ===
let deleteUserId = null;

// Bind User Management buttons
function bindUserManagement() {
  // Edit buttons
  document.querySelectorAll(".edit-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      document.getElementById("edit-id").value = btn.dataset.id;
      document.getElementById("edit-name").value = btn.dataset.name;
      document.getElementById("edit-email").value = btn.dataset.email;
      document.getElementById("edit-phone").value = btn.dataset.phone;
      document.getElementById("edit-address").value = btn.dataset.address;
      openModal("editModal");
    });
  });

  // Delete buttons
  document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      deleteUserId = btn.dataset.id;
      openModal("deleteModal");
    });
  });

  // Confirm Delete
  const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", async () => {
      if (!deleteUserId) return;
      const formData = new FormData();
      formData.append("id", deleteUserId);

      try {
  const response = await fetch("delete-user.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message || "User deleted", result.status);
  if (result.status === "success") {
    loadPage("admin-users.php"); // ✅ reload only User Management
  }
} catch (err) {
  showToast("Error deleting user", "error");
}
      closeModal("deleteModal");
    });
  }

  // Handle Edit form
  const editForm = document.getElementById("editForm");
  if (editForm) {
    editForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(editForm);

      try {
  const response = await fetch("update-users.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message || "User updated", result.status);
  if (result.status === "success") {
    loadPage("admin-users.php"); // ✅ reload only User Management
  }
} catch (err) {
  showToast("Error updating user", "error");
}
      closeModal("editModal");
    });
  }
}

// Add Categories
function bindAddCategoryForm(form) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("admin-guides-pest.php", {
        method: "POST",
        body: formData
      });
      const result = await response.json();
      showToast(result.message, result.status);

      if (result.status === "success") {
        // Reload Pest Guides section only
        loadPage("admin-guides-pest.php");
      }
    } catch (err) {
      showToast("Error adding category", "error");
    }
  });
}

function bindAddPestVideoForm(form) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("admin-guides-pest.php", {
        method: "POST",
        body: formData
      });
      const result = await response.json();
      showToast(result.message, result.status);

      if (result.status === "success") {
        loadPage("admin-guides-pest.php");
      }
    } catch (err) {
      showToast("Error adding video", "error");
    }
  });
}

// Edit Categories
let editCategoryId = null;
let deleteCategoryId = null;

function editCategory(id, currentName) {
  editCategoryId = id;
  document.getElementById("editCategoryInput").value = currentName;
  openModal("editCategoryModal");
}

async function confirmEditCategory() {
  const newName = document.getElementById("editCategoryInput").value.trim();
  if (newName === "") return;
  const formData = new FormData();
  formData.append("edit_category_id", editCategoryId);
  formData.append("new_category_name", newName);

  const response = await fetch("admin-guides-pest.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") loadPage("admin-guides-pest.php");
  closeModal("editCategoryModal");
}

function deleteCategory(id) {
  deleteCategoryId = id;
  openModal("deleteCategoryModal");
}

async function confirmDeleteCategory() {
  const formData = new FormData();
  formData.append("delete_category_id", deleteCategoryId);

  const response = await fetch("admin-guides-pest.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") loadPage("admin-guides-pest.php");
  closeModal("deleteCategoryModal");
}

// Expose globally
window.loadPage = loadPage;
window.toggleDropdown = toggleDropdown;
window.logout = logout;
window.showToast = showToast;
window.openModal = openModal;
window.closeModal = closeModal;
window.confirmEdit = confirmEdit;
window.confirmDelete = confirmDelete;