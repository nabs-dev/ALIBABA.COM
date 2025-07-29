<?php
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'supplier') {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE user_id = ?");
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    echo "<script>window.location.href='manage-product.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
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
        <h1>Manage Products</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <table>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>MOQ</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td>$<?php echo $product['price']; ?></td>
                    <td><?php echo $product['moq']; ?></td>
                    <td>
                        <button onclick="window.location.href='add-product.php?edit=<?php echo $product['id']; ?>'">Edit</button>
                        <button onclick="window.location.href='manage-product.php?delete=<?php echo $product['id']; ?>'">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
