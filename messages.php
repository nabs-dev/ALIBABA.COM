<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];
$messages = $conn->prepare("SELECT m.*, u.username FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = ? OR m.sender_id = ?");
$messages->execute([$user_id, $user_id]);
$messages = $messages->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver_id = $_POST['receiver_id'];
    $product_id = $_POST['product_id'];
    $message = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $receiver_id, $product_id, $message]);
    echo "<script>window.location.href='messages.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; }
        .header { background: #ff6200; color: white; padding: 10px; text-align: center; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .message { background: white; padding: 15px; margin-bottom: 10px; border-radius: 10px; }
        .form { background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px; background: #ff6200; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #e55a00; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Messages</h1>
        <a href="dashboard.php" style="color: white;">Back to Dashboard</a>
    </div>
    <div class="container">
        <div class="form">
            <form method="POST">
                <input type="number" name="receiver_id" placeholder="Receiver ID" required>
                <input type="number" name="product_id" placeholder="Product ID (Optional)">
                <textarea name="message" placeholder="Message" required></textarea>
                <button type="submit">Send</button>
            </form>
        </div>
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <p><strong><?php echo $message['username']; ?>:</strong> <?php echo $message['message']; ?></p>
                <p>Product ID: <?php echo $message['product_id'] ?: 'N/A'; ?> | <?php echo $message['created_at']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
