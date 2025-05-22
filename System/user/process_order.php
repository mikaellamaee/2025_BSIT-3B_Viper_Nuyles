<?php
include '../db.php';
session_start();

$user_id = $_POST['user_info_id'] ?? null;
if (!$user_id) {
    die("User not found.");
}

// Sanitize input
$name     = mysqli_real_escape_string($conn, $_POST['uname']);
$email    = mysqli_real_escape_string($conn, $_POST['email']);
$address  = mysqli_real_escape_string($conn, $_POST['address']);
$contact  = mysqli_real_escape_string($conn, $_POST['contact']);
$order_ref = mysqli_real_escape_string($conn, $_POST['order_ref']);
$date_now = date('Y-m-d H:i:s');

// Update user info
$update_query = "
    UPDATE user_info SET 
        user_name = '$name',
        e_mail = '$email',
        add_ress = '$address',
        contact_no = '$contact',
        date_updated = '$date_now'
    WHERE user_info_id = $user_id
";

if (!mysqli_query($conn, $update_query)) {
    die("❌ Error updating user info: " . mysqli_error($conn));
}

if (!isset($_POST['user_info_id'])) {
    echo "Invalid access.";
    exit;
}

$user_id = $_POST['user_info_id'];
$order_ref = mysqli_real_escape_string($conn, $_POST['order_ref']);
$date_now = date('Y-m-d H:i:s');

// Fetch cart items
$cart_query = "
    SELECT c.cart_id, c.item_qty, pd.prdct_dsgn_id
    FROM cart c
    JOIN product_design pd ON c.prdct_dsgn_id = pd.prdct_dsgn_id
    WHERE c.user_info_id = $user_id
";
$cart_result = mysqli_query($conn, $cart_query);

if (!$cart_result || mysqli_num_rows($cart_result) == 0) {
    echo "Your cart is empty.";
    exit;
}

// Insert each item into the orders table
while ($row = mysqli_fetch_assoc($cart_result)) {
    $prdct_dsgn_id = $row['prdct_dsgn_id'];
    $item_qty = $row['item_qty'];

    $insert_order = "
        INSERT INTO orders (order_ref, user_info_id, prdct_dsgn_id, order_qty, order_phase, date_added, date_updated)
        VALUES ('$order_ref', $user_id, $prdct_dsgn_id, $item_qty, 'Pending', '$date_now', '$date_now')
    ";
    mysqli_query($conn, $insert_order);
}

// Optionally clear the cart
$clear_cart = "DELETE FROM cart WHERE user_info_id = $user_id";
mysqli_query($conn, $clear_cart);

// Redirect or confirmation
header("Location: home.php?ref=$order_ref");
exit;
?>
