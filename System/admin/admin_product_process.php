<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['upload'])) {

    $product_name  = trim($_POST['product_name'] ?? '');
    $category      = trim($_POST['category'] ?? '');
    $brand         = trim($_POST['brand'] ?? '');
    $pieces        = intval($_POST['pieces'] ?? 0);
    $price         = floatval($_POST['price'] ?? 0);
    $description   = trim($_POST['i_description'] ?? '');

    // File upload
    $photo_name = '';
    if (isset($_FILES['i_photo']) && $_FILES['i_photo']['error'] === UPLOAD_ERR_OK) {
        $photo_name = basename($_FILES['i_photo']['name']);
        $upload_path = '../photos/' . $photo_name;
    
        if (!is_dir('../photos')) {
            mkdir('../photos', 0755, true);
        }
    
        if (!move_uploaded_file($_FILES['i_photo']['tmp_name'], $upload_path)) {
            die("Failed to upload image.");
        }
    } else {
        die("Image upload error: " . $_FILES['i_photo']['error']);
    }

    // Prepare and execute the insert query
    $sql = "INSERT INTO product_design 
        (item_name, item_type, item_brand, item_qty, item_price, item_description, item_photo)
        VALUES ( ?, ?, ?, ?, ?, ?, ?)";
        
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssdss",  
        $product_name, 
        $category, 
        $brand, 
        $pieces, 
        $price, 
        $description, 
        $photo_name
    );

    if ($stmt->execute()) {
        header("Location: admin_products.php?success=1");
        exit();
    } else {
        echo "Insert failed: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid form submission.";
}
?>
