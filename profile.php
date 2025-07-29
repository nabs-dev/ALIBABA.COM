<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $company_name = $_POST['company_name'];
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, company_name = ? WHERE id = ?");
    $stmt->execute([$username, $email, $company_name, $user_id]);
    echo "<script>window.location.href='profile.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; }
        .header { background: #ff6200; color: white; padding: 10px; text-align: center; }
        .container { max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px; background: #ff6200; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #e55a00; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Profile</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <form method="POST">
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <input type="text" name="company_name" value="<?php echo $user['company_name']; ?>" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
