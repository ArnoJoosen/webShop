<?php
require_once "core/shoppingCart-class.php";
require_once "core/error_handler.php"
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webshop Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
    <script src="script/shoppingCart.js"></script>
</head>
    <body>
        <?php include "core/pageElements/navBar.php"; ?>
        <!-- Main content -->
        <div class="container mt-4">
            <?php
            try {
                if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
                    throw new UnauthorizedError("attempt to access shopping cart without logging in", "Please log in to access the shopping cart");
                }
            } catch (WebShopErrorHandler $e) {
                $error_message = handleError($e);
                header("Location: login.php");
                exit();
            }
            // post request
            try {
                if ($_SERVER["REQUEST_METHOD"] == "POST" &&
                    isset($_POST["product_id"]) &&
                    isset($_POST["name"]) &&
                    isset($_POST["action"])
                ) {
                    // create a new shopping cart object for the user
                    $cart = new ShoppingCart($_SESSION["id"]); ?>
                    <div class="text-center">
                        <div id="ajax">
                            <?php
                            switch ($_POST["action"]) {
                                case "add":
                                    $cart->addItem($_POST["product_id"]);
                                    echo "<h2>" . htmlspecialchars($_POST["name"]) . " Added to Cart</h2>";
                                    break;
                                case "remove":
                                    $cart->removeItem($_POST["product_id"]);
                                    echo "<h2>" . htmlspecialchars($_POST["name"]) . " Removed from Cart</h2>";
                                    break;
                                case "decrement":
                                    $cart->decrementItem($_POST["product_id"]);
                                    echo "<h2>One " . htmlspecialchars($_POST["name"]) . " Removed from Cart</h2>";
                                    break;
                                case "increment":
                                    $cart->incrementItem($_POST["product_id"]);
                                    echo "<h2>One " . htmlspecialchars($_POST["name"]) . " Added to Cart</h2>";
                                    break;
                                default:
                                    throw new SessionException("Invalid action in shopping cart", "We're sorry, something went wrong. Please try again later.");
                            }?>
                            <?php $cart->displayCartEditor(); ?>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-secondary me-2" onclick="history.back()">Back to Shopping</button>
                            <a href="checkout.php" class="btn btn-primary <?php echo $cart->getCount() == 0 ? "disabled": ""; ?>">Order</a>
                        </div>
                    </div>
                <?php
                } else {
                    // create a new shopping cart object for the user if not already created
                    $cart = new ShoppingCart($_SESSION["id"]); ?>
                        <div class="text-center">
                            <div id="ajax">
                            <h2> Shopping Cart</h2>
                                <?php $cart->displayCartEditor(); ?>
                            </div>
                            <div class="mt-4">
                                <button class="btn btn-secondary me-2" onclick="history.back()">Back to Shopping</button>
                                <a href="checkout.php" class="btn btn-primary <?php echo $cart->getCount() == 0 ? "disabled" : ""; ?>">Order</a>
                            </div>
                        </div>
                <?php
                }
            } catch (Exception $e) {
                $error_message = handleError($e);
                echo "<h2>" . $error_message . "</h2>";
            }
            ?>
        </div>
        <!-- Footer -->
        <footer class="footer mt-auto py-3 theme">
            <div class="container">
                <span class="text-muted">© 2023 Webshop. All rights reserved.</span>
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="script/themeToggle.js"></script>
    </body>
</html>
