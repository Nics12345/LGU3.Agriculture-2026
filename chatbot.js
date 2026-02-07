// --- Chatbot Dashboard JS ---

function newChat() {
  fetch("new-chat.php", {
    method: "POST",
    credentials: "include"
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      const chatList = document.getElementById("chat-list");
      const li = document.createElement("li");
      li.classList.add("active");
      li.innerHTML = `
        <a href="chatbot-dashboard.php?conv_id=${data.id}">${data.title}</a>
        <div class="chat-options">
          <button class="chat-options-btn">⋮</button>
          <div class="chat-options-menu">
            <button class="rename-chat" data-id="${data.id}">Rename</button>
            <button class="pin-chat" data-id="${data.id}">Pin</button>
            <button class="delete-chat" data-id="${data.id}">Delete</button>
          </div>
        </div>
      `;
      chatList.prepend(li);

      window.location.href = `chatbot-dashboard.php?conv_id=${data.id}`;
    } else {
      alert("Error creating chat: " + data.message);
    }
  })
  .catch(err => {
    console.error("New chat error:", err);
    alert("Error connecting to server");
  });
}

// Toggle profile dropdown
function toggleProfile() {
  const menu = document.getElementById("profile-menu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Logout
function logout() {
  window.location.href = "logout.php";
}

document.addEventListener("DOMContentLoaded", () => {
  const chatForm = document.getElementById("chat-form");
  const chatMessages = document.getElementById("chat-messages");
  const chatMain = document.querySelector(".chat-main");
  const searchInput = document.getElementById("chat-search");
  const chatList = document.getElementById("chat-list");

  let currentConvId = null;
  let currentLi = null;

  // Handle sending a message
  if (chatForm) {
    chatForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      chatMain.classList.add("has-messages");

      const formData = new FormData(chatForm);

      const userMsg = document.createElement("div");
      userMsg.className = "message user-message";
      userMsg.textContent = formData.get("prompt");
      chatMessages.appendChild(userMsg);

      const imageFile = formData.get("image");
      if (imageFile && imageFile.size > 0) {
        const imgPreview = document.createElement("img");
        imgPreview.src = URL.createObjectURL(imageFile);
        imgPreview.className = "user-image";
        chatMessages.appendChild(imgPreview);
      }

      const botTyping = document.createElement("div");
      botTyping.className = "message bot-message typing";
      botTyping.textContent = "Bot is thinking...";
      chatMessages.appendChild(botTyping);

      try {
  const response = await fetch("chatbot-process.php", {
    method: "POST",
    body: formData
  });
  const data = await response.json();

  chatMessages.removeChild(botTyping);

  const botMsg = document.createElement("div");
  botMsg.className = "message bot-message";
  botMsg.textContent = data.reply || data.error || "No response.";
  chatMessages.appendChild(botMsg);

  // 🔥 Update sidebar title live
  if (data.title) {
    const sidebarLink = document.querySelector(`#chat-list li a[href*="conv_id=${data.conv_id}"]`);
    if (sidebarLink) {
      sidebarLink.textContent = data.title;
    }
  }

  chatMessages.scrollTop = chatMessages.scrollHeight;
} catch (err) {
  botTyping.textContent = "Error connecting to AI service.";
}

      chatForm.reset();
    });
  }

  // Search chats
  if (searchInput && chatList) {
    searchInput.addEventListener("input", () => {
      const filter = searchInput.value.toLowerCase();
      const items = chatList.getElementsByTagName("li");
      for (let i = 0; i < items.length; i++) {
        const link = items[i].getElementsByTagName("a")[0];
        const text = link.textContent || link.innerText;
        items[i].style.display = text.toLowerCase().includes(filter) ? "" : "none";
      }
    });
  }

  // Three-dot menu toggle
  document.querySelectorAll(".chat-options-btn").forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      const menu = btn.nextElementSibling;
      menu.classList.toggle("show");
    });
  });

  document.addEventListener("click", () => {
    document.querySelectorAll(".chat-options-menu").forEach(menu => menu.classList.remove("show"));
  });

  // Rename modal
  document.querySelectorAll(".rename-chat").forEach(btn => {
    btn.addEventListener("click", () => {
      currentConvId = btn.dataset.id;
      currentLi = btn.closest("li");
      document.getElementById("rename-modal").style.display = "flex";
    });
  });

  document.getElementById("rename-confirm").addEventListener("click", () => {
    const newName = document.getElementById("rename-input").value.trim();
    if (newName && currentLi) {
      currentLi.querySelector("a").textContent = newName;
      // TODO: AJAX call to backend to save rename
    }
    document.getElementById("rename-modal").style.display = "none";
    document.getElementById("rename-input").value = "";
  });

  document.getElementById("rename-cancel").addEventListener("click", () => {
    document.getElementById("rename-modal").style.display = "none";
  });

  // Pin / Unpin
  document.querySelectorAll(".pin-chat").forEach(btn => {
    btn.addEventListener("click", () => {
      currentConvId = btn.dataset.id;
      currentLi = btn.closest("li");

      if (chatList && currentLi) {
        const badge = currentLi.querySelector(".pinned-badge");

        if (badge) {
          badge.remove();
          chatList.appendChild(currentLi);
        } else {
          const newBadge = document.createElement("span");
          newBadge.className = "pinned-badge";
          newBadge.textContent = "📌";
          currentLi.querySelector("a").prepend(newBadge);
          chatList.insertBefore(currentLi, chatList.firstChild);
        }
      }
    });
  });

  // Delete (open modal)
  document.querySelectorAll(".delete-chat").forEach(btn => {
    btn.addEventListener("click", () => {
      currentConvId = btn.dataset.id;
      currentLi = btn.closest("li");
      document.getElementById("delete-modal").style.display = "flex";
    });
  });

  // Delete modal actions
  document.getElementById("delete-confirm").addEventListener("click", async () => {
    if (currentConvId && currentLi) {
      const response = await fetch("delete-conversation.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "conv_id=" + encodeURIComponent(currentConvId)
      });
      const data = await response.json();
      if (data.status === "success") {
        currentLi.remove();

        // Reset chat area if deleted conversation is open
        const openConvId = new URLSearchParams(window.location.search).get("conv_id");
        if (openConvId == currentConvId) {
          chatMessages.innerHTML = `
            <div id="chat-splash" class="chat-splash">
              <img src="LOGO.jpg" alt="LGU3 Logo" class="splash-logo">
              <h1 class="splash-title">LGU3</h1>
              <p>Select or start a chat.</p>
            </div>
          `;
          history.replaceState(null, "", "chatbot-dashboard.php");
        }
      } else {
        alert("Failed to delete conversation.");
      }
    }
    document.getElementById("delete-modal").style.display = "none";
  });

  document.getElementById("delete-cancel").addEventListener("click", () => {
    document.getElementById("delete-modal").style.display = "none";
  });
});

// File preview
const fileUpload = document.getElementById("file-upload");
const preview = document.getElementById("file-preview");

if (fileUpload) {
  fileUpload.addEventListener("change", function() {
    preview.innerHTML = "";
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        if (file.type.startsWith("image/")) {
          preview.innerHTML = `<img src="${e.target.result}" class="user-message preview-img">`;
        } else {
          preview.innerHTML = `<p class="user-message">📎 ${file.name}</p>`;
        }
      };
      reader.readAsDataURL(file);
    }
  });
}

// Expose globally
window.newChat = newChat;
window.toggleProfile = toggleProfile;
window.logout = logout;