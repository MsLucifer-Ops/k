<?php
// C:\xampp\htdocs\Barcode\views\product.php

// 1) Load auth guard and DB
include_once __DIR__ . '/../auth/validate.php';
include_once __DIR__ . '/../config/db.php';

$error        = '';
$success      = '';
$barcode      = '';
$product_name = '';
$category_id  = '';
$description  = '';
$stock        = '';
$purchase     = '';
$sale         = '';
$image_path   = '';

// Handle create
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barcode      = trim($_POST['barcode']);
    $product_name = trim($_POST['product_name']);
    $category_id  = (int)$_POST['category_id'];
    $description  = trim($_POST['description']);
    $stock        = (int)$_POST['stock'];
    $purchase     = trim($_POST['purchase_price']);
    $sale         = trim($_POST['sale_price']);

    // Image upload
    if (!empty($_FILES['product_image']['name']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $tmp  = $_FILES['product_image']['tmp_name'];
        $ext  = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $dest = 'uploads/' . uniqid('prod_') . '.' . $ext;
        if (move_uploaded_file($tmp, __DIR__ . '/../' . $dest)) {
            $image_path = $dest;
        }
    }

    // Validation
    if ($barcode === '' || $product_name === '') {
        $error = 'Barcode and Product Name are required.';
    } else {
        $stmt = $conn->prepare("
            INSERT INTO Tbl_Product
              (Barcode,Product_name,Category_id,Description,Stock,Purchase_price,Sale_price,Image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'ssisddds',
            $barcode,
            $product_name,
            $category_id,
            $description,
            $stock,
            $purchase,
            $sale,
            $image_path
        );
        if ($stmt->execute()) {
            $success = 'Product added successfully.';
            // Clear inputs
            $barcode = $product_name = $category_id = '';
            $description = $stock = $purchase = $sale = $image_path = '';
        } else {
            $error = 'Insert failed: ' . $conn->error;
        }
    }
}

// Fetch categories
$catRes = $conn->query("SELECT Category_id,Category_name FROM Tbl_Category ORDER BY Category_name");
$cats   = $catRes->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Add Product';
include __DIR__ . '/templates/header.php';
?>

<div class="card mt-3 shadow-sm">
   <div class="card-header bg-white d-flex align-items-center">
    <a href="product.php" class="btn btn-sm btn-secondary me-3">
      &larr; Back
    </a>
    <h1 class="h5 mb-0"><?= htmlentities($pageTitle) ?></h1>
  </div>
  <div class="card-body px-3 py-4">
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="product.php" enctype="multipart/form-data">
      <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-12 col-lg-6">
          <div class="mb-3">
            <label for="barcode" class="form-label">Barcode</label>
            <input
              type="text"
              id="barcode"
              name="barcode"
              class="form-control"
              placeholder="Enter Barcode"
              required
              value="<?= htmlspecialchars($barcode) ?>">
          </div>

          <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input
              type="text"
              id="product_name"
              name="product_name"
              class="form-control"
              placeholder="Enter Name"
              required
              value="<?= htmlspecialchars($product_name) ?>">
          </div>

          <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select id="category_id" name="category_id" class="form-select" required>
              <option value="">Select Category</option>
              <?php foreach ($cats as $c): ?>
                <option
                  value="<?= $c['Category_id'] ?>"
                  <?= $c['Category_id'] == $category_id ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['Category_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea
              id="description"
              name="description"
              class="form-control"
              placeholder="Enter Description"
              rows="4"><?= htmlspecialchars($description) ?></textarea>
          </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-12 col-lg-6">
          <div class="mb-3">
            <label for="stock" class="form-label">Stock Quantity</label>
            <input
              type="number"
              id="stock"
              name="stock"
              class="form-control"
              placeholder="Enter Stock"
              value="<?= htmlspecialchars($stock) ?>">
          </div>

          <div class="mb-3">
            <label for="purchase_price" class="form-label">Purchase Price</label>
            <input
              type="text"
              id="purchase_price"
              name="purchase_price"
              class="form-control"
              placeholder="Enter Purchase Price"
              value="<?= htmlspecialchars($purchase) ?>">
          </div>

          <div class="mb-3">
            <label for="sale_price" class="form-label">Sale Price</label>
            <input
              type="text"
              id="sale_price"
              name="sale_price"
              class="form-control"
              placeholder="Enter Sale Price"
              value="<?= htmlspecialchars($sale) ?>">
          </div>

          <div class="mb-3">
            <label for="product_image" class="form-label">Product image</label>
            <input
              type="file"
              id="product_image"
              name="product_image"
              class="form-control">
            <small class="form-text text-muted">Upload image</small>
          </div>
        </div>
      </div>

      <div class="d-flex mt-3">
        <button type="submit" class="btn btn-primary">Save Product</button>
        
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>
