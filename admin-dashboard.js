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

      // ✅ Re-bind forms after content is loaded
      const adminForm = document.getElementById("create-admin-form");
      if (adminForm && typeof bindCreateAdminForm === "function") {
        bindCreateAdminForm(adminForm);
      }

      const videoForm = document.getElementById("add-video-form");
      if (videoForm && typeof bindAddVideoForm === "function") {
        bindAddVideoForm(videoForm);
      }

      const marketForm = document.getElementById("add-market-form");
      if (marketForm && typeof bindMarketForm === "function") {
        bindMarketForm(marketForm);
      }

      // ✅ Re-bind User Management handlers
      if (document.getElementById("users-table") && typeof bindUserManagement === "function") {
        bindUserManagement();
      }

      // ✅ Re-init pest/farm guide scripts after their pages are loaded
      if (page === "admin-guides-pest.php" && typeof initPestJS === "function") {
        initPestJS();
      }
      if (page === "admin-guides-farm.php" && typeof initFarmJS === "function") {
        initFarmJS();
      }
    })
    .catch(err => {
      container.innerHTML = "<p>Error loading content.</p>";
      console.error(err);
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
        // ✅ Refresh both lists dynamically
        loadFarmVideos();
        loadFarmImages();
      }
    } catch (err) {
      showToast("Error adding guide", "error");
    }
  });
}

// Toggle Guides Management dropdown
function toggleDropdown() {
  const dropdown = document.getElementById("guidesDropdown");
  if (!dropdown) return;
  dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
}

// Logout function
function logout() {
  window.location.href = "logout.php";
}

// Toast notifications
function showToast(message, type = "success") {
  const toast = document.getElementById("toast");
  if (!toast) return;
  toast.textContent = message;
  toast.className = "toast " + type;
  toast.classList.add("show");

  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
}

// Refresh admin table (now calls merged page)
async function refreshAdminTable() {
  try {
    const response = await fetch("admin-users-admin.php?list=1");
    const admins = await response.json();

    const tbody = document.querySelector("#admins-table tbody");
    if (!tbody) return;
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

// Bind admin creation form (now posts to merged page)
function bindCreateAdminForm(form) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("admin-users-admin.php", {
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
          const errorEl = document.getElementById(result.field + "-error");
          if (errorEl) errorEl.textContent = result.message;
        }
      }
    } catch (err) {
      showToast("Error submitting form", "error");
    }
  });
}

async function loadFarmImages() {
  const container = document.getElementById("image-list");
  if (!container) return;

  container.innerHTML = "<p class='loading'>🔄 Updating images...</p>";

  try {
    const response = await fetch("admin-guides-farm.php?list_images=1");
    const images = await response.json();
    container.innerHTML = "";

    if (images.length > 0) {
      images.forEach(img => {
        const card = document.createElement("div");
        card.className = "image-card";
        card.innerHTML = `
          <img src="${img.file_path}" alt="${img.title}">
          <h3>${img.title}</h3>
          <p>${img.description || ""}</p>
          <div class="video-actions">
            <button onclick="deleteImage(${img.id})" class="btn btn-danger">Delete</button>
            <button onclick="editImage(${img.id}, '${img.title.replace(/'/g,"&#39;")}', '${(img.description || "").replace(/'/g,"&#39;")}')" class="btn btn-warning">Edit</button>
          </div>
        `;
        container.appendChild(card);
      });
    } else {
      container.innerHTML = "<p style='text-align:center; color:#777;'>🚫 No farm images available yet.</p>";
    }
  } catch (err) {
    container.innerHTML = "<p style='text-align:center; color:red;'>❌ Error loading images.</p>";
    console.error("Error loading images", err);
  }
}

