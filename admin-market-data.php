<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo "<p style='color:red;'>Access denied.</p>";
    exit;
}
include 'db.php';

// --- 1. HANDLE DELETE ACTION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM market_data WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Product removed from market"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete"]);
    }
    exit;
}

// --- 2. HANDLE ADD ACTION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'])) {
    $name = trim($_POST['product_name']);
    $price = floatval($_POST['price']);
    $unit = trim($_POST['unit']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO market_data (product_name, price, unit, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $unit, $status);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Market price updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }
    exit;
}
?>

<div class="admin-form-container">
    <h2>📈 Product Market Data</h2>
    <p>Update the current market rates for crops and supplies.</p>

    <form id="add-market-form" method="POST">
        <table class="form-table">
            <tr>
                <th>Product Name:</th>
                <td><input type="text" name="product_name" placeholder="e.g. Rice (Well-milled)" required></td>
            </tr>
            <tr>
                <th>Price (PHP):</th>
                <td><input type="number" step="0.01" name="price" required></td>
            </tr>
            <tr>
                <th>Unit:</th>
                <td><input type="text" name="unit" placeholder="e.g. per kg" required></td>
            </tr>
            <tr>
                <th>Trend:</th>
                <td>
                    <select name="status">
                        <option value="Stable">Stable</option>
                        <option value="Increasing">Increasing ↑</option>
                        <option value="Decreasing">Decreasing ↓</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <button type="submit" class="btn" style="background:#1cc88a;">Add Product</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<div class="user-table-wrapper" style="margin-top:20px;">
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Unit</th>
                <th>Trend</th>
                <th>Last Updated</th>
                <th>Action</th> </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM market_data ORDER BY updated_at DESC");
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $statusColor = $row['status'] == 'Increasing' ? 'red' : ($row['status'] == 'Decreasing' ? 'green' : 'gray');
                    echo "<tr>
                        <td><b>" . htmlspecialchars($row['product_name']) . "</b></td>
                        <td>₱" . number_format($row['price'], 2) . "</td>
                        <td>{$row['unit']}</td>
                        <td style='color:{$statusColor}; font-weight:bold;'>{$row['status']}</td>
                        <td>" . date("M j, g:i a", strtotime($row['updated_at'])) . "</td>
                        <td>
                            <button onclick='deleteMarketItem({$row['id']})' class='btn btn-danger' style='padding: 5px 10px; font-size: 12px;'>Delete</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No data available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
// Function to handle deletion via AJAX
async function deleteMarketItem(id) {
    if (confirm("Are you sure you want to remove this product?")) {
        const formData = new FormData();
        formData.append('delete_id', id);

        try {
            const response = await fetch('admin-market-data.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (typeof showToast === "function") {
                showToast(result.message, result.status);
            } else {
                alert(result.message);
            }

            if (result.status === 'success') {
                // Refresh the current page view
                loadPage('admin-market-data.php');
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }
}
</script>