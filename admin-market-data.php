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

<link rel="stylesheet" href="admin-dashboard.css">

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
                <td colspan="2" class="form-submit-cell">
                    <button type="submit" class="btn btn-success">Add Product</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<div class="user-table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Unit</th>
                <th>Trend</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
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
                        <td class='status-{$row['status']}'>" . htmlspecialchars($row['status']) . "</td>
                        <td>" . date("M j, g:i a", strtotime($row['updated_at'])) . "</td>
                        <td>
                            <button onclick='deleteMarketItem({$row['id']})' class='btn btn-danger delete-btn'>Delete</button>
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

<script src="admin-dashboard.js"></script>