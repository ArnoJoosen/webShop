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
    <script src="script/checkout.js"></script>
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
</head>
<body>
    <?php include "core/pageElements/navBar.php"; ?>
    <div id="errorBox" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <span id="errorMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
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
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['address']) && isset($_POST['paymentMethod'])) { ?>
                <div class="text-center">
                    <h2>Thanks for ordering! Your order will be shipped soon.</h2>
                    <?php
                    $cart->displayCart();
                    ?>
                </div>
                <?php
                $cart->checkout($_POST['address']);
            } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
                ?><h2>Shopping Cart</h2><?php
                $cart->displayCart();
                require_once __DIR__ . '/core/config.php';
                $conn = connectToDatabase();
                $sql = "SELECT * FROM Address WHERE customer_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_SESSION["id"]);
                $stmt->execute();
                $result = $stmt->get_result();

                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="checkout-form">
                    <div class="mb-4">
                        <h2 class="mb-3">Delivery Address</h2>
                        <div id="addressList">
                            <?php if($result->num_rows > 0) {?>
                                <div class="form-group address-list">
                                    <?php while($row = $result->fetch_assoc()) { ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="address" id="address<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" required>
                                            <label class="form-check-label" for="address<?php echo $row['id']; ?>">
                                                <?php echo $row['street'] . ' ' . $row['street_number'] . ', ' . $row['postal_code'] . ' ' . $row['city'] . ', ' . $row['country']; ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p class="text-warning">No addresses found. Please add an address first.</p>
                            <?php } ?>
                        </div>

                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="fas fa-plus"></i> Add New Address
                        </button>
                    </div>

                    <div class="mb-4">
                        <h2 class="mb-3">Payment Method</h2>
                        <div class="form-group payment-methods">
                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="ideal" value="ideal" required>
                                        <label class="form-check-label" for="ideal">iDEAL</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="bancontact" value="bancontact">
                                        <label class="form-check-label" for="bancontact">Bancontact</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="applepay" value="applepay">
                                        <label class="form-check-label" for="applepay">Apple Pay</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="googlepay" value="googlepay">
                                        <label class="form-check-label" for="googlepay">Google Pay</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="maestro" value="maestro">
                                        <label class="form-check-label" for="maestro">Maestro</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="mastercard" value="mastercard">
                                        <label class="form-check-label" for="mastercard">Mastercard</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="visa" value="visa">
                                        <label class="form-check-label" for="visa">Visa</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
                <?php
            }
        } ?>
    </div>
    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addressForm" onsubmit="addAddress(event)">
                        <div class="mb-3">
                            <label for="street" class="form-label">Street</label>
                            <input type="text" class="form-control" id="street" name="street" required>
                        </div>
                        <div class="mb-3">
                            <label for="street_number" class="form-label">Street Number</label>
                            <input type="number" class="form-control" id="street_number" name="street_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Address</button>
                    </form>
                </div>
            </div>
        </div>
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
