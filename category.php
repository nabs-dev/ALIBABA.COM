<?php
require 'db.php';
$categories = $conn->query("SELECT * FROM categories")->fetchAll();
$products = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $price_min = $_POST['price_min'] ?: 0;
    $price_max = $_POST['price_max'] ?: 999999;
    $moq = $_POST['moq'] ?: 1;

    $stmt = $conn->prepare("SELECT p.*, u.company_name FROM products p JOIN users u ON p.user_id = u.id WHERE p.category_id = ? AND p.price BETWEEN ? AND ? AND p.moq >= ?");
    $stmt->execute([$category_id, $price_min, $price_max, $moq]);
    $products = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; }
        .header { background: #ff6200; color: white; padding: 10px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .filter { background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .products { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .product { background: white; padding: 15px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); text-align: center; }
        .product img { max-width: 100%; height: auto; border-radius: 5px; }
        input, select { padding: 10px; margin: 5px; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px; background: #ff6200; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #e55a00; }
        @media (max-width: 768px) { .products { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Categories</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <div class="filter">
            <form method="POST">
                <select name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="price_min" placeholder="Min Price">
                <input type="number" name="price_max" placeholder="Max Price">
                <input type="number" name="moq" placeholder="Min MOQ">
                <button type="submit">Filter</button>
            </form>
        </div>
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
