<?php
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'supplier') {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$categories = $conn->query("SELECT * FROM categories")->fetchAll();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $moq = $_POST['moq'];
    $image = $_FILES['image']['name'];
    $target = "Uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO products (user_id, category_id, name, description, price, moq, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $category_id, $name, $description, $price, $moq, $image]);
    echo "<script>window.location.href='manage-product.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f4f4;
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
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        input, select, textarea {
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
        .error {
            color: red;
            text-align: center;
            margin: 10px 0;
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
        <h1>Add Product</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required>
            <?php if (empty($categories)): ?>
                <p class="error">No categories available. Please <a href="manage-category.php">add categories</a> first.</p>
            <?php else: ?>
                <select name="category_id" required>
                    <option value="" disabled selected>Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="number" name="moq" placeholder="Minimum Order Quantity" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" <?php echo empty($categories) ? 'disabled' : ''; ?>>Add Product</button>
        </form>
        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
