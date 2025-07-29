<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(to right, #f4f4f4, #e0e0e0);
        }
        .header {
            background: #ff6200;
            color: white;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .header a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .nav-bar {
            background: #333;
            padding: 10px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .nav-bar a {
            display: inline-block;
            color: white;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            background: #ff6200;
            border-radius: 5px;
            transition: background 0.3s, transform 0.2s;
        }
        .nav-bar a:hover {
            background: #e55a00;
            transform: scale(1.05);
        }
        .content {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        p {
            color: #666;
            font-size: 16px;
        }
        @media (max-width: 768px) {
            .nav-bar a {
                display: block;
                margin: 10px auto;
                width: 80%;
            }
            .content {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard</h1>
        <a href="logout.php">Logout</a>
    </div>
    <div class="nav-bar">
        <a href="index.php">Index</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <?php if ($role == 'supplier'): ?>
            <a href="add-product.php">Add Product</a>
            <a href="manage-product.php">Manage Products</a>
        <?php endif; ?>
        <a href="category.php">Categories</a>
        <a href="messages.php">Messages</a>
        <a href="orders.php">Orders</a>
        <a href="quotation.php">Quotations</a>
    </div>
    <div class="content">
        <h2>Welcome, <?php echo $role; ?>!</h2>
        <p>Use the navigation bar above to manage your products, orders, messages, and more.</p>
    </div>
</body>
</html>
