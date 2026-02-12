<?php
// guides-soil.php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>Access denied.</p>";
    exit;
}
?>

<link rel="stylesheet" href="guides-soil.css">

<div class="guides-container">
  <h2>🌱 Soil Analysis</h2>

  <!-- Soil Analysis Chatbox -->
  <div class="soil-analysis">
    <h3>🧪 Soil Analysis Dashboard</h3>
    <form id="soil-analysis-form">
      <input type="text" name="location" placeholder="Location" required>
      <input type="number" step="0.1" name="ph_level" placeholder="pH Level" required>
      <input type="number" step="0.1" name="nitrogen" placeholder="Nitrogen (mg/kg)" required>
      <input type="number" step="0.1" name="phosphorus" placeholder="Phosphorus (mg/kg)" required>
      <input type="number" step="0.1" name="potassium" placeholder="Potassium (mg/kg)" required>
      <input type="number" step="0.1" name="organic_matter" placeholder="Organic Matter (%)" required>
      <input type="number" step="0.1" name="moisture_content" placeholder="Moisture Content (%)" required>

      <button type="button" class="btn btn-success" onclick="analyzeSoil()">Analyze Soil</button>
    </form>
    <div id="soil-analysis-result"></div>
  </div>
</div>

<script src="guides-soil.js"></script>