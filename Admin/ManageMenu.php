<?php
require_once('auth.php');
require_once('../Database/database.php');
require_once('sidebar.php');

$con = new Database();
$getCategory = $con->viewCategory();
$getProducts = $con->getAllProducts();

$uploadDir = __DIR__ . '/../uploads/products/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

function findProductImageRel($id){
    $base = __DIR__ . '/../uploads/products/product_'.intval($id).'.*';
    $matches = glob($base);
    if(!$matches) return '';
    $file = $matches[0];
    $rel = str_replace(__DIR__ . '/../', '../', $file);
    return $rel;
}

function validateAndResizeImage($tmpPath, $destPath, $maxWidth = 800, $maxHeight = 800){
    $info = @getimagesize($tmpPath);
    if(!$info) return false;
    $mime = $info['mime'];
    switch($mime){
        case 'image/jpeg': $img = @imagecreatefromjpeg($tmpPath); break;
        case 'image/png': $img = @imagecreatefrompng($tmpPath); break;
        case 'image/gif': $img = @imagecreatefromgif($tmpPath); break;
        default: return false;
    }
    if(!$img) return false;
    $width = imagesx($img);
    $height = imagesy($img);
    $scale = min(1, $maxWidth / $width, $maxHeight / $height);
    $newW = (int)($width * $scale);
    $newH = (int)($height * $scale);
    $newImg = imagecreatetruecolor($newW, $newH);
    // preserve transparency for PNG/GIF
    if($mime === 'image/png' || $mime === 'image/gif'){
        imagecolortransparent($newImg, imagecolorallocatealpha($newImg, 0, 0, 0, 127));
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
    }
    imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newW, $newH, $width, $height);
    $ok = false;
    if($mime === 'image/jpeg') $ok = imagejpeg($newImg, $destPath, 85);
    if($mime === 'image/png') $ok = imagepng($newImg, $destPath);
    if($mime === 'image/gif') $ok = imagegif($newImg, $destPath);
    imagedestroy($img);
    imagedestroy($newImg);
    return $ok;
}

if (isset($_POST["AddCat"])) {
    $InputCategory = trim($_POST["categoryInput"] ?? '');
    if ($InputCategory !== '') {
        $AddCategory = $con->AddCategory($InputCategory);
        $_SESSION['success_message'] = 'Category "' . htmlspecialchars($InputCategory) . '" added.';
        header('Location: ManageMenu.php');
        exit();
    }
}

if (isset($_POST['deleteCategory'])) {
    $categoryID = $_POST["delete_id"] ?? '';
    $categoryName = $_POST["delete_name"] ?? '';
    try {
        $deleteCategory = $con->deleteCategory($categoryID);
        $_SESSION['success_message'] = htmlspecialchars($categoryName) . ' has been deleted';
        header('Location: ManageMenu.php');
        exit();
    } catch (Exception $e) {
        $error_message = 'Unable to delete category. It may be in use.';
    }
}

// Handle Edit Category
if (isset($_POST['editCategory'])){
    $cid = $_POST['edit_category_id'] ?? '';
    $cname = trim($_POST['edit_category_name'] ?? '');
    if($cid && $cname){
        try{
            $con->UpdateCategory($cid, $cname);
            $_SESSION['success_message'] = 'Category updated.';
            header('Location: ManageMenu.php'); exit();
        } catch(Exception $e){
            $error_message = 'Unable to update category.';
        }
    }
}

