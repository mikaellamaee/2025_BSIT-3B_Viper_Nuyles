<?php
session_start();

if (!isset($_SESSION['user_info_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_info_id'];

$host = 'localhost';
$dbname = 'origato_b2b';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$user_id = $_SESSION['user_info_id'];

// Fetch admin profile
$stmt = $pdo->prepare("SELECT * FROM `user_info` WHERE user_info_id = ?");
$stmt->execute([$userId]);
$fetch_profile = $stmt->fetch(PDO::FETCH_ASSOC);


$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$product = [
    'name' => 'Product Not Found',
    'price' => 0,
    'images' => [],
    'set_options' => ['50PCS', '100PCS', '150PCS', '200PCS'],
    'description' => 'No description available.'
];

if ($productId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM product_design WHERE prdct_dsgn_id = ?");
    $stmt->execute([$productId]);
    $productData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($productData) {
        $product['name'] = $productData['item_name'];
        $product['price'] = $productData['item_price'];
        $product['description'] = $productData['item_description'] ?? 'No description available.';
        $product['images'] = [$productData['item_photo'] ?? ''];
        $product['mainImage'] = !empty($productData['item_photo']) ? '../photos/' . $productData['item_photo'] : '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $item_name = $_POST['item_name'] ?? '';
    $item_price = floatval($_POST['item_price'] ?? 0);
    $selected_set = $_POST['selected_set'] ?? '50';
    $quantity = intval($selected_set);
    
    $total_price = $item_price * $quantity;

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO cart (user_info_id, prdct_dsgn_id, item_qty, item_price, date_added) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$userId, $productId, $quantity, $total_price])) {
            header("Location: cart.php");
            exit;
        } else {
            $error = $stmt->errorInfo();
            echo "Insert Error: " . $error[2];
        }
    } elseif ($action === 'buy') {
        $_SESSION['checkout'] = [
            'prdct_dsgn_id' => $productId,
            'qty' => $quantity,
            'price' => $total_price
        ];
        header("Location: checkout.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Product Details - ORIGATO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body { margin: 0; font-family: 'Arial', sans-serif; background-color: #4f6edb; color: #000; }
    header { background: white; padding: 10px 30px; display: flex; justify-content: space-between; align-items: center; }
    .logo { display: flex; align-items: center; gap: 10px; font-weight: bold; font-size: 24px; color: #1c1c1c; }
    .logo img { height: 40px; }
    .header-icons { display: flex; align-items: center; gap: 15px; }
    .header-icons button { background-color: #d90479; color: white; border: none; padding: 5px 15px; border-radius: 5px; cursor: pointer; }
    .search-bar { display: flex; justify-content: center; margin: 20px 0; }
    .search-bar input { padding: 10px; width: 400px; border-radius: 5px 0 0 5px; border: none; font-size: 16px; }
    .search-bar button { background-color: #d90479; color: white; border: none; padding: 10px 15px; border-radius: 0 5px 5px 0; cursor: pointer; font-size: 1.2rem; }
    .product-detail { background-color: white; border-radius: 40px; margin: 20px 60px; padding: 30px; display: flex; gap: 40px; }
    .product-images { flex: 1; max-width: 300px; }
    .main-image {
  background-color: #4f6edb;
  border-radius: 10px;
  width: 100%;
  aspect-ratio: 1 / 1;
  margin-bottom: 15px;
  background-size: cover;
  background-position: center;
}

.thumbnail {
  flex: 1;
  background-color: #6f81d4;
  border-radius: 3px;
  aspect-ratio: 1 / 1;
  opacity: 0.8;
  background-size: cover;
  background-position: center;
  cursor: pointer;
}

    .thumbnail-container { margin-top: 15px; display: flex; gap: 10px;}
    .product-info { flex: 1.5; }
    .product-info h2 { font-size: 28px; margin-bottom: 10px; }
    .product-info .price { font-size: 30px; color: #d90479; background-color: #fdd6e4; padding: 10px; display: inline-block; margin: 10px 0; }
    .price-per-piece { font-size: 14px; color: #333; }
    .ratings { font-size: 14px; color: #444; }
    .set-options button {
      border: 1px solid #999;
      background: white;
      margin: 5px 3px;
      padding: 5px 15px;
      cursor: pointer;
    }
    .set-options button.selected {
      background-color: #d90479;
      color: white;
    }
    .action-buttons button {
      margin: 10px 10px 0 0;
      padding: 10px 20px;
      border-radius: 10px;
      border: 2px solid #d90479;
      font-weight: bold;
    }
    .add-to-cart {
      background-color: white;
      color: #d90479;
    }
    .buy-now {
      background-color: #d90479;
      color: white;
      border: none;
    }
    .user-icon {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      background-color: #e0e0e0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">
    <img src="../photos/logo.jpeg" alt="Origato Logo" />
    <span>ORIGATO</span>
  </div>
  <div class="header-icons">
    <?php if (isset($_SESSION['username'])): ?>
      <div class="user-icon" title="Logged in as <?php $user_id ?>">
        <i class="bi bi-person-circle"></i>
      </div>
    <?php endif; ?>
    <div class="header-icons">
  <a href="cart.php" class="btn" title="Cart" style="background-color: #d90479; color: white;">
    <i class="bi bi-cart"></i>
  </a>
  <div class="position-relative">
    <div class="user-icon" id="userIcon" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-controls="userDropdown">
      <i class="bi bi-person-circle"></i>
    </div>
    <div class="dropdown-menu" id="userDropdown" role="menu" aria-label="User menu">
    <a href="profile.php" class="dropdown-btn">Hello, <?php $user_id ?></a>
      <a href="../logout.php" class="dropdown-btn">Logout</a>
    </div>
  </div>
  </div>
</header>

<div class="product-detail">
  <div class="product-images">
    <div class="main-image" id="mainImage" style="background-image: url('<?= htmlspecialchars($product['mainImage'] ?? '') ?>');"></div>
  </div>

  <div class="product-info">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <div class="price">₱ <?= number_format($product['price'], 2) ?></div>
    <div class="price-per-piece">₱ <?= number_format($product['price'], 2) ?> per piece</div>
    <div class="ratings">★★★★★ (1.5K Reviews)</div>
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

    <form method="POST" id="productForm">
      <input type="hidden" name="item_name" value="<?= htmlspecialchars($product['name']) ?>" />
      <input type="hidden" name="item_price" value="<?= htmlspecialchars($product['price']) ?>" />
      <input type="hidden" name="selected_set" id="selectedSet" value="50" />

      <div class="set-options">
        <?php foreach ([50,100,150,200] as $pcs): ?>
          <button type="button" class="set-option-btn" data-value="<?= $pcs ?>"><?= $pcs ?>PCS</button>
        <?php endforeach; ?>
      </div>

      <div class="action-buttons">
        <button type="submit" name="action" value="add" class="add-to-cart">Add to Cart</button>
        <button type="submit" name="action" value="buy" class="buy-now">Buy Now</button>
      </div>
    </form>
  </div>
</div>

<script>
  function setMainImage(elem) {
    document.getElementById('mainImage').style.backgroundImage = elem.style.backgroundImage;
  }

  const optionButtons = document.querySelectorAll('.set-option-btn');
  const selectedSetInput = document.getElementById('selectedSet');
  const priceDiv = document.querySelector('.price');
  const pricePerPieceDiv = document.querySelector('.price-per-piece');
  const basePrice = <?= json_encode($product['price']) ?>;

  optionButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      optionButtons.forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
      selectedSetInput.value = btn.getAttribute('data-value');
      const qty = parseInt(selectedSetInput.value);
      priceDiv.textContent = '₱ ' + (basePrice * qty).toFixed(2);
      pricePerPieceDiv.textContent = '₱ ' + basePrice.toFixed(2) + ' per piece';
    });
  });

  // Set default 8PCS selected
  document.querySelector('.set-option-btn[data-value="8"]').classList.add('selected');

    // User dropdown toggle
    const userIcon = document.getElementById('userIcon');
  const userDropdown = document.getElementById('userDropdown');

  userIcon.addEventListener('click', () => {
    userDropdown.classList.toggle('show');
    const expanded = userIcon.getAttribute('aria-expanded') === 'true';
    userIcon.setAttribute('aria-expanded', !expanded);
  });

  window.addEventListener('click', (e) => {
    if (!userIcon.contains(e.target) && !userDropdown.contains(e.target)) {
      userDropdown.classList.remove('show');
      userIcon.setAttribute('aria-expanded', false);
    }
  });

  window.addEventListener('keydown', (e) => {
    if (e.key === "Escape") {
      userDropdown.classList.remove('show');
      userIcon.setAttribute('aria-expanded', false);
    }
  });
</script>

</body>
</html>
