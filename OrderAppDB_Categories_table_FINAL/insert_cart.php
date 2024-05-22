<?php
session_start();
require_once("pdo.php");

$productId = $_POST['product_id'];

if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];

    if (array_key_exists($productId, $cart)) {
        $cart[$productId]['quantity']++;
    } else {
        $cart[$productId] = [
            'product_id' => $productId,
            'quantity' => 1
        ];
    }

    $totalAmount = 0;
    foreach ($cart as $product) {
        $productId = $product['product_id'];
        $quantity = $product['quantity'];

        try {
            $productQuery = "SELECT * FROM products WHERE product_id = :product_id";
            $productStmt = $PDO->prepare($productQuery);
            $productStmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $productStmt->execute();
            $productDetails = $productStmt->fetch(PDO::FETCH_ASSOC);

            $productPrice = $productDetails['product_price'];
            $subtotal = $quantity * $productPrice;
            $totalAmount += $subtotal;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    $_SESSION['cart'] = $cart;
    $_SESSION['total_amount'] = $totalAmount;
} else {
    $cart = [];

    $cart[$productId] = [
        'product_id' => $productId,
        'quantity' => 1
    ];

    $totalAmount = 0;
    foreach ($cart as $product) {
        $productId = $product['product_id'];
        $quantity = $product['quantity'];

        try {
            $productQuery = "SELECT * FROM products WHERE product_id = :product_id";
            $productStmt = $PDO->prepare($productQuery);
            $productStmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $productStmt->execute();
            $productDetails = $productStmt->fetch(PDO::FETCH_ASSOC);

            $productPrice = $productDetails['product_price'];
            $subtotal = $quantity * $productPrice;
            $totalAmount += $subtotal;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    $_SESSION['cart'] = $cart;
    $_SESSION['total_amount'] = $totalAmount;
}

header("Location: products.php");
exit();
?>
