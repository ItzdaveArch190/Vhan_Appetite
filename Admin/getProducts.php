<?php
require_once('../Database/database.php');
header('Content-Type: application/json; charset=utf-8');
$con = new Database();
// support single id fetch
$page = max(1, intval($_GET['page'] ?? 1));
$per = max(1, intval($_GET['per_page'] ?? 24));
$search = trim($_GET['search'] ?? '');
$category = isset($_GET['category']) && $_GET['category'] !== '' ? $_GET['category'] : null;
$offset = ($page - 1) * $per;
$singleId = isset($_GET['id']) && $_GET['id'] !== '' ? intval($_GET['id']) : null;
try{
    $baseDir = __DIR__ . '/../uploads/products/';
    if($singleId){
        $items = $con->getProductsPage(1, 0, '', null);
        // try a targeted query instead
        $conp = $con->opencon();
        $stmt = $conp->prepare("SELECT
                p.Product_ID,
                p.Product_Name,
                MAX(pp.Product_Price) AS Product_Price,
                p.Stock,
                p.Status,
                c.Category_Name,
                MAX(pc.Category_ID) AS Category_ID
            FROM product p
            LEFT JOIN product_category pc ON p.Product_ID = pc.Product_ID
            LEFT JOIN category c ON pc.Category_ID = c.Category_ID
            LEFT JOIN product_price pp ON p.Product_ID = pp.Product_ID
            WHERE p.Product_ID = ?
            GROUP BY p.Product_ID");
        $stmt->execute([$singleId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($items as &$it){
            $matches = glob($baseDir . 'product_' . $it['Product_ID'] . '.*');
            $it['image'] = $matches ? str_replace(__DIR__ . '/../', '../', $matches[0]) : '';
        }
        echo json_encode(['ok'=>true,'page'=>1,'per_page'=>1,'total'=>count($items),'items'=>$items]);
        exit();
    }

    $items = $con->getProductsPage($per, $offset, $search, $category);
    // attach image rel path
    foreach($items as &$it){
        $matches = glob($baseDir . 'product_' . $it['Product_ID'] . '.*');
        $it['image'] = $matches ? str_replace(__DIR__ . '/../', '../', $matches[0]) : '';
    }
    $total = $con->getProductsCount($search, $category);
    echo json_encode(['ok' => true, 'page' => $page, 'per_page' => $per, 'total' => $total, 'items' => $items]);
}catch(Exception $e){
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