// Load farm videos dynamically
async function loadFarmVideos() {
  const container = document.getElementById("video-list");
  if (!container) return;

  container.innerHTML = "<p class='loading'>🔄 Updating videos...</p>";

  try {
    const response = await fetch("admin-guides-farm.php?list=1");
    const videos = await response.json();
    container.innerHTML = "";

    if (videos.length > 0) {
      videos.forEach(video => {
        const card = document.createElement("div");
        card.className = "video-card";

        if (video.youtube_id) {
          const thumb = `https://img.youtube.com/vi/${video.youtube_id}/hqdefault.jpg`;
          const url = `https://www.youtube.com/watch?v=${video.youtube_id}`;
          card.innerHTML = `
            <a href="${url}" target="_blank">
              <img src="${thumb}">
              <h3>${video.title}</h3>
              <p>${video.description || ""}</p>
            </a>
            <div class="video-actions">
              <button onclick="deleteVideo(${video.id})" class="btn btn-danger">Delete</button>
              <button onclick="editVideo(${video.id}, '${video.title.replace(/'/g,"&#39;")}', '${(video.description || "").replace(/'/g,"&#39;")}')" class="btn btn-warning">Edit</button>
            </div>
          `;
        } else if (video.file_path) {
          card.innerHTML = `
            <video width="100%" controls>
              <source src="${video.file_path}" type="video/mp4">
            </video>
            <h3>${video.title}</h3>
            <p>${video.description || ""}</p>
            <div class="video-actions">
              <button onclick="deleteVideo(${video.id})" class="btn btn-danger">Delete</button>
              <button onclick="editVideo(${video.id}, '${video.title.replace(/'/g,"&#39;")}', '${(video.description || "").replace(/'/g,"&#39;")}')" class="btn btn-warning">Edit</button>
            </div>
          `;
        }

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

// Track IDs for edit/delete
let editType = null;
let editVideoId = null;
let editImageId = null;
let deleteVideoId = null;
let deleteImageId = null;


function editImage(id, currentTitle) {
  editImageId = id;
  document.getElementById("editTitleInput").value = currentTitle;
  openModal("editModal"); // reuse same modal
}

async function confirmEditImage() {
  const newTitle = document.getElementById("editTitleInput").value.trim();
  if (newTitle === "") return;
  const formData = new FormData();
  formData.append("edit_image_id", editImageId);
  formData.append("new_title", newTitle);

  const response = await fetch("admin-guides-farm.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") loadFarmImages();
  closeModal("editModal");
}

function deleteImage(id) {
  deleteImageId = id;
  openModal("deleteModal"); // reuse same modal
}

async function confirmDeleteImage() {
  const formData = new FormData();
  formData.append("delete_image_id", deleteImageId);

  const response = await fetch("admin-guides-farm.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") loadFarmImages();
  closeModal("deleteModal");
}


function openModal(id) {
  const modal = document.getElementById(id);
  if (!modal) {
    console.warn(`Modal ${id} not found`);
    return;
  }
  modal.classList.add("show");
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (!modal) {
    console.warn(`Modal ${id} not found`);
    return;
  }
  modal.classList.remove("show");
}

// Open edit modal for video
function editVideo(id, currentTitle, currentDesc) {
  editType = "video";
  editVideoId = id;
  document.getElementById("editTitleInput").value = currentTitle;
  document.getElementById("editDescInput").value = currentDesc;
  document.getElementById("confirmEditBtn").onclick = confirmEdit;
  openModal("editModal");
}

// Confirm edit video
async function confirmEdit() {
  const newTitle = document.getElementById("editTitleInput").value.trim();
  const newDesc = document.getElementById("editDescInput").value.trim();
  if (newTitle === "") return;

  const formData = new FormData();
  formData.append("edit_id", editVideoId);
  formData.append("new_title", newTitle);
  formData.append("new_description", newDesc);

  const response = await fetch("admin-guides-farm.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") loadFarmVideos();
  closeModal("editModal");
}

// Delete video
function deleteVideo(id) {
  deleteVideoId = id;
  document.getElementById("deleteMessage").textContent = "Are you sure you want to delete this video guide?";
  document.getElementById("confirmDeleteBtn").onclick = confirmDelete;
  openModal("deleteModal");
}

// Confirm delete video
async function confirmDelete() {
  const formData = new FormData();
  formData.append("delete_id", deleteVideoId);
  const response = await fetch("admin-guides-farm.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") {
    loadFarmVideos(); // ✅ refresh videos only
  }
  closeModal("deleteModal");
}


// Open edit modal for image
function editImage(id, currentTitle, currentDesc) {
  editType = "image";
  editImageId = id;
  document.getElementById("editTitleInput").value = currentTitle;
  document.getElementById("editDescInput").value = currentDesc;
  document.getElementById("confirmEditBtn").onclick = confirmEditImage;
  openModal("editModal");
}


// Confirm edit image
async function confirmEditImage() {
  const newTitle = document.getElementById("editTitleInput").value.trim();
  const newDesc = document.getElementById("editDescInput").value.trim();
  if (newTitle === "") return;

  const formData = new FormData();
  formData.append("edit_image_id", editImageId);
  formData.append("new_title", newTitle);
  formData.append("new_description", newDesc);

  const response = await fetch("admin-guides-farm.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") loadFarmImages();
  closeModal("editModal");
}


// Delete image
function deleteImage(id) {
  deleteImageId = id;
  document.getElementById("deleteMessage").textContent = "Are you sure you want to delete this image guide?";
  document.getElementById("confirmDeleteBtn").onclick = confirmDeleteImage;
  openModal("deleteModal");
}

// Confirm Delete Image
async function confirmDeleteImage() {
  const formData = new FormData();
  formData.append("delete_image_id", deleteImageId);
  const response = await fetch("admin-guides-farm.php", { method:"POST", body:formData });
  const result = await response.json();
  showToast(result.message, result.status);
  if (result.status === "success") {
    loadFarmImages(); // ✅ refresh images only
  }
  closeModal("deleteModal");
}

// ✅ Single initialization block
document.addEventListener("DOMContentLoaded", () => {
  const adminForm = document.getElementById("create-admin-form");
  if (adminForm) bindCreateAdminForm(adminForm);

  const videoForm = document.getElementById("add-video-form");
  if (videoForm) bindAddVideoForm(videoForm);
  
  if (document.getElementById("image-list")) {
  loadFarmImages();
}
  // ✅ Bind User Management if the table exists
  if (document.getElementById("users-table")) {
    bindUserManagement();
  }
  if (document.getElementById("video-list")) {
  loadFarmVideos();
}
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


  // Handle Edit form
const editForm = document.getElementById("editForm");
if (editForm) {
  editForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(editForm);

    try {
      const response = await fetch("admin-users-admin.php", { method:"POST", body:formData });
      const result = await response.json();
      showToast(result.message || "User updated", result.status);

      if (result.status === "success") {
        closeModal("editModal");
        // ✅ Delay reload so toast shows
        setTimeout(() => {
          loadPage("admin-users-admin.php");
        }, 1000);
      } else {
        closeModal("editModal");
      }
    } catch (err) {
      showToast("Error updating user", "error");
      closeModal("editModal");
    }
  });
}
}


// Handle deletion via AJAX
async function deleteMarketItem(id) {
  if (confirm("Are you sure you want to remove this product?")) {
    const formData = new FormData();
    formData.append('delete_id', id);

    try {
      const response = await fetch('admin-market-data.php', {
        method: 'POST',
        body: formData
      });
      const result = await response.json();

      if (typeof showToast === "function") {
        showToast(result.message, result.status);
      } else {
        alert(result.message);
      }

      if (result.status === 'success') {
        // Refresh the current page view
        loadPage('admin-market-data.php');
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }
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

// ✅ Updated: Admin creation now posts to merged page
function bindCreateAdminForm(form) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("admin-users-admin.php", {
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
          const errorEl = document.getElementById(result.field + "-error");
          if (errorEl) errorEl.textContent = result.message;
        }
      }
    } catch (err) {
      showToast("Error submitting form", "error");
    }
  });
}

// === Tab Navigation ===
function openTab(tabId) {
  // Hide all tab contents
  document.querySelectorAll(".tab-content").forEach(el => el.classList.remove("active"));
  // Remove active state from all buttons
  document.querySelectorAll(".tab-btn").forEach(el => el.classList.remove("active"));
  // Show the selected tab
  const tab = document.getElementById(tabId);
  if (tab) tab.classList.add("active");
  // Highlight the clicked button
  const btn = document.querySelector(`.tab-btn[onclick="openTab('${tabId}')"]`);
  if (btn) btn.classList.add("active");
}

// Expose globally so HTML onclick works
window.deleteVideo = deleteVideo;
window.editVideo = editVideo;
window.loadPage = loadPage;
window.toggleDropdown = toggleDropdown;
window.logout = logout;
window.showToast = showToast;
window.openModal = openModal;
window.closeModal = closeModal;
window.confirmEdit = confirmEdit;
window.confirmDelete = confirmDelete;
window.deleteMarketItem = deleteMarketItem;
window.openTab = openTab;