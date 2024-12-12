<?php include "core/shoppingCart-class.php"; ?>
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
</head>
<body>
    <?php include "core/pageElements/navBar.php"; ?>
    <!-- Main content -->
    <div class="container mt-4">
        <?php
        if (!isset($_SESSION['loggedin'])) {
            header('Location: login.php');
            exit;
        }
        $cart = new ShoppingCart($_SESSION["id"]);
        if ($cart->getCount() <= 0) {
            ?>
            <div class="text-center">
            <h2>Shopping Cart</h2>
            <p>There are no items in your cart. Add some items to your cart to continue.</p>
            </div>
            <?php
        } else {
        $cart->checkout();
        ?>
        <div class="text-center">
            <h2> Shopping Cart</h2>
            <?php
            $cart->displayCart();
            ?>
        </div>
        <?php } ?>
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
