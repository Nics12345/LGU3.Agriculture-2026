// guides-soil.js

function analyzeSoil() {
  const form = document.getElementById("soil-analysis-form");
  const resultDiv = document.getElementById("soil-analysis-result");

  const location = form.location.value.trim();
  const ph = parseFloat(form.ph_level.value);
  const nitrogen = parseFloat(form.nitrogen.value);
  const phosphorus = parseFloat(form.phosphorus.value);
  const potassium = parseFloat(form.potassium.value);
  const organicMatter = parseFloat(form.organic_matter.value);
  const moisture = parseFloat(form.moisture_content.value);

  let analysis = `<h4>Soil Analysis for ${location}</h4><ul>`;

  // pH evaluation (8 levels)
  if (ph < 4.0) analysis += "<li style='color:#e74c3c;'>Extremely acidic — toxic for most crops, urgent liming required.</li>";
  else if (ph < 4.5) analysis += "<li style='color:#e67e22;'>Very strongly acidic — nutrient uptake severely restricted.</li>";
  else if (ph < 5.0) analysis += "<li style='color:#f39c12;'>Strongly acidic — lime recommended for most crops.</li>";
  else if (ph >= 5.0 && ph < 5.5) analysis += "<li style='color:#f1c40f;'>Moderately acidic — tolerable for acid-loving crops.</li>";
  else if (ph >= 5.5 && ph < 6.5) analysis += "<li style='color:#1cc88a;'>Slightly acidic — excellent for cereals and vegetables.</li>";
  else if (ph >= 6.5 && ph <= 7.0) analysis += "<li style='color:#2ecc71;'>Neutral — ideal for most crops.</li>";
  else if (ph > 7.0 && ph <= 8.0) analysis += "<li style='color:#f39c12;'>Moderately alkaline — micronutrient deficiencies possible.</li>";
  else analysis += "<li style='color:#e74c3c;'>Highly alkaline — unsuitable for many crops, gypsum or acidifying amendments needed.</li>";

  // Nitrogen evaluation (8 levels)
  if (nitrogen < 5) analysis += "<li style='color:#e74c3c;'>Extremely low nitrogen — severe deficiency, apply fertilizer immediately.</li>";
  else if (nitrogen < 10) analysis += "<li style='color:#e67e22;'>Very low nitrogen — crop growth stunted, urgent supplementation needed.</li>";
  else if (nitrogen < 20) analysis += "<li style='color:#f39c12;'>Low nitrogen — growth limited, supplement with urea or ammonium sulfate.</li>";
  else if (nitrogen >= 20 && nitrogen < 30) analysis += "<li style='color:#1cc88a;'>Moderate nitrogen — adequate for some crops, monitor closely.</li>";
  else if (nitrogen >= 30 && nitrogen < 40) analysis += "<li style='color:#2ecc71;'>Balanced nitrogen — good fertility for leafy crops.</li>";
  else if (nitrogen >= 40 && nitrogen < 50) analysis += "<li style='color:#27ae60;'>High nitrogen — strong growth, but risk of leaching.</li>";
  else if (nitrogen >= 50 && nitrogen < 70) analysis += "<li style='color:#f39c12;'>Very high nitrogen — potential pollution, reduce inputs.</li>";
  else analysis += "<li style='color:#e74c3c;'>Excessive nitrogen — harmful to crops and environment, stop nitrogen inputs.</li>";

  // Phosphorus evaluation (7 levels)
  if (phosphorus < 5) analysis += "<li style='color:#e74c3c;'>Very low phosphorus — root development severely restricted.</li>";
  else if (phosphorus < 10) analysis += "<li style='color:#e67e22;'>Low phosphorus — limited flowering and fruiting.</li>";
  else if (phosphorus >= 10 && phosphorus < 20) analysis += "<li style='color:#f39c12;'>Moderate phosphorus — adequate for some crops.</li>";
  else if (phosphorus >= 20 && phosphorus < 30) analysis += "<li style='color:#1cc88a;'>Balanced phosphorus — good for most crops.</li>";
  else if (phosphorus >= 30 && phosphorus < 40) analysis += "<li style='color:#2ecc71;'>High phosphorus — strong root and flower development.</li>";
  else if (phosphorus >= 40 && phosphorus < 60) analysis += "<li style='color:#f39c12;'>Very high phosphorus — micronutrient lock‑up possible.</li>";
  else analysis += "<li style='color:#e74c3c;'>Excessive phosphorus — soil imbalance likely, avoid further P fertilization.</li>";

  // Potassium evaluation (8 levels)
  if (potassium < 40) analysis += "<li style='color:#e74c3c;'>Extremely low potassium — poor disease resistance, urgent supplementation needed.</li>";
  else if (potassium < 60) analysis += "<li style='color:#e67e22;'>Very low potassium — limited yield potential, apply muriate of potash.</li>";
  else if (potassium >= 60 && potassium < 80) analysis += "<li style='color:#f39c12;'>Low potassium — adequate but monitor for deficiencies.</li>";
  else if (potassium >= 80 && potassium < 100) analysis += "<li style='color:#1cc88a;'>Moderate potassium — sufficient for cereals and vegetables.</li>";
  else if (potassium >= 100 && potassium < 130) analysis += "<li style='color:#2ecc71;'>Balanced potassium — strong fertility.</li>";
  else if (potassium >= 130 && potassium < 160) analysis += "<li style='color:#27ae60;'>High potassium — good fertility, but watch magnesium uptake.</li>";
  else if (potassium >= 160 && potassium < 200) analysis += "<li style='color:#f39c12;'>Very high potassium — nutrient imbalance possible, reduce inputs.</li>";
  else analysis += "<li style='color:#e74c3c;'>Excessive potassium — soil imbalance, avoid further K fertilization.</li>";

  // Organic matter evaluation (7 levels)
  if (organicMatter < 1) analysis += "<li style='color:#e74c3c;'>Extremely low organic matter — soil structure poor, add compost urgently.</li>";
  else if (organicMatter < 2) analysis += "<li style='color:#e67e22;'>Low organic matter — fertility limited, incorporate residues.</li>";
  else if (organicMatter >= 2 && organicMatter < 3) analysis += "<li style='color:#f39c12;'>Slightly low organic matter — acceptable but could be improved.</li>";
  else if (organicMatter >= 3 && organicMatter < 4) analysis += "<li style='color:#1cc88a;'>Moderate organic matter — good for most crops.</li>";
  else if (organicMatter >= 4 && organicMatter < 5) analysis += "<li style='color:#2ecc71;'>High organic matter — excellent soil health.</li>";
  else if (organicMatter >= 5 && organicMatter < 8) analysis += "<li style='color:#27ae60;'>Very high organic matter — soil is rich, may retain excess moisture.</li>";
  else analysis += "<li style='color:#e74c3c;'>Excessive organic matter — risk of nutrient imbalance, monitor carefully.</li>";

  // Moisture evaluation (8 levels)
  if (moisture < 10) analysis += "<li style='color:#e74c3c;'>Extremely dry — crops will fail without irrigation.</li>";
  else if (moisture < 15) analysis += "<li style='color:#e67e22;'>Very low moisture — irrigation urgently needed.</li>";
  else if (moisture >= 15 && moisture < 20) analysis += "<li style='color:#f39c12;'>Low moisture — tolerable but risky.</li>";
  else if (moisture >= 20 && moisture < 25) analysis += "<li style='color:#1cc88a;'>Slightly low moisture — manageable with irrigation.</li>";
  else if (moisture >= 25 && moisture < 35) analysis += "<li style='color:#2ecc71;'>Moderate moisture — suitable for most crops.</li>";
  else if (moisture >= 35 && moisture < 45) analysis += "<li style='color:#27ae60;'>Good moisture — optimal for crop development.</li>";
  else if (moisture >= 45 && moisture < 60) analysis += "<li style='color:#f39c12;'>High moisture — risk of waterlogging, ensure drainage.</li>";
  else analysis += "<li style='color:#e74c3c;'>Excessive moisture — soil saturated, crop roots may rot.</li>";

  analysis += "</ul>";

    // Overall summary synthesis
  let summary = "<h4>Overall Summary</h4><p>";
  let issues = [];

  if (ph < 5.0 || ph > 8.0) issues.push("pH correction");
  if (nitrogen < 15 || nitrogen > 70) issues.push("nitrogen management");
  if (phosphorus < 15 || phosphorus > 60) issues.push("phosphorus adjustment");
  if (potassium < 70 || potassium > 200) issues.push("potassium balance");
  if (organicMatter < 2 || organicMatter > 8) issues.push("organic matter improvement");
  if (moisture < 20 || moisture > 60) issues.push("moisture control");

  if (issues.length === 0) {
    summary += "Your soil is well balanced and suitable for a wide range of crops. Maintain current practices and monitor regularly to sustain fertility.";
  } else {
    summary += "Your soil shows areas needing attention: " + issues.join(", ") + ". ";
    summary += "Addressing these will improve crop yield and sustainability. ";

    if (issues.includes("pH correction")) summary += "Consider liming or acidifying amendments depending on your pH imbalance. ";
    if (issues.includes("nitrogen management")) summary += "Adjust nitrogen inputs carefully to avoid deficiency or excess. ";
    if (issues.includes("phosphorus adjustment")) summary += "Balance phosphorus to support root and flower development without causing lock‑up. ";
    if (issues.includes("potassium balance")) summary += "Ensure potassium levels are stable to maintain disease resistance and nutrient uptake. ";
    if (issues.includes("organic matter improvement")) summary += "Incorporate compost or organic residues to stabilize soil structure. ";
    if (issues.includes("moisture control")) summary += "Improve irrigation or drainage systems to maintain optimal moisture. ";
  }

  summary += "</p>";

  resultDiv.innerHTML = analysis + summary;
}

// Expose globally
window.analyzeSoil = analyzeSoil;