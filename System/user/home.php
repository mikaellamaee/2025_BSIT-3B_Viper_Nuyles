<?php
session_start();
if (!isset($_SESSION['user_info_id'])) {
    header("Location: ../login.php");
    exit;
}

$host = 'localhost';
$dbname = 'origato_b2b';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}

// Fetch the logged-in user's details from database to display profile info
$user_id = $_SESSION['user_info_id'];

$stmt = $pdo->prepare("SELECT user_name, e_mail FROM user_info WHERE user_info_id = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // User not found, logout and redirect to login
    session_destroy();
    header('Location: ../login.php');
    exit;
}

// Use fetched user data for display
$username = $user['user_name'];
$useremail = $user['e_mail'];

// Define fetchProducts function (unchanged)
function fetchProducts($pdo, $limit = 4) {
    $stmt = $pdo->prepare("SELECT * FROM product_design LIMIT :limit");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch products (example)
$products = fetchProducts($pdo);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Homepage - ORIGATO</title>

  <!-- External CSS -->
  <link rel="stylesheet" href="../css/home.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
</head>
<body>

<style>
body {
  font-family: Arial, sans-serif;
  background-color: #4f6edb;
  margin: 0;
  padding: 0;
  color: #000;
}
</style>

<header>
  <div class="logo">
    <img src="../photos/logo.jpeg" alt="Origato Logo" />
    <span>ORIGATO</span>
  </div>
  <div class="search-bar">
        <div style="position: relative; display: flex; gap: 5px; width: 600px;">
            <input type="text" id="searchInput" placeholder="Search by name, category, or brand" class="form-control" />
            <button id="clearBtn" class="btn btn-secondary"><i class="bi bi-search"></i></button>
        </div>
    </div>
  <div class="header-icons">
  <a href="cart.php" class="btn" title="Cart" style="background-color: #d90479; color: white;">
    <i class="bi bi-cart"></i>
  </a>
  <div class="position-relative">
    <div class="user-icon" id="userIcon" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-controls="userDropdown">
      <i class="bi bi-person-circle"></i>
    </div>
    <div class="dropdown-menu" id="userDropdown" role="menu" aria-label="User menu">
      <a href="profile.php" class="dropdown-btn"><?= htmlspecialchars("Hello, $username") ?></a>
      <a href="../logout.php" class="dropdown-btn">Logout</a>
    </div>
  </div>
</div>

</header>



<div class="banner" id="banner">
  <div class="banner-content">
    <img src="../photos/banner1.png" alt="Banner 1" class="banner-img active">
    <img src="../photos/banner2.png" alt="Banner 2" class="banner-img">
    <img src="../photos/banner3.png" alt="Banner 3" class="banner-img">
  </div>
</div>


<div class="categories">
  <h3>CATEGORIES</h3>
  <div class="category-row">
    <button class="category-btn"><img src="../photos/crayons.png" name="crayon"><br>crayons</button>
    <button class="category-btn"><img src="../photos/paint.png" name="paint"><br>paint</button>
    <button class="category-btn"><img src="../photos/ruler.png" name="ruler"><br>ruler</button>
    <button class="category-btn"><img src="../photos/easel.png" name="easel"><br>easel</button>
    <button class="category-btn"><img src="../photos/eraser.png" name="eraser"><br>eraser</button>
    <button class="category-btn"><img src="../photos/brush.png" name="paint brush"><br>paint brush</button>
    <button class="category-btn"><img src="../photos/palette.png" name="palette"><br>palette</button>
    <button class="category-btn"><img src="../photos/pencil.png" name="colored pencil"><br>colored pencil</button>
    <button class="category-btn"><img src="../photos/notes.png" name="sticky notes"><br>sticky notes</button>
    <button class="category-btn"><img src="../photos/sharpener.png" name="sharpener"><br>sharpener</button>
    <button class="category-btn"><img src="../photos/stapler.png" name="stapler"><br>stapler</button>
    <button class="category-btn"><img src="../photos/scissors.png" name="scissors"><br>scissors</button>
  </div>
</div>

<div class="products">
  <?php
   $products = fetchProducts($pdo, 100); 
    if ($products) {
        foreach ($products as $product) {
            // Use htmlspecialchars for safe output
            $id = (int)$product['prdct_dsgn_id']; // assuming primary key column is 'item_id'
            $img = htmlspecialchars($product['item_photo']);
            $name = htmlspecialchars($product['item_name']);
            $price = number_format($product['item_price'], 2);
            $brand = htmlspecialchars($product['item_brand']);
            $desc = htmlspecialchars($product['item_description']);
            $cat = htmlspecialchars($product['item_type']);

            echo '
            <a href="product.php?id=' . $id . '" 
               class="product-box" 
               title="' . $name . '" 
               data-name="' . strtolower($name) . '"
               data-brand="' . strtolower($brand) . '"
               data-description="' . strtolower($desc) . '"
               data-category="' . strtolower($cat) . '"
               style="text-decoration:none; color:inherit;">
              <img src="../photos/' . $img . '" alt="' . $name . '" style="width:100%; height:140px; object-fit: cover;" />
              <div style="padding: 10px; color: white;">
                <h6 style="margin: 0 0 5px 0;">' . $name . '</h6>
                <p style="font-size: 0.9rem; margin: 0 0 5px 0;">₱' . $price . '</p>
                <small style="font-size: 0.8rem; opacity: 0.8;">' . $brand . '</small>
              </div>
            </a>';
            
        }
    } else {
        echo '<p style="color:white; text-align:center; width:100%;">No products found.</p>';
    }
  ?>
</div>



<script>
  const searchInput = document.getElementById('searchInput');
  const clearBtn = document.getElementById('clearBtn');
  const productBoxes = document.querySelectorAll('.product-box');
  const categoryButtons = document.querySelectorAll('.category-btn');

  let activeCategory = '';

  function filterProducts() {
    const query = searchInput.value.trim().toLowerCase();

    productBoxes.forEach(product => {
      const name = product.getAttribute('data-name');
      const brand = product.getAttribute('data-brand');
      const category = product.getAttribute('data-category');

      const matchesSearch = name.includes(query) || brand.includes(query) || category.includes(query);
      const matchesCategory = activeCategory === '' || category === activeCategory;

      product.style.display = matchesSearch && matchesCategory ? 'flex' : 'none';
    });
  }

  searchInput.addEventListener('input', filterProducts);

  clearBtn.addEventListener('click', () => {
    searchInput.value = '';
    activeCategory = '';
    filterProducts();
  });

  categoryButtons.forEach(button => {
    button.addEventListener('click', () => {
      const text = button.innerText.trim().toLowerCase();
      activeCategory = text;
      filterProducts();
    });
  });

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

  const banner = document.getElementById('banner');
  const images = banner.querySelectorAll('.banner-img');
  let currentIndex = 0;

  function showImage(index) {
    images.forEach((img, i) => {
      img.classList.toggle('active', i === index);
    });
  }

  function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    showImage(currentIndex);
  }

  function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    showImage(currentIndex);
  }

  // Handle banner arrow clicks using event delegation
  banner.addEventListener('click', (e) => {
    const bannerRect = banner.getBoundingClientRect();
    if (e.clientX < bannerRect.left + 50) {
      prevImage();
    } else if (e.clientX > bannerRect.right - 50) {
      nextImage();
    }
  });

  // Optional: Auto-rotate every 5 seconds
  setInterval(nextImage, 5000);
</script>
</body>
</html>
