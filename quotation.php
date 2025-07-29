<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
if ($role == 'buyer') {
    $stmt = $conn->prepare("SELECT q.*, p.name FROM quotations q JOIN products p ON q.product_id = p.id WHERE q.buyer_id = ?");
    $stmt->execute([$user_id]);
} else {
    $stmt = $conn->prepare("SELECT q.*, p.name FROM quotations q JOIN products p ON q.product_id = p.id JOIN products pr ON q.product_id = pr.id WHERE pr.user_id = ?");
    $stmt->execute([$user_id]);
}
$quotations = $stmt->fetchAll();

if (isset($_GET['accept'])) {
    $id = $_GET['accept'];
    $stmt = $conn->prepare("UPDATE quotations SET status = 'accepted' WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location.href='quotation.php';</script>";
}
if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    $stmt = $conn->prepare("UPDATE quotations SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location.href='quotation.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quotations</title>
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
        <h1>Quotations</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Proposed Price</th>
                <th>Status</th>
                <?php if ($role == 'supplier'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
            <?php foreach ($quotations as $quotation): ?>
                <tr>
                    <td><?php echo $quotation['name']; ?></td>
                    <td><?php echo $quotation['quantity']; ?></td>
                    <td>$<?php echo $quotation['proposed_price']; ?></td>
                    <td><?php echo $quotation['status']; ?></td>
                    <?php if ($role == 'supplier'): ?>
                        <td>
                            <button onclick="window.location.href='quotation.php?accept=<?php echo $quotation['id']; ?>'">Accept</button>
                            <button onclick="window.location.href='quotation.php?reject=<?php echo $quotation['id']; ?>'">Reject</button>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