// Handle Add Product submission
if (!empty($_POST['productName']) && isset($_POST['price'], $_POST['stocks'], $_POST['categoryId'])) {
    $productName = trim($_POST['productName']);
    $price = floatval($_POST['price']);
    $stocks = intval($_POST['stocks']);
    $categoryId = $_POST['categoryId'];
    $status = 'Available';
    if ($productName !== '' && $categoryId !== '') {
        try {
            $newId = $con->AddProduct($productName, $price, $stocks, $status, $categoryId);

            // handle uploaded image
            if (!empty($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['productImage']['tmp_name'];
                $orig = $_FILES['productImage']['name'];
                $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
                // basic size check (<= 3MB)
                if ($_FILES['productImage']['size'] <= 3 * 1024 * 1024) {
                    $target = $uploadDir . 'product_' . $newId . '.' . $ext;
                    validateAndResizeImage($tmp, $target, 1200, 1200);
                }
            }

            if(isset($_POST['ajax']) && $_POST['ajax'] == '1'){
                echo json_encode(['ok' => true, 'id' => $newId]);
                exit();
            }
            $_SESSION['success_message'] = 'Product "' . htmlspecialchars($productName) . '" added.';
            header('Location: ManageMenu.php');
            exit();
        } catch (Exception $e) {
            if(isset($_POST['ajax']) && $_POST['ajax'] == '1'){
                echo json_encode(['ok' => false, 'error' => $e->getMessage()]); exit();
            }
            $error_message = 'Unable to add product: ' . $e->getMessage();
        }
    }
}

// Handle Delete Product
if (isset($_POST['deleteProduct'])){
    $pid = $_POST['delete_product_id'] ?? '';
    if($pid){
        try{
            $con->DeleteProduct($pid);
            // remove image files
            foreach (glob($uploadDir . 'product_' . $pid . '.*') as $f) { @unlink($f); }
            if(isset($_POST['ajax']) && $_POST['ajax']=='1'){
                echo json_encode(['ok'=>true]); exit();
            }
            $_SESSION['success_message'] = 'Product deleted.';
            header('Location: ManageMenu.php'); exit();
        } catch(Exception $e){
            if(isset($_POST['ajax']) && $_POST['ajax']=='1'){
                echo json_encode(['ok'=>false,'error'=>$e->getMessage()]); exit();
            }
            $error_message = 'Unable to delete product.';
        }
    }
}

// Handle Edit Product
if (isset($_POST['editProduct'])){
    $pid = $_POST['edit_product_id'] ?? '';
    $pname = trim($_POST['edit_product_name'] ?? '');
    $pprice = floatval($_POST['edit_product_price'] ?? 0);
    $pstock = intval($_POST['edit_product_stock'] ?? 0);
    $pcat = $_POST['edit_product_category'] ?? '';
    $pstatus = $_POST['edit_product_status'] ?? 'Available';
    if($pid && $pname && $pcat){
        try{
            $con->UpdateProduct($pid, $pname, $pprice, $pstock, $pstatus, $pcat);
            // handle replacement image
            if (!empty($_FILES['edit_product_image']) && $_FILES['edit_product_image']['error'] === UPLOAD_ERR_OK){
                $tmp = $_FILES['edit_product_image']['tmp_name'];
                $orig = $_FILES['edit_product_image']['name'];
                $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
                $target = $uploadDir . 'product_' . $pid . '.' . $ext;
                // remove existing matches
                foreach (glob($uploadDir . 'product_' . $pid . '.*') as $f) { @unlink($f); }
                if ($_FILES['edit_product_image']['size'] <= 3 * 1024 * 1024) {
                    validateAndResizeImage($tmp, $target, 1200, 1200);
                }
            }
            if(isset($_POST['ajax']) && $_POST['ajax']=='1'){
                echo json_encode(['ok'=>true,'id'=>$pid]); exit();
            }
            $_SESSION['success_message'] = 'Product updated.';
            header('Location: ManageMenu.php'); exit();
        } catch(Exception $e){
            if(isset($_POST['ajax']) && $_POST['ajax']=='1'){
                echo json_encode(['ok'=>false,'error'=>$e->getMessage()]); exit();
            }
            $error_message = 'Unable to update product: ' . $e->getMessage();
        }
    }
}

$getProducts = $con->getAllProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <title>Manage Menu</title>
    <style>
        body{
            background:
                radial-gradient(circle at top right, rgba(15, 138, 82, 0.08), transparent 26%),
                radial-gradient(circle at bottom left, rgba(227, 151, 16, 0.08), transparent 24%),
                #f7f8f4;
        }

        .manage-shell{
            margin-left: 350px;
            min-height: 100vh;
            padding: 28px;
        }

        .dashboard-hero {
            border: 0;
            border-radius: 26px;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.07), transparent 30%),
                linear-gradient(145deg, #13181d 0%, #22282e 58%, #1c2126 100%);
            color: #f8fafc;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
        }

        .dashboard-hero__body{
            padding: 30px 32px;
        }

        .dashboard-kicker{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.22);
            color: #e6fff0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .dashboard-title{
            margin: 14px 0 8px;
            font-size: clamp(2rem, 3vw, 2.8rem);
            line-height: 1;
            font-weight: 800;
            color: #f8fafc;
        }

        .dashboard-copy{
            color: rgba(255,255,255,.84);
            max-width: 700px;
            margin-bottom: 0;
        }

        .dashboard-hero__stat{
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255,255,255,0.12);
            padding: 12px 16px;
            min-width: 180px;
        }

        .dashboard-hero__stat-label{
            font-size: .74rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.72);
            font-weight: 700;
        }

        .dashboard-hero__stat-value{
            margin-top: 4px;
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
        }

        .quick-actions-card { border-radius: 18px; border: 0; box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08); }
        .quick-action-btn { min-width:160px; }
        .owner-name{ font-family:'Brush Script MT', 'Brush Script Std', cursive; font-size:20px; }
        .sidebar { width:350px; position:fixed; top:0; left:0; height:100vh; }

        @media (max-width: 991px){
            .manage-shell{
                margin-left: 0;
                padding: 18px 14px 28px;
            }
        }
    </style>
