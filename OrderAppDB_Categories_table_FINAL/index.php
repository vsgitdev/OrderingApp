<?php
session_start();
require_once("pdo.php");

$stmt = $PDO->prepare("SELECT * FROM products");
$stmt->execute();
$result_set = $stmt->fetchAll();
?>
<html>
<head>
<title>Categories</title>
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
<div class="home-page">
<a href="index.php" class="active"><i class="fas fa-home"></i><h3>Home Page<h3></a>

<div>
<h1>Categories</h1>

<?php
try {
    $categoryQuery = "SELECT * FROM categories WHERE category_active = 1";
    $categoryStmt = $PDO->query($categoryQuery);
    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($categories) > 0) {
        echo '<div class="category-buttons">';

        foreach ($categories as $category) {
            echo '<form action="products.php" method="get">';
            echo '<input type="hidden" name="category_id" value="' . $category['category_id'] . '">';
            echo '<button type="submit">';
            echo $category['category_name'];
            echo '</button>';
            echo '</form>';
        }

        echo '</div>';
    } else {
        echo "No categories found.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="footer">
<h2>Total Amount: €<?php echo isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : 0; ?></h2>
<a href="cart.php" class="active1"><i class="fa-solid fa-cart-shopping"></i><a>
<div>
</div>
</div>
</body>
</html>
