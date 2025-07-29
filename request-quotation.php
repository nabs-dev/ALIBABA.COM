<?php
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer' || !isset($_GET['product_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$product_id = $_GET['product_id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();
if (!$product) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = $_POST['quantity'];
    $proposed_price = $_POST['proposed_price'];
    $stmt = $conn->prepare("INSERT INTO quotations (buyer_id, product_id, quantity, proposed_price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $product_id, $quantity, $proposed_price]);
    echo "<script>window.location.href='quotation.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Quotation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(to right, #f4f4f4, #e0e0e0);
        }
        .header {
            background: #ff6200;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #ff6200;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #e55a00;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #ff6200;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Request Quotation</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <h2>Request Quotation for <?php echo htmlspecialchars($product['name']); ?></h2>
        <form method="POST">
            <input type="number" name="quantity" placeholder="Quantity (Min: <?php echo $product['moq']; ?>)" min="<?php echo $product['moq']; ?>" required>
            <input type="number" name="proposed_price" placeholder="Proposed Price ($)" step="0.01" required>
            <button type="submit">Submit Quotation</button>
        </form>
        <a href="product-details.php?id=<?php echo $product_id; ?>" class="back-link">Back to Product</a>
    </div>
</body>
</html>
