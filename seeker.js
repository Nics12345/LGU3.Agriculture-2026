const API_KEY = "VgwrpiY5vVDLsrJ28OVn";  
// ✅ Hosted API endpoint from Roboflow Universe
const MODEL_URL = "https://detect.roboflow.com/pest-detection-ntbss/3?api_key=" + API_KEY;

function initSeeker() {
  console.log("initSeeker called"); // Debugging

  const form = document.getElementById("seeker-form");
  const results = document.getElementById("seeker-results");
  const canvas = document.getElementById("seekerCanvas");
  const ctx = canvas.getContext("2d");
  const detailsDiv = document.getElementById("pest-details-content");

  if (!form) {
    console.error("Form not found!");
    return;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fileInput = document.getElementById("seekerImage");
    if (!fileInput.files.length) {
      results.innerHTML = "<p class='error'>❌ Mangyaring pumili muna ng larawan.</p>";
      return;
    }

    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append("file", file);

    results.innerHTML = "<p class='loading'>🔄 Sinusuri ang larawan...</p>";
    detailsDiv.innerHTML = "<p class='loading'>🔄 Kinukuha ang detalye ng peste...</p>";

    try {
      const response = await fetch(MODEL_URL, { method: "POST", body: formData });
      console.log("Response status:", response.status);

      const text = await response.text();
      console.log("Raw response:", text);

      let result;
      try {
        result = JSON.parse(text);
      } catch (e) {
        results.innerHTML = "<p class='error'>❌ Nabigo ang pag-parse ng tugon.</p>";
        console.error("JSON parse error:", e);
        return;
      }

      if (result.predictions && result.predictions.length > 0) {
        // Left panel: detection results
        let html = "<h3>✅ Natukoy na mga Peste:</h3><ul class='pest-list'>";
        result.predictions.forEach(pred => {
          const confidence = (pred.confidence * 100).toFixed(1);
          html += `<li><strong>${pred.class}</strong> — ${confidence}%</li>`;
        });
        html += "</ul>";
        results.innerHTML = html;

        // Draw bounding boxes
        const img = new Image();
        img.onload = () => {
          canvas.width = img.width;
          canvas.height = img.height;
          ctx.drawImage(img, 0, 0);

          result.predictions.forEach(pred => {
            const { x, y, width, height, class: pestClass, confidence } = pred;
            const confText = (confidence * 100).toFixed(1) + "%";

            ctx.strokeStyle = "#ff0000";
            ctx.lineWidth = 2;
            ctx.strokeRect(x - width / 2, y - height / 2, width, height);

            ctx.fillStyle = "rgba(255,0,0,0.7)";
            ctx.font = "16px Arial";
            ctx.fillText(`${pestClass} (${confText})`, x - width / 2, y - height / 2 - 5);
          });

          canvas.style.display = "block";
        };
        img.src = URL.createObjectURL(file);

        // Right panel: pest details via PHP proxy (cached in DB)
        detailsDiv.innerHTML = "";
        for (const pred of result.predictions) {
          const pestName = pred.class;
          const confidence = (pred.confidence * 100).toFixed(1);
          try {
            let details = await fetchPestDetails(pestName, confidence);

            // ✅ Fallback: auto-translate to Tagalog if response is mostly English
            if (/^[A-Za-z0-9\s.,;:!?'"-]+$/.test(details)) {
              details = await translateToTagalog(details);
            }

            // ✅ Convert **bold** markdown to <strong>
            const formattedDetails = details.replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>");

            detailsDiv.innerHTML += `
              <div class="pest-detail">
                <h4>${pestName} (${confidence}%)</h4>
                <p>${formattedDetails}</p>
              </div>
            `;
          } catch (err) {
            detailsDiv.innerHTML += `
              <div class="pest-detail">
                <h4>${pestName}</h4>
                <p class="error">❌ Nabigo ang pagkuha ng detalye.</p>
              </div>
            `;
            console.error("OpenAI/DB error:", err);
          }
        }
      } else {
        results.innerHTML = "<p class='no-pests'>🚫 Walang natukoy na peste sa larawang ito.</p>";
        detailsDiv.innerHTML = "<p class='no-pests'>🚫 Walang detalye ng peste na makukuha.</p>";
        canvas.style.display = "none";
      }
    } catch (err) {
      results.innerHTML = "<p class='error'>❌ Error sa pagsusuri ng larawan.</p>";
      detailsDiv.innerHTML = "<p class='error'>❌ Error sa pagkuha ng detalye ng peste.</p>";
      console.error(err);
    }
  });
}

// ✅ Secure proxy call — pest-details.php handles DB + OpenAI
async function fetchPestDetails(pestName, confidence) {
  const response = await fetch("pest-details.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({ pest: pestName, confidence: confidence })
  });

  const data = await response.json();
  if (data.error) {
    throw new Error(data.error);
  }
  return data.details;
}

// ✅ Fallback translator (calls your PHP proxy again or another endpoint)
async function translateToTagalog(text) {
  const response = await fetch("translate.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({ text: text, target: "tl" })
  });

  const data = await response.json();
  return data.translated || text;
}