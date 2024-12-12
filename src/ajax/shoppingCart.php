<?php
include "../core/shoppingCart-class.php";
session_start();
if (!isset($_SESSION["loggedin"])) {
    echo "You are not logged in!";
    exit();
}
$cart = new ShoppingCart($_SESSION["id"]);
if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["product_id"]) &&
    isset($_POST["name"]) &&
    isset($_POST["action"])
) {
    if ($_POST["action"] == "remove") {
        $cart->removeItem($_POST["product_id"]);
        echo "<h2>" . $_POST["name"] . " Removed from Cart</h2>";
    } elseif ($_POST["action"] == "decrement") {
        $cart->decrementItem($_POST["product_id"]);
        echo "<h2>One " . $_POST["name"] . " Removed from Cart</h2>";
    } elseif ($_POST["action"] == "increment") {
        $cart->incrementItem($_POST["product_id"]);
        echo "<h2>One " . $_POST["name"] . " Added to Cart</h2>";
    }
    $cart->displayCartEditor();
} else {
    $cart->displayCartEditor();
}
?>
