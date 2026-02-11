<?php
session_start();
?>

<div class="seeker-container">
  <h2>🔍 Pest Detection (Roboflow)</h2>
  
<div class="seeker-layout">
  <div class="seeker-analysis">
    <!-- left side: detection -->
    <form id="seeker-form" class="seeker-form">
      <label for="seekerImage">Upload Crop Image:</label>
      <input type="file" id="seekerImage" accept="image/*" class="form-input">
      <button type="submit" class="btn btn-primary">Analyze</button>
    </form>
    <div id="seeker-results" class="seeker-results"></div>
    <canvas id="seekerCanvas" class="seeker-canvas"></canvas>
  </div>

  <div class="seeker-details">
    <h3>📘 Pest Information</h3>
    <div id="pest-details-content">
      <p>Details will appear here after analysis.</p>
    </div>
  </div>
</div>

<link rel="stylesheet" href="seeker.css">
<script src="seeker.js"></script>