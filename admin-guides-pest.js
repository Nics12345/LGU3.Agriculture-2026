// Handle Pest Guide Upload
document.addEventListener("DOMContentLoaded", () => {
  const pestVideoForm = document.getElementById("add-pest-video-form");
  if (pestVideoForm) {
    pestVideoForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(pestVideoForm);

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
        console.error("Pest upload error:", err);
        showToast("Error adding pest guide", "error");
      }
    });
  }
});

// =======================
// Delete Pest Guide Modal
// =======================
function openDeletePestModal(id) {
  const modal = document.getElementById("deletePestModal");
  modal.classList.add("show");   // use CSS class toggle
  modal.setAttribute("data-id", id);
}

function confirmDeletePest() {
  const modal = document.getElementById("deletePestModal");
  const id = modal.getAttribute("data-id");

  const formData = new FormData();
  formData.append("delete_id", id);

  fetch("admin-guides-pest.php", {
    method: "POST",
    body: formData
  })
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
      modal.classList.remove("show"); // hide modal after action
    });
}

// =======================
// Edit Pest Guide Modal
// =======================
function openEditPestModal(id, currentTitle, currentDesc) {
  const modal = document.getElementById("editPestModal");
  modal.classList.add("show");   // use CSS class toggle
  modal.setAttribute("data-id", id);

  // Prefill the form fields inside the modal
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

  fetch("admin-guides-pest.php", {
    method: "POST",
    body: formData
  })
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
      modal.classList.remove("show"); // hide modal after action
    });
}


// Expose globally
window.openDeletePestModal = openDeletePestModal;
window.confirmDeletePest = confirmDeletePest;
window.openEditPestModal = openEditPestModal;
window.confirmEditPest = confirmEditPest;