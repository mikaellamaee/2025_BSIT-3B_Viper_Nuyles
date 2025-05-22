<?php
session_start();

if (!isset($_SESSION['user_info_id']) || !isset($_POST['cart_id'])) {
    header('Location: cart.php');
    exit;
}

$cartId = $_POST['cart_id'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=origato_b2b;charset=utf8", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ? AND user_info_id = ?");
    $stmt->execute([$cartId, $_SESSION['user_info_id']]);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

header('Location: cart.php');
exit;
