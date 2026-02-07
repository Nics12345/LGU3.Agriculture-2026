<?php
session_start();
include 'db.php'; // Ensure this matches your DB connection file name
?>
<div class="content-wrapper">
    <div style="background: #4e73df; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h2 style="margin: 0;">🤖 Technical Assistant</h2>
        <p style="margin: 5px 0 0 0;">Browse expert solutions for common farming challenges.</p>
    </div>
    
    <div class="assistant-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php
        $res = $conn->query("SELECT * FROM tech_assistant ORDER BY category ASC");
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                echo "
                <div style='background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-top: 4px solid #1cc88a;'>
                    <span style='background: #f8f9fc; color: #4e73df; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;'>{$row['category']}</span>
                    <h3 style='margin: 15px 0 10px 0; color: #2e59d9;'>{$row['issue']}</h3>
                    <p style='color: #5a5c69; line-height: 1.5;'><b>Recommended Solution:</b><br>" . nl2br(htmlspecialchars($row['solution'])) . "</p>
                </div>";
            }
        } else {
            echo "<p style='grid-column: 1/-1; text-align: center; color: #858796;'>No technical guides are currently available.</p>";
        }
        ?>
    </div>
</div>