<?php
session_start();
require_once("pdo.php");

$stmt = $PDO->prepare("SELECT * FROM products");
$stmt->execute();
$result_set = $stmt->fetchAll();
?>

<html>
<head>
<title>Products</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link
  href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,600,700&display=swap"
  rel="stylesheet"
/>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class='page'>
<div class='home-page'>
<a href="index.php" class="active"><i class="fas fa-home"></i><h3>Home Page<h3></a>
<div>

<?php
require_once("pdo.php");

if (isset($_GET['category_id'])) {
    $selectedCategoryId = $_GET['category_id'];

    try {     
        $categoryQuery = "SELECT * FROM categories WHERE category_id = :category_id";
        $categoryStmt = $PDO->prepare($categoryQuery);
        $categoryStmt->bindParam(':category_id', $selectedCategoryId, PDO::PARAM_INT);
        $categoryStmt->execute();
        $categoryDetails = $categoryStmt->fetch(PDO::FETCH_ASSOC);

        $selectedCategoryName = $categoryDetails['category_name'];
        
        echo '<h1>Products - ' . $selectedCategoryName . '</h1>';

        $productQuery = "SELECT * FROM products WHERE product_category_id = :category_id AND product_active = 1";
        $productStmt = $PDO->prepare($productQuery);
        $productStmt->bindParam(':category_id', $selectedCategoryId, PDO::PARAM_INT);
        $productStmt->execute();
        $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($products) > 0) {
            echo '<div class="product-buttons">';

            foreach ($products as $product) {
                echo '<div class="product-container">';
                echo '<form action="insert_cart.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
                echo '<button type="button" class="disabled-button">';
                echo $product['product_name'] . ' - $' . $product['product_price']; 
                echo '</button>';
                echo '<button type="submit"><i class="fa-solid fa-cart-shopping"></i></button>';
                echo '</form>';
            }

            echo '</div>';
        } else {
            header("Location: products.php");
        }        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {    
    header("Location: index.php");
    exit();
}
?>

<div class="footer">
<h2>Total Amount: €<?php echo isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : 0; ?></h2>
<a href="cart.php" class="active1"><i class="fa-solid fa-cart-shopping"></i><a>
<div>
</div>
</body>
</html>
