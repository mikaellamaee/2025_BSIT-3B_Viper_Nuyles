<?php
include "../db.php";

if (isset($_POST['update'])) {
    $item_id = intval($_POST['product_id']);
    $item_name = trim($_POST['u_name']);
    $item_desc = trim($_POST['u_description']);
    $item_price = floatval($_POST['u_price']);
    $item_qty = intval($_POST['u_qty']);
    $item_cat = trim($_POST['u_category']);
    $item_brand = trim($_POST['u_brand']);

    $photo_sql = "";
    $params = [$item_name, $item_desc, $item_price, $item_qty, $item_cat, $item_brand];
    $types = "ssdiss";

    // Check for optional image upload
    if (!empty($_FILES['u_photo']['name']) && is_uploaded_file($_FILES["u_photo"]["tmp_name"])) {
        $photo_name = time() . "_" . uniqid() . "_" . basename($_FILES["u_photo"]["name"]);
        $photo_path = "../photos/" . $photo_name;

        if (move_uploaded_file($_FILES["u_photo"]["tmp_name"], $photo_path)) {
            $photo_sql = ", item_photo = ?";
            $params[] = $photo_name;
            $types .= "s";
        } else {
            header("Location: admin_products.php?error=upload_failed");
            exit;
        }
    }

    // Final SQL with conditional photo update
    $sql = "UPDATE product_design SET 
        item_name = ?, 
        item_description = ?, 
        item_price = ?, 
        item_qty = ?, 
        item_type = ?, 
        item_brand = ? $photo_sql 
        WHERE prdct_dsgn_id = ?";

    $params[] = $item_id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        header("Location: admin_products.php?updated=1");
    } else {
        header("Location: admin_products.php?error=db_error");
    }

    $stmt->close();
} else {
    header("Location: admin_products.php?error=invalid_form");
}
?>
