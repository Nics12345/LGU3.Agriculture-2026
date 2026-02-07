// Toggle sidebar visibility
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("show");
}

// Toggle profile dropdown menu
function toggleProfile() {
  const menu = document.getElementById("profile-menu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Logout function
function logout() {
  window.location.href = "logout.php";
}

// Load section content dynamically with persistence
function loadSection(page) {
  const container = document.getElementById("dashboard-content");
  container.innerHTML = "<p>Loading...</p>";

  fetch(page)
    .then(response => {
      if (!response.ok) throw new Error("Network error");
      return response.text();
    })
    .then(html => {
      container.innerHTML = html;
      // Save the last loaded section in localStorage
      localStorage.setItem("lastSection", page);
    })
    .catch(err => {
      container.innerHTML = "<p>Error loading content.</p>";
      console.error(err);
    });
}

// Restore last section on page load
window.addEventListener("DOMContentLoaded", () => {
  const lastSection = localStorage.getItem("lastSection");
  if (lastSection) {
    loadSection(lastSection);
  }
});

// Close profile menu when clicking outside
window.addEventListener("click", function(event) {
  const menu = document.getElementById("profile-menu");
  const dropdown = document.querySelector(".profile-dropdown");
  if (menu && dropdown && !dropdown.contains(event.target)) {
    menu.style.display = "none";
  }
});

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
    } else {
      document.getElementById(targetId).innerHTML = "<p>City not found.</p>";
    }
  } catch (error) {
    document.getElementById(targetId).innerHTML = "<p>Error fetching forecast data.</p>";
  }
}


// Add these lines inside your loadPage .then(html => { ... }) block
const assistantForm = document.getElementById("tech-assistant-form");
if (assistantForm) bindAssistantForm(assistantForm);

const marketForm = document.getElementById("market-data-form");
if (marketForm) bindMarketForm(marketForm);

function getForecast() {
  const city = document.getElementById("city").value;
  loadWeather(city, "weather-result");
}

function toggleCategory(card) {
  document.querySelectorAll('.category-card').forEach(c => {
    if (c !== card) c.classList.remove('active');
  });
  card.classList.toggle('active');
}

// Load section content dynamically with persistence + active link
function loadSection(page) {
  const container = document.getElementById("dashboard-content");
  container.innerHTML = "<p>Loading...</p>";

  fetch(page)
    .then(response => {
      if (!response.ok) throw new Error("Network error");
      return response.text();
    })
    .then(html => {
      container.innerHTML = html;
      // Save the last loaded section in localStorage
      localStorage.setItem("lastSection", page);

      // Highlight the active link
      document.querySelectorAll(".sidebar-link").forEach(link => {
        link.classList.remove("active");
        if (link.getAttribute("onclick").includes(page)) {
          link.classList.add("active");
        }
      });
    })
    .catch(err => {
      container.innerHTML = "<p>Error loading content.</p>";
      console.error(err);
    });
}

// Restore last section on page load
window.addEventListener("DOMContentLoaded", () => {
  const lastSection = localStorage.getItem("lastSection");
  if (lastSection) {
    loadSection(lastSection);
  }
});

// Expose globally
window.toggleCategory = toggleCategory;
window.toggleSidebar = toggleSidebar;
window.toggleProfile = toggleProfile;
window.logout = logout;
window.loadSection = loadSection;
window.getForecast = getForecast;
window.loadWeather = loadWeather;