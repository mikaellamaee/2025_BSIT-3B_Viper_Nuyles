<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
</head>
<body>
    <?php
    include '../db.php';

    $searchString = $conn->real_escape_string($_GET['q']);
    $search_query = $conn->real_escape_string($_GET['q']); // Sanitize input

    if(isset($_GET['items']))
    {
        $query = "SELECT * FROM product_design zz
        WHERE item_name LIKE '%$searchString%' 
        OR item_description LIKE '%$searchString%' 
        OR item_brand LIKE '%$searchString%' 
        OR item_type LIKE '%$searchString%' 
        OR item_color LIKE '%$searchString%'";

$item_result = $conn->query($query);

if ($item_result->num_rows > 0) {
  echo "<a href ='admin_items.php'>Go Back</a>";
  echo "<h2>Inventory Search Results: " .$searchString . "</h2>";
  echo "<table class='table'>";
  echo "<tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Color</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Sizes</th>
            <th>Actions</th>
        </tr>";

  while ($row = $item_result->fetch_assoc()) {
      // Fetch sizes
      $strsize = '';
      $prodctid = $row['prdct_dsgn_id'];
      $sql_get_sizes = "SELECT size_name FROM product 
                        INNER JOIN product_sizes 
                        ON product.sizes_id = product_sizes.sizes_id 
                        WHERE product.prdct_dsgn_id = $prodctid";
      $sizeresult = $conn->query($sql_get_sizes);
      while ($sizerow = $sizeresult->fetch_assoc()) {
          $strsize .= $sizerow['size_name'] . "<br>";
      }

      // Display product information
      echo "<tr>
                <td><img src='../product_pictures/{$row['item_photo']}' alt='' class='img-fluid' width='100px'></td>
                <td>{$row['item_name']}</td>
                <td>{$row['item_description']}</td>
                <td>Php " . number_format($row['item_price'], 2) . "</td>
                <td>{$row['item_qty']}</td>
                <td>{$row['item_color']}</td>
                <td>{$row['item_type']}</td>
                <td>{$row['item_brand']}</td>
                <td>$strsize</td>
                <td>
                    <a href='admin_items.php?update_item={$row['prdct_dsgn_id']}' class='btn btn-success'>Update</a>
                    <a href='admin_items.php?delete_item={$row['prdct_dsgn_id']}' class='btn btn-danger'>Delete</a>
                </td>
            </tr>";
  }
  echo "</table>";
} else {
  echo "<p>No results found</p>";
}
    }

    if(isset($_GET['orders']))
    {
            
// Prepare SQL statement
$sql = "SELECT product_design.*
FROM product_design
INNER JOIN orders ON orders.prdct_dsgn_id = product_design.prdct_dsgn_id
WHERE orders.order_ref LIKE '%$search_query%'";
// Execute SQL query
$order_result = $conn->query($sql);

if ($order_result->num_rows > 0) {
    echo "<a href ='admin_items.php'>Go Back</a>";
    echo "<h2>Ordered Items Search Results: " . $search_query . "</h2>";
    echo "<table class='table'>";
    echo "<tr>
              <th>Photo</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Color</th>
              <th>Category</th>
              <th>Brand</th>
              <th>Sizes</th>
              <th>Actions</th>
          </tr>";

    while ($row = $order_result->fetch_assoc()) {
        // Fetch sizes
        $strsize = '';
        $prodctid = $row['prdct_dsgn_id'];
        $sql_get_sizes = "SELECT size_name FROM product 
                          INNER JOIN product_sizes 
                          ON product.sizes_id = product_sizes.sizes_id 
                          WHERE product.prdct_dsgn_id = $prodctid";
        $sizeresult = $conn->query($sql_get_sizes);
        while ($sizerow = $sizeresult->fetch_assoc()) {
            $strsize .= $sizerow['size_name'] . "<br>";
        }

        // Display product information
        echo "<tr>
                  <td><img src='../product_pictures/{$row['item_photo']}' alt='' class='img-fluid' width='100px'></td>
                  <td>{$row['item_name']}</td>
                  <td>{$row['item_description']}</td>
                  <td>Php " . number_format($row['item_price'], 2) . "</td>
                  <td>{$row['item_qty']}</td>
                  <td>{$row['item_color']}</td>
                  <td>{$row['item_type']}</td>
                  <td>{$row['item_brand']}</td>
                  <td>$strsize</td>
                  <td>
                      <a href='admin_items.php?update_item={$row['prdct_dsgn_id']}' class='btn btn-success'>Update</a>
                      <a href='admin_items.php?delete_item={$row['prdct_dsgn_id']}' class='btn btn-danger'>Delete</a>
                  </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No results found";
}
    }
   
    ?>
</body>
</html>
