<?php
session_start();
include 'db.php';
?>
<div class="content-wrapper">
    <div style="background: #1cc88a; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h2 style="margin: 0;">📈 Market Price Index</h2>
        <p style="margin: 5px 0 0 0;">Real-time price monitoring for local agricultural products.</p>
    </div>

    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fc; color: #4e73df; border-bottom: 2px solid #e3e6f0;">
                    <th style="padding: 15px;">Product Name</th>
                    <th style="padding: 15px;">Current Price</th>
                    <th style="padding: 15px;">Unit</th>
                    <th style="padding: 15px;">Trend</th>
                    <th style="padding: 15px;">Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $conn->query("SELECT * FROM market_data ORDER BY product_name ASC");
                if ($res && $res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        // Logic for trend colors
                        $trendColor = "#858796"; // Default gray
                        $icon = "•";
                        if ($row['status'] == 'Increasing') { $trendColor = "#e74c3c"; $icon = "↑"; }
                        if ($row['status'] == 'Decreasing') { $trendColor = "#1cc88a"; $icon = "↓"; }

                        echo "
                        <tr style='border-bottom: 1px solid #e3e6f0;'>
                            <td style='padding: 15px; font-weight: bold; color: #3a3b45;'>{$row['product_name']}</td>
                            <td style='padding: 15px; color: #1cc88a; font-weight: bold;'>₱" . number_format($row['price'], 2) . "</td>
                            <td style='padding: 15px; color: #858796;'>{$row['unit']}</td>
                            <td style='padding: 15px; color: {$trendColor}; font-weight: bold;'>{$icon} {$row['status']}</td>
                            <td style='padding: 15px; font-size: 13px; color: #b7b9cc;'>" . date("M d, Y", strtotime($row['updated_at'])) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='padding: 20px; text-align: center; color: #858796;'>No market data available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>