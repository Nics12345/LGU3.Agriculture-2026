/* === LANDING PAGE SCRIPT === */

// --- Menu Toggle (Hamburger for mobile) ---
function toggleMenu() {
  const navList = document.querySelector("nav ul");
  navList.classList.toggle("show");
}

// --- Modal Manager ---
function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.add("show");
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.remove("show");
}

// Close modal when clicking outside
window.addEventListener("click", function(event) {
  document.querySelectorAll(".modal").forEach(modal => {
    if (event.target === modal) {
      modal.classList.remove("show");
    }
  });
});

// --- Toast Notifications ---
function showToast(message, isError = false) {
  const toast = document.getElementById("toast");
  if (!toast) return;

  toast.textContent = message;
  toast.className = "toast " + (isError ? "error" : "success");
  toast.classList.add("show");

  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
}

// --- Weather Forecast ---
async function loadWeather(city, targetId) {
  city = city.trim();
  if (city === "") {
    document.getElementById(targetId).innerHTML = "<p>Please enter a city name.</p>";
    return;
  }

  const apiKey = "4eb15006e0cd026d02dcb6d311d2b8c9";
  const url = `https://api.openweathermap.org/data/2.5/forecast?q=${encodeURIComponent(city)}&appid=${apiKey}&units=metric`;

  try {
    let response = await fetch(url);
    let data = await response.json();

    if (data.cod === "200") {
      let forecastHTML = `<h3>Forecast for ${data.city.name}</h3><div class="weather-cards">`;
      let daily = {};
      data.list.forEach(item => {
        let date = new Date(item.dt_txt).toLocaleDateString();
        if (!daily[date]) daily[date] = item;
      });

      for (let date in daily) {
        let item = daily[date];
        let dt = new Date(item.dt_txt);
        let formattedDate = dt.toLocaleDateString();
        let dayOfWeek = dt.toLocaleDateString("en-US", { weekday: "short" });

        let desc = item.weather[0].description.toLowerCase();
        let conditionClass = "cloudy";
        if (desc.includes("sun") || desc.includes("clear")) conditionClass = "sunny";
        else if (desc.includes("rain")) conditionClass = "rainy";
        else if (desc.includes("storm") || desc.includes("thunder")) conditionClass = "stormy";

        let icon = item.weather[0].icon;
        let iconUrl = `https://openweathermap.org/img/wn/${icon}@2x.png`;

        forecastHTML += `
          <div class="weather-card ${conditionClass}">
            <img src="${iconUrl}" alt="${item.weather[0].description}">
            <div><b>${dayOfWeek}, ${formattedDate}</b></div>
            <div>${item.weather[0].description}</div>
            <div>🌡 ${item.main.temp}°C</div>
            <div class="weather-details">
              💧 Humidity: ${item.main.humidity}% <br>
              🌬 Wind: ${item.wind.speed} m/s <br>
              🌡 Feels like: ${item.main.feels_like}°C
            </div>
          </div>`;
      }

      forecastHTML += "</div>";
      document.getElementById(targetId).innerHTML = forecastHTML;

      // Expand/collapse behavior
      document.querySelectorAll(".weather-card").forEach(card => {
        card.addEventListener("click", () => {
          document.querySelectorAll(".weather-card").forEach(c => {
            if (c !== card) c.classList.remove("expanded");
          });
          card.classList.toggle("expanded");
        });
      });
    } else {
      document.getElementById(targetId).innerHTML = "<p>City not found.</p>";
    }
  } catch (error) {
    document.getElementById(targetId).innerHTML = "<p>Error fetching forecast data.</p>";
  }
}

function getForecast() {
  const city = document.getElementById("city").value;
  loadWeather(city, "weather-result");
}

// --- Chatbot ---
async function sendMessage() {
  const input = document.getElementById("chatbot-text");
  const message = input.value.trim();
  if (!message) return;

  const messages = document.getElementById("chatbot-messages");

  // User bubble
  const userMsg = document.createElement("div");
  userMsg.className = "user";
  userMsg.textContent = message;
  messages.appendChild(userMsg);

  input.value = "";

  try {
    const response = await fetch("chatbot-landing.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ prompt: message })
    });

    const data = await response.json();

    // Bot bubble
    const botMsg = document.createElement("div");
    botMsg.className = "bot";
    botMsg.textContent = data.reply || data.error || "No response from AI.";
    messages.appendChild(botMsg);

    messages.scrollTop = messages.scrollHeight;
  } catch (err) {
    const errorMsg = document.createElement("div");
    errorMsg.className = "bot";
    errorMsg.textContent = "Error connecting to AI service.";
    messages.appendChild(errorMsg);
  }
}

