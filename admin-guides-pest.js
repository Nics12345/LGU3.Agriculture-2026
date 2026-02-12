// admin-guides-pest.js

function initPestJS() {
  // =======================
  // Pest Guide Upload Form
  // =======================
  const pestVideoForm = document.getElementById("add-pest-video-form");
  if (pestVideoForm) {
    pestVideoForm.addEventListener("submit", async (e) => {
      e.preventDefault(); // stop normal refresh
      console.log("Intercepted pest guide form submit");

      const formData = new FormData(pestVideoForm);

      try {
        const response = await fetch("admin-guides-pest.php", {
          method: "POST",
          body: formData
        });

        if (!response.ok) {
          throw new Error("Network response was not ok");
        }

        const result = await response.json();
        console.log("Upload result:", result);

        showToast(result.message, result.status);

        if (result.status === "success") {
          // reload the pest guides list
          loadPage("admin-guides-pest.php");
        }
      } catch (err) {
        console.error("Pest upload error:", err);
        showToast("Error adding pest guide", "error");
      }
    });
  } else {
    console.error("Form #add-pest-video-form not found in DOM");
  }
}

// =======================
// Delete Pest Guide Modal
// =======================
function openDeletePestModal(id) {
  const modal = document.getElementById("deletePestModal");
  modal.classList.add("show");
  modal.setAttribute("data-id", id);
}

function confirmDeletePest() {
  const modal = document.getElementById("deletePestModal");
  const id = modal.getAttribute("data-id");

  const formData = new FormData();
  formData.append("delete_id", id);

  fetch("admin-guides-pest.php", { method: "POST", body: formData })
    .then(res => res.json())
    .then(result => {
      showToast(result.message, result.status);
      if (result.status === "success") {
        loadPage("admin-guides-pest.php");
      }
    })
    .catch(err => {
      console.error("Delete error:", err);
      showToast("Error deleting pest guide", "error");
    })
    .finally(() => {
      modal.classList.remove("show");
    });
}

// =======================
// Edit Pest Guide Modal
// =======================
function openEditPestModal(id, currentTitle, currentDesc) {
  const modal = document.getElementById("editPestModal");
  modal.classList.add("show");
  modal.setAttribute("data-id", id);

  document.getElementById("editPestTitle").value = currentTitle;
  document.getElementById("editPestDesc").value = currentDesc;
}

function confirmEditPest() {
  const modal = document.getElementById("editPestModal");
  const id = modal.getAttribute("data-id");

  const newTitle = document.getElementById("editPestTitle").value;
  const newDesc = document.getElementById("editPestDesc").value;

  const formData = new FormData();
  formData.append("edit_id", id);
  formData.append("new_title", newTitle);
  formData.append("new_description", newDesc);

  fetch("admin-guides-pest.php", { method: "POST", body: formData })
    .then(res => res.json())
    .then(result => {
      showToast(result.message, result.status);
      if (result.status === "success") {
        loadPage("admin-guides-pest.php");
      }
    })
    .catch(err => {
      console.error("Edit error:", err);
      showToast("Error editing pest guide", "error");
    })
    .finally(() => {
      modal.classList.remove("show");
    });
}

// Expose globally so loadPage can call initPestJS after injection
window.initPestJS = initPestJS;
window.openDeletePestModal = openDeletePestModal;
window.confirmDeletePest = confirmDeletePest;
window.openEditPestModal = openEditPestModal;
window.confirmEditPest = confirmEditPest;