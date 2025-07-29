<?php
require 'db.php';
$stmt = $conn->query("SELECT p.*, u.company_name FROM products p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 6");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>B2B Marketplace</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; }
        .header { background: #ff6200; color: white; padding: 10px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .products { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .product { background: white; padding: 15px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); text-align: center; }
        .product img { max-width: 100%; height: auto; border-radius: 5px; }
        button { padding: 10px; background: #ff6200; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #e55a00; }
        @media (max-width: 768px) { .products { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>B2B Marketplace</h1>
        <a href="login.php" style="color: white;">Login</a> | <a href="signup.php" style="color: white;">Signup</a>
    </div>
    <div class="container">
        <h2>Trending Products</h2>
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?php echo $product['image'] ?: 'placeholder.jpg'; ?>" alt="<?php echo $product['name']; ?>">
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Price: $<?php echo $product['price']; ?> | MOQ: <?php echo $product['moq']; ?></p>
                    <p>Supplier: <?php echo $product['company_name']; ?></p>
                    <button onclick="window.location.href='product-details.php?id=<?php echo $product['id']; ?>'">View Details</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