// Allow pressing Enter to send message
const chatbotInput = document.getElementById("chatbot-text");
if (chatbotInput) {
  chatbotInput.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
      event.preventDefault();
      sendMessage();
    }
  });
}

// Chatbot toggle (minimize/expand)
const toggleBtn = document.getElementById("chatbot-toggle");
if (toggleBtn) {
  toggleBtn.addEventListener("click", function() {
    const chatbot = document.getElementById("chatbot");
    chatbot.classList.toggle("minimized");
    this.textContent = chatbot.classList.contains("minimized") ? "▲" : "–";
  });
}

// --- Auth Form Submissions ---
function submitSignup() {
  const fullname = document.getElementById("signup-name").value.trim();
  const email    = document.getElementById("signup-email").value.trim();
  const password = document.getElementById("signup-password").value.trim();
  const phone    = document.getElementById("signup-phone").value.trim();
  const address  = document.getElementById("signup-address").value.trim();

  let errors = [];

  if (!fullname) errors.push("Full name is required");
  if (!email) errors.push("Email is required");
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push("Invalid email format");

  if (!password) errors.push("Password is required");
  else if (password.length < 6) errors.push("Password must be at least 6 characters");

  if (!phone) errors.push("Phone number is required");
  else if (!/^\d+$/.test(phone)) errors.push("Phone number must be numeric");

  if (!address) errors.push("Address is required");

  if (errors.length > 0) {
    showToast(errors.join("\n"), true);
    return;
  }

  // Duplicate check before signup
  fetch("check-duplicate.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "email=" + encodeURIComponent(email) + "&phone=" + encodeURIComponent(phone)
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "error") {
      showToast(data.message, true);
    } else {
      // Proceed with signup
      const data = { fullname, email, password, phone, address };
      fetch("signup.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
        credentials: "include"
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === "success") {
          showToast("Signup successful!");
          closeModal("signupModal");
        } else {
          showToast(res.message, true);
        }
      })
      .catch(() => showToast("Error connecting to server", true));
    }
  })
  .catch(() => showToast("Error checking duplicates", true));
}

function submitLogin() {
  const data = {
    email: document.getElementById("login-email").value.trim(),
    password: document.getElementById("login-password").value.trim()
  };

  fetch("login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
    credentials: "include"
  })
  .then(res => res.json())
  .then(res => {
    if (res.status === "success" && res.redirect) {
      window.location.href = res.redirect;
    } else {
      showToast(res.message, true);
    }
  })
  .catch(() => showToast("Error connecting to server", true));
}

function submitAdminLogin() {
  const data = {
    email: document.getElementById("admin-email").value.trim(),
    password: document.getElementById("admin-password").value.trim()
  };

  fetch("admin-login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
    credentials: "include"
  })
  .then(res => res.json())
  .then(res => {
    if (res.status === "success" && res.redirect) {
      window.location.href = res.redirect;
    } else {
      showToast(res.message, true);
    }
  })
  .catch(() => showToast("Error connecting to server", true));
}

// --- AJAX Content Loader ---
function loadContent(page) {
  const container = document.getElementById("dynamic-content");
  if (!container) return;

  // Show loading state
  container.innerHTML = "<p class='fade-in'>Loading...</p>";

  fetch(page)
    .then(response => {
      if (!response.ok) throw new Error("Network error");
      return response.text();
    })
    .then(html => {
      container.innerHTML = html;
      container.classList.add("fade-in");
    })
    .catch(err => {
      container.innerHTML = "<p class='fade-in'>Error loading content.</p>";
      console.error(err);
    });
}

// Forgot Password
function submitForgotPassword() {
  const identifier = document.getElementById("forgot-identifier").value.trim();

  if (!identifier) {
    showToast("Please enter your email or phone number", true);
    return;
  }

  fetch("forgot-password.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "identifier=" + encodeURIComponent(identifier)
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      showToast(data.message);
      closeModal("forgotPasswordModal");
    } else {
      showToast(data.message, true);
    }
  })
  .catch(() => showToast("Error connecting to server", true));
}

// Expose globally so HTML onclick works
window.loadContent = loadContent;
window.toggleMenu = toggleMenu;
window.openModal = openModal;
window.closeModal = closeModal;
window.getForecast = getForecast;
window.sendMessage = sendMessage;
window.submitSignup = submitSignup;
window.submitLogin = submitLogin;
window.submitAdminLogin = submitAdminLogin;
window.submitForgotPassword = submitForgotPassword;