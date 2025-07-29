<?php
require 'db.php';
if (!isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
$product_id = $_GET['id'];
$stmt = $conn->prepare("SELECT p.*, u.company_name FROM products p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] == 'buyer') {
    $quantity = $_POST['quantity'];
    $total_price = $quantity * $product['price'];
    $stmt = $conn->prepare("INSERT INTO orders (buyer_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $product_id, $quantity, $total_price]);
    echo "<script>alert('Order placed successfully!'); window.location.href='orders.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; }
        .header { background: #ff6200; color: white; padding: 10px; text-align: center; }
        .container { max-width: 800px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        img { max-width: 100%; height: auto; border-radius: 5px; }
        input { padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px; background: #ff6200; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #e55a00; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Product Details</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <img src="<?php echo $product['image'] ?: 'placeholder.jpg'; ?>" alt="<?php echo $product['name']; ?>">
        <h2><?php echo $product['name']; ?></h2>
        <p><?php echo $product['description']; ?></p>
        <p>Price: $<?php echo $product['price']; ?> | MOQ: <?php echo $product['moq']; ?></p>
        <p>Supplier: <?php echo $product['company_name']; ?></p>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'buyer'): ?>
            <form method="POST">
                <input type="number" name="quantity" placeholder="Quantity" min="<?php echo $product['moq']; ?>" required>
                <button type="submit">Place Order</button>
            </form>
            <button onclick="window.location.href='request-quotation.php?product_id=<?php echo $product['id']; ?>'">Request Quotation</button>
        <?php endif; ?>
    </div>
</body>
</html>
