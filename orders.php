<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
if ($role == 'buyer') {
    $stmt = $conn->prepare("SELECT o.*, p.name FROM orders o JOIN products p ON o.product_id = p.id WHERE o.buyer_id = ?");
    $stmt->execute([$user_id]);
} else {
    $stmt = $conn->prepare("SELECT o.*, p.name FROM orders o JOIN products p ON o.product_id = p.id JOIN products pr ON o.product_id = pr.id WHERE pr.user_id = ?");
    $stmt->execute([$user_id]);
}
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; }
        .header { background: #ff6200; color: white; padding: 10px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #ff6200; color: white; }
        button { padding: 5px 10px; background: #ff6200; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #e55a00; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Orders</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['name']; ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td>$<?php echo $order['total_price']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
