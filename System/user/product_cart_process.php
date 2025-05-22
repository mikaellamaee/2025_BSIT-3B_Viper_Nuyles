<?php
require 'conn.php';
session_start();

// Assuming the user is logged in and their ID is stored in session
$user_info_id = $_SESSION['user_info_id'] ?? null;

if (!$user_info_id) {
    die("User not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prdct_dsgn_id = $_POST['prdct_dsgn_id'] ?? null;
    $item_qty = $_POST['item_qty'] ?? 1;

    if (!$prdct_dsgn_id) {
        die("Product ID is missing.");
    }

    // Optional: Sanitize/validate values
    $item_qty = intval($item_qty);
    $date_added = date('Y-m-d H:i:s');

    $sql = "INSERT INTO cart (user_info_id, prdct_dsgn_id, item_qty, date_added)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $user_info_id, $prdct_dsgn_id, $item_qty, $date_added);

    if ($stmt->execute()) {
        header("Location: cart.php");
        exit;
    } else {
        echo "Error inserting into cart: " . $stmt->error;
    }
}
?>
