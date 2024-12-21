<?php
require_once "../core/shoppingCart-class.php";
require_once __DIR__ . "/../core/error_handler.php";
try {
session_start();
    if (!isset($_SESSION["loggedin"])) {
        echo "You are not logged in!";
        exit();
    }
    $cart = new ShoppingCart($_SESSION["id"]);
    if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        isset($_POST["product_id"]) && is_numeric($_POST["product_id"]) &&
        isset($_POST["name"]) && !empty($_POST["name"]) &&
        isset($_POST["action"])
    ) {
        if ($_POST["action"] == "remove") {
            $cart->removeItem($_POST["product_id"]);
            echo "<h2>" . htmlspecialchars($_POST["name"]) . " Removed from Cart</h2>";
        } elseif ($_POST["action"] == "decrement") {
            $cart->decrementItem($_POST["product_id"]);
            echo "<h2>One " . htmlspecialchars($_POST["name"]) . " Removed from Cart</h2>";
        } elseif ($_POST["action"] == "increment") {
            $cart->incrementItem($_POST["product_id"]);
            echo "<h2>One " . htmlspecialchars($_POST["name"]) . " Added to Cart</h2>";
        }
        $cart->displayCartEditor();
    } else {
        $cart->displayCartEditor();
    }
} catch (Exception $e) {
    $error_message = handleError($e);
    echo "<h2>" . $error_message . "</h2>";
}
?>
