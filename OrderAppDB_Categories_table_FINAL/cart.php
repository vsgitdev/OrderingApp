<?php
session_start();

require_once("pdo.php");

if (isset($_GET['pid'])) {
    $productIdToDelete = intval($_GET['pid']);

    if ($productIdToDelete <= 0) {
        header("location: products.php");
        exit();
    }

    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];

        if (array_key_exists($productIdToDelete, $cart)) {
            unset($cart[$productIdToDelete]);            
            $totalAmount = 0;
            foreach ($cart as $productId => $item) {
                $productDetails = getProductDetailsById($productId, $PDO);
                $productPrice = $productDetails['product_price'];
                $totalAmount += $item['quantity'] * $productPrice;
            }

            $_SESSION['cart'] = $cart;
            $_SESSION['total_amount'] = $totalAmount;
        }
    }

    header("location: cart.php");
    exit();
}

function getProductDetailsById($productId, $PDO)
{
    $query = "SELECT * FROM products WHERE product_id = :product_id";
    $statement = $PDO->prepare($query);
    $statement->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $statement->execute();
    $productDetails = $statement->fetch(PDO::FETCH_ASSOC);
    return $productDetails;
}
?>
<html>
<head>
    <title>Shopping Cart</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1 class="cart">Shopping Cart</h1>
    <div class="product-grid-container">
    <table class="product-grid">
        <tr>
            <th><b>Product ID</b></th>
            <th><b>Product Name</b></th>
            <th><b>Quantity</b></th>
            <th><b>Price</b></th>
            <th><b>Delete</b></th>
        </tr>

        <?php
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $cart = $_SESSION['cart'];
            $totalAmount = 0;

            $sortedCart = [];        
            
            foreach ($cart as $productId => $item) {
                $productDetails = getProductDetailsById($productId, $PDO);
                $productName = $productDetails['product_name'];
                $productPrice = $productDetails['product_price'];
                $sortedCart[$productId] = $item;
            }
            ksort($sortedCart);

            foreach ($sortedCart as $productId => $item) {
                $productDetails = getProductDetailsById($productId, $PDO);
                $productName = $productDetails['product_name'];
                $productPrice = $productDetails['product_price'];

                echo '<tr>';
                echo '<td align="center" valign="middle">' . $productId . '</td>';
                echo '<td align="center" valign="middle">' . $productName . '</td>';
                echo '<td align="center" valign="middle">' . $item['quantity'] . '</td>';
                echo '<td align="center" valign="middle">' . $productPrice . '&euro;</td>';
                echo '<td align="center" valign="middle">';
                echo '<a href="cart.php?pid=' . $productId . '" class="delete-product"><img src="del.png" height="20"></a>';
                echo '</td>';
                echo '</tr>';

                $totalAmount += $item['quantity'] * $productPrice;
            }

            echo '</table>';
        } else {
            echo "<h1>Your shopping cart is empty.</h1>";
        }
        ?>
    </table>
    </div>
    <div class='home-page'>
        <a href="index.php" class="active"><i class="fas fa-home"></i><h3>Home Page<h3></a>
    </div>

    <div class="footer">
        <h2 class="total_cart">Total Amount: <?php echo $_SESSION['total_amount']; ?></h2>
    </div>

</body>
</html>