</head>
<body>
<div class="d-flex vh-100">
    <?php renderAdminSidebar(); ?>

    <main class="w-100 manage-shell">
        <div class="container-fluid px-0">
            <?php if (!empty($error_message)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success_message'])) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-hero shadow-sm">
                        <div class="dashboard-hero__body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                <div class="dashboard-hero__copy">
                                    <span class="dashboard-kicker">Menu Control</span>
                                    <h1 class="dashboard-title">Manage Menu</h1>
                                    <p class="dashboard-copy">Add, edit, and organize products and categories from one control panel.</p>
                                </div>
                                <div class="dashboard-hero__stat text-end">
                                    <div class="dashboard-hero__stat-label">Total Categories</div>
                                    <div class="dashboard-hero__stat-value"><?php echo count($getCategory); ?></div>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-2 flex-wrap">
                                <button onclick="toProducts()" class="btn btn-light btn-sm">View Products</button>
                                <button onclick="toManageMenu()" class="btn btn-outline-light btn-sm">Manage Categories</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card quick-actions-card shadow-sm p-3 mb-3">
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-primary quick-action-btn" onclick="toProducts()">Add / View Products</button>
                            <button class="btn btn-outline-primary quick-action-btn" data-bs-toggle="collapse" data-bs-target="#categorySection">Add Category</button>
                            <button class="btn btn-outline-secondary quick-action-btn" onclick="toDashboard()">Back to Dashboard</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card shadow-sm p-3 bg-body rounded">
                        <div class="card-body">
                            <h5 class="card-title">Add Item</h5>
                            <p class="text-muted small">Create a new product for the menu.</p>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input class="form-control" name="productName" placeholder="Yummy Papa Dave" required />
                                </div>

                                <div class="mb-3 row">
                                    <div class="col-6">
                                        <label class="form-label">Price</label>
                                        <input type="text" class="form-control" name="price" placeholder="0.00" required />
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Stock</label>
                                        <input type="number" class="form-control" name="stocks" placeholder="0" required />
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="categoryId" required>
                                        <option value="">Select category</option>
                                        <?php foreach ($getCategory as $Category) : ?>
                                            <option value="<?php echo $Category['Category_ID']; ?>"><?php echo htmlspecialchars($Category['Category_Name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="d-grid">
                                    <div class="mb-2">
                                        <label class="form-label">Product Image (optional)</label>
                                        <input type="file" class="form-control form-control-sm" name="productImage" accept="image/*">
                                    </div>
                                    <button class="btn btn-success">Add Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card shadow-sm p-3 bg-body rounded">
                        <div class="card-body">
                            <h5 class="card-title">Categories</h5>
                            <p class="text-muted small">Manage product categories.</p>

                            <div class="collapse mb-3" id="categorySection">
                                <form action="" method="POST" class="row g-2 align-items-end">
                                    <div class="col-md-9">
                                        <label class="form-label">New Category</label>
                                        <input type="text" class="form-control" name="categoryInput" placeholder="e.g., Drinks" required>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary w-100" name="AddCat" type="submit">Add Category</button>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($getCategory as $Category) : ?>
                                            <tr>
                                                <td><?php echo $Category['Category_ID']; ?></td>
                                                <td><?php echo htmlspecialchars($Category['Category_Name']); ?></td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-catId="<?php echo $Category['Category_ID']; ?>" data-catName="<?php echo htmlspecialchars($Category['Category_Name']); ?>">Edit</button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTargetCategory" data-bs-catid="<?php echo $Category['Category_ID']; ?>" data-bs-category="<?php echo htmlspecialchars($Category['Category_Name']); ?>">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr />
                            <h6 class="mt-3">Menu Items</h6>
                            <div class="mb-3 d-flex gap-2 align-items-center">
                                <input id="productSearch" class="form-control form-control-sm" placeholder="Search products by name...">
                                <select id="filterCategory" class="form-select form-select-sm w-auto">
                                    <option value="">All categories</option>
                                    <?php foreach($getCategory as $cat): ?>
                                        <option value="<?php echo $cat['Category_ID']; ?>"><?php echo htmlspecialchars($cat['Category_Name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row g-3" id="productGrid">
                                <?php foreach ($getProducts as $p) : ?>
                                    <?php $imgRel = findProductImageRel($p['Product_ID']); ?>
                                    <div class="col-6 col-md-4 col-lg-3 product-card" data-name="<?php echo htmlspecialchars(strtolower($p['Product_Name'])); ?>" data-category="<?php echo $p['Category_ID'] ?? ''; ?>">
                                        <div class="card h-100 shadow-sm">
                                            <?php if($imgRel): ?>
                                                <img src="<?php echo $imgRel; ?>" class="card-img-top" style="height:140px;object-fit:cover;" alt="">
                                            <?php else: ?>
                                                <div class="card-img-top d-flex align-items-center justify-content-center" style="height:140px;background:#f3f3f3;">No image</div>
                                            <?php endif; ?>
                                            <div class="card-body p-2">
                                                <h6 class="card-title mb-1"><?php echo htmlspecialchars($p['Product_Name']); ?></h6>
                                                <div class="text-muted small mb-2"><?php echo htmlspecialchars($p['Category_Name'] ?? '-'); ?></div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="fw-bold">₱<?php echo number_format((float)($p['Product_Price'] ?? 0), 2); ?></div>
                                                    <div class="small text-muted">Stock: <?php echo $p['Stock']; ?></div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent p-2 border-0 d-flex justify-content-end gap-2">
                                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                    data-pid="<?php echo $p['Product_ID']; ?>"
                                                    data-pname="<?php echo htmlspecialchars($p['Product_Name']); ?>"
                                                    data-pprice="<?php echo number_format((float)($p['Product_Price'] ?? 0), 2); ?>"
                                                    data-pstock="<?php echo $p['Stock']; ?>"
                                                    data-pcatid="<?php echo $p['Category_ID'] ?? ''; ?>"
                                                    data-pstatus="<?php echo htmlspecialchars($p['Status']); ?>"
                                                >Edit</button>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-pid="<?php echo $p['Product_ID']; ?>">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Category Modal -->
            <div class="modal fade" id="deleteTargetCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteCategoryLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteCategoryLabel">Delete category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete <strong id="deleteMessage"></strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <form method="POST">
                                <input type="hidden" name="delete_id" id="delete_id">
                                <input type="hidden" name="delete_name" id="deleteName">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="deleteCategory" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Category Modal -->
            <div class="modal fade" id="editCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoryLabel">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="editCategoryForm">
                                <input type="hidden" name="edit_category_id" id="edit_category_id">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" class="form-control" name="edit_category_name" id="edit_category_name" required />
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="editCategoryForm" name="editCategory" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Product Modal -->
            <div class="modal fade" id="deleteProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteProductLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteProductLabel">Delete product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this product?</p>
                        </div>
                        <div class="modal-footer">
                            <form method="POST">
                                <input type="hidden" name="delete_product_id" id="delete_product_id">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="deleteProduct" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Product Modal -->
            <div class="modal fade" id="editProductModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductLabel">Edit product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="editProductForm" enctype="multipart/form-data">
                                <input type="hidden" name="edit_product_id" id="edit_product_id">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input class="form-control" name="edit_product_name" id="edit_product_name" required />
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Price</label>
                                        <input type="text" class="form-control" name="edit_product_price" id="edit_product_price" required />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Stock</label>
                                        <input type="number" class="form-control" name="edit_product_stock" id="edit_product_stock" required />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="edit_product_status" id="edit_product_status">
                                            <option>Available</option>
                                            <option>Unavailable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 mt-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="edit_product_category" id="edit_product_category" required>
                                        <option value="">Select category</option>
                                        <?php foreach($getCategory as $cat): ?>
                                            <option value="<?php echo $cat['Category_ID']; ?>"><?php echo htmlspecialchars($cat['Category_Name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Replace Image (optional)</label>
                                    <input type="file" class="form-control form-control-sm" name="edit_product_image" id="edit_product_image" accept="image/*">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="editProductForm" name="editProduct" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    (function () {
        const deleteCategoryModal = document.getElementById('deleteTargetCategory');
        if (!deleteCategoryModal) return;

        deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;
            const id = button.getAttribute('data-bs-catid') || '';
            const name = button.getAttribute('data-bs-category') || '';
            const idInput = document.getElementById('delete_id');
            const nameInput = document.getElementById('deleteName');
            const message = document.getElementById('deleteMessage');
            if (idInput) idInput.value = id;
            if (nameInput) nameInput.value = name;
            if (message) message.textContent = name;
        });
    })();
    
    (function(){
        const deleteProductModal = document.getElementById('deleteProductModal');
        if(deleteProductModal){
            deleteProductModal.addEventListener('show.bs.modal', function(event){
                const button = event.relatedTarget;
                if(!button) return;
                const pid = button.getAttribute('data-pid') || '';
                const idInput = document.getElementById('delete_product_id');
                if(idInput) idInput.value = pid;
            });
        }

        const editModal = document.getElementById('editProductModal');
        if(editModal){
            editModal.addEventListener('show.bs.modal', function(event){
                const button = event.relatedTarget; if(!button) return;
                const pid = button.getAttribute('data-pid') || '';
                const pname = button.getAttribute('data-pname') || '';
                const pprice = button.getAttribute('data-pprice') || '';
                const pstock = button.getAttribute('data-pstock') || '';
                const pcatid = button.getAttribute('data-pcatid') || '';
                const pstatus = button.getAttribute('data-pstatus') || 'Available';

                document.getElementById('edit_product_id').value = pid;
                document.getElementById('edit_product_name').value = pname;
                document.getElementById('edit_product_price').value = pprice;
                document.getElementById('edit_product_stock').value = pstock;
                document.getElementById('edit_product_status').value = pstatus;
                const catSelect = document.getElementById('edit_product_category');
                if(catSelect) catSelect.value = pcatid;
            });
        }
        // Edit Category modal population
        const editCategoryModal = document.getElementById('editCategoryModal');
        if(editCategoryModal){
            editCategoryModal.addEventListener('show.bs.modal', function(event){
                const button = event.relatedTarget; if(!button) return;
                const cid = button.getAttribute('data-catId') || '';
                const cname = button.getAttribute('data-catName') || '';
                const idInput = document.getElementById('edit_category_id');
                const nameInput = document.getElementById('edit_category_name');
                if(idInput) idInput.value = cid;
                if(nameInput) nameInput.value = cname;
            });
        }

        // Product search + filter
        const productSearch = document.getElementById('productSearch');
        const filterCategory = document.getElementById('filterCategory');
        const productGrid = document.getElementById('productGrid');
        function applyFilter(){
            const q = (productSearch?.value || '').trim().toLowerCase();
            const cid = filterCategory?.value || '';
            document.querySelectorAll('.product-card').forEach(function(card){
                const name = card.getAttribute('data-name') || '';
                const cat = card.getAttribute('data-category') || '';
                const matchesQ = !q || name.indexOf(q) !== -1;
                const matchesCat = !cid || cid === cat;
                card.style.display = (matchesQ && matchesCat) ? '' : 'none';
            });
        }
        if(productSearch) productSearch.addEventListener('input', applyFilter);
        if(filterCategory) filterCategory.addEventListener('change', applyFilter);
    })();
</script>
<script src="../functions/window.js"></script>
</body>
</html>