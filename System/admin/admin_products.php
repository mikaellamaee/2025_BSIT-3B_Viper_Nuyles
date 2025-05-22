<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_info_id'])) {
    die("Access denied. Please log in.");
}

$user_id = $_SESSION['user_info_id'];

// Fetch admin profile
$stmt = $conn->prepare("SELECT * FROM `user_info` WHERE user_info_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$fetch_profile = $result->fetch_assoc();
$stmt->close();

// Delete product
if (isset($_GET['delete_item'])) {
    $item_id = intval($_GET['delete_item']);

    $stmt = $conn->prepare("DELETE FROM `product` WHERE `prdct_dsgn_id` = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM `product_design` WHERE `prdct_dsgn_id` = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_products.php?deleted=1");
    exit;
}

// Get product to update if requested
$update_data = null;
if (isset($_GET['update_item'])) {
    $item_id = intval($_GET['update_item']);
    $stmt = $conn->prepare("SELECT * FROM product_design WHERE prdct_dsgn_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $update_data = $result->fetch_assoc();
    $stmt->close();
}

// Get all products
$products = [];
$result = $conn->query("SELECT * FROM product_design");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Products - ORIGATO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_products.css">
</head>
<body>

<style>
    header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1050;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .main {
        margin-top: 100px;
    }
</style>

<header>
    <div class="logo">
        <img src="../photos/logo.jpeg" alt="Logo">
        <span>ORIGATO</span>
    </div>

    <!-- SEARCH BAR -->
    <div class="search-bar">
        <div style="position: relative; display: flex; gap: 5px; width: 600px;">
            <input type="text" id="searchInput" placeholder="Search by name, category, or brand" class="form-control" />
            <button id="clearBtn" class="btn btn-secondary"><i class="bi bi-search"></i></button>
        </div>
    </div>

    <div class="header-icons">
        <div class="user-info" title="Admin">
            <div class="user-icon"><i class="bi bi-person-circle"></i></div>
            <div><b><?= htmlspecialchars($fetch_profile['user_name']) ?></b><br><small>(Admin)</small></div>
        </div>
    </div>
</header>

<div class="sidebar">
    <div class="logo">ORIGATO</div>
    <a href="admin_products.php" class="active"><i class="bi bi-basket"></i> Products</a>
    <a href="admin_orders.php"><i class="bi bi-truck"></i> Orders</a>
    <a href="admin_homepage.php"><i class="bi bi-clipboard-data"></i> Reports</a>
    <a href="logout.php"><i class="bi bi-box-arrow-left"></i> Log Out</a>
</div>

<div class="main" style="margin-left: 300px; width: 70%;">
    <h1 class="mt-4">Manage Products</h1>

    <!-- Feedback Alerts -->
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">Product updated successfully.</div>
    <?php elseif (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger">Product deleted.</div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-warning">An error occurred.</div>
    <?php endif; ?>

    <!-- ADD PRODUCT FORM -->
    <div class="card mb-4">
        <div class="card-header">Add Product</div>
        <div class="card-body">
            <form action="admin_product_process.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                        <label>ID Code</label>
                        <input type="text" name="product_id" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Name</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Quantity</label>
                        <input type="number" name="pieces" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Price (₱)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="i_description" class="form-control" rows="2" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Photo</label>
                    <input type="file" name="i_photo" class="form-control" accept="image/*" required>
                </div>

                <button type="submit" name="upload" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>

    <!-- UPDATE PRODUCT MODAL -->
    <?php if ($update_data): ?>
    <div class="modal show fade" id="updateModal" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.6);" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h5 class="modal-title">Update Product</h5>
            <a href="admin_products.php" class="btn-close"></a>
          </div>
          <form action="admin_product_update.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="product_id" value="<?= $update_data['prdct_dsgn_id'] ?>">

              <div class="row mb-3">
                <div class="col">
                  <label>Name</label>
                  <input type="text" name="u_name" class="form-control" value="<?= htmlspecialchars($update_data['item_name']) ?>" required>
                </div>
                <div class="col">
                  <label>Category</label>
                  <input type="text" name="u_category" class="form-control" value="<?= htmlspecialchars($update_data['item_type']) ?>" required>
                </div>
                <div class="col">
                  <label>Brand</label>
                  <input type="text" name="u_brand" class="form-control" value="<?= htmlspecialchars($update_data['item_brand']) ?>" required>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col">
                  <label>Quantity</label>
                  <input type="number" name="u_qty" class="form-control" value="<?= htmlspecialchars($update_data['item_qty']) ?>" required>
                </div>
                <div class="col">
                  <label>Price (₱)</label>
                  <input type="number" step="0.01" name="u_price" class="form-control" value="<?= htmlspecialchars($update_data['item_price']) ?>" required>
                </div>
                <div class="col">
                  <label>New Photo (optional)</label>
                  <input type="file" name="u_photo" class="form-control" accept="image/*">
                </div>
              </div>

              <div class="mb-3">
                <label>Description</label>
                <textarea name="u_description" class="form-control" rows="2" required><?= htmlspecialchars($update_data['item_description']) ?></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="update" class="btn btn-warning">Update Product</button>
              <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- PRODUCT TABLE -->
    <div class="card">
        <div class="card-header">Product List</div>
        <div class="card-body">
            <?php if (count($products) > 0): ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><img src="../photos/<?= htmlspecialchars($product['item_photo']) ?>" alt="Photo" width="50"></td>
                                <td><?= htmlspecialchars($product['prdct_dsgn_id']) ?></td>
                                <td><?= htmlspecialchars($product['item_name']) ?></td>
                                <td><?= htmlspecialchars($product['item_type']) ?></td>
                                <td><?= htmlspecialchars($product['item_brand']) ?></td>
                                <td><?= htmlspecialchars($product['item_qty']) ?></td>
                                <td>₱<?= number_format($product['item_price'], 2) ?></td>
                                <td><?= htmlspecialchars($product['item_description']) ?></td>
                                <td>
                                    <a href="admin_products.php?update_item=<?= $product['prdct_dsgn_id'] ?>" class="btn btn-sm btn-success">Update</a>
                                    <a href="admin_products.php?delete_item=<?= $product['prdct_dsgn_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No products available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
function filterTable() {
    const query = document.getElementById('searchInput').value.trim().toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        const name = row.children[2].textContent.toLowerCase();
        const category = row.children[3].textContent.toLowerCase();
        const brand = row.children[4].textContent.toLowerCase();

        const match = name.includes(query) || category.includes(query) || brand.includes(query);
        row.style.display = match ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('input', filterTable);

document.getElementById('clearBtn').addEventListener('click', function () {
    document.getElementById('searchInput').value = '';
    filterTable();
});

// Lock background scroll if update modal is open
window.onload = () => {
    const modal = document.getElementById("updateModal");
    if (modal) {
        document.body.style.overflow = "hidden";
        modal.scrollIntoView({ behavior: "smooth" });
    }
};
</script>

</body>
</html>
