// weather-notify.js
function initWeatherNotify() {
  navigator.geolocation.getCurrentPosition(success, error);
}

function success(position) {
  const lat = position.coords.latitude;
  const lon = position.coords.longitude;
  fetchWeather(lat, lon);
}

function error(err) {
  console.warn(`Geolocation error (${err.code}): ${err.message}`);
  document.getElementById("weather-forecast").innerHTML =
    "<p>Unable to get your location. Please allow location access.</p>";
}

async function fetchWeather(lat, lon) {
  const apiKey = "4eb15006e0cd026d02dcb6d311d2b8c9";
  const url = `https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`;

  try {
    const response = await fetch(url);
    const data = await response.json();
    displayWeather(data);       // show full forecast
    checkSevereWeather(data);   // auto-insert alerts if needed
  } catch (err) {
    console.error("Weather fetch error:", err);
    document.getElementById("weather-forecast").innerHTML =
      "<p>Error fetching weather data.</p>";
  }
}

function displayWeather(data) {
  const forecastDiv = document.getElementById("weather-forecast");
  forecastDiv.innerHTML = "";

  const grouped = {};
  data.list.forEach(item => {
    const dateObj = new Date(item.dt * 1000);
    const dayKey = dateObj.toISOString().split("T")[0];
    if (!grouped[dayKey]) grouped[dayKey] = [];
    grouped[dayKey].push(item);
  });

  Object.keys(grouped).forEach(dayKey => {
    const dayBlock = document.createElement("div");
    dayBlock.className = "forecast-day";

    grouped[dayKey].forEach(item => {
      const date = new Date(item.dt * 1000).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
      const temp = item.main.temp;
      const condition = item.weather[0].description;
      const main = item.weather[0].main.toLowerCase();

      const card = document.createElement("div");
      card.className = "forecast-card";

      // Color coding by condition
      if (main.includes("storm")) {
        card.classList.add("storm");
      } else if (main.includes("rain")) {
        card.classList.add("rain");
      } else if (main.includes("snow")) {
        card.classList.add("snow");
      } else if (main.includes("cloud")) {
        card.classList.add("cloudy");
      } else if (main.includes("clear")) {
        card.classList.add("sunny");
      }

      // Severe highlight
      if (main.includes("storm") || main.includes("rain") || main.includes("snow")) {
        card.classList.add("severe");
        card.innerHTML = `⚠ <strong>${date}</strong><br>🌡 ${temp}°C<br>☁ ${condition}`;
      } else {
        card.innerHTML = `<strong>${date}</strong><br>🌡 ${temp}°C<br>☁ ${condition}`;
      }

      dayBlock.appendChild(card);
    });

    const dayLabel = document.createElement("h3");
    dayLabel.textContent = new Date(dayKey).toDateString();

    forecastDiv.appendChild(dayLabel);
    forecastDiv.appendChild(dayBlock);
  });
}

function checkSevereWeather(data) {
  data.list.forEach(item => {
    const condition = item.weather[0].main.toLowerCase();
    const description = item.weather[0].description;
    const date = new Date(item.dt * 1000).toLocaleString();

    if (condition.includes("storm") || condition.includes("rain") || condition.includes("snow")) {
      const formData = new FormData();
      formData.append("title", "⚠ Severe Weather Alert");
      formData.append("description", `Expected ${description} on ${date}`);

      fetch("weather-notify.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.json())
        .then(result => {
          console.log("Notification inserted:", result);
        })
        .catch(err => console.error("Notify insert error:", err));
    }
  });
}

// Expose init function globally
window.initWeatherNotify = initWeatherNotify;