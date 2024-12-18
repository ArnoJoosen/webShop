<?php
require_once __DIR__ . '/core/config.php';
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}
function displayOrderDetails($order_id) {
    $conn = connectToDatabase();
    $total = 0;

    // First verify this order belongs to the logged in customer
    $verify_sql = "SELECT customer_id FROM Orders WHERE id = ?";
    $verify_stmt = $conn->prepare($verify_sql);
    $verify_stmt->bind_param("i", $order_id);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();
    $order_data = $verify_result->fetch_assoc();

    // If the order does not belong to the logged in customer, exit
    if (!$order_data || $order_data['customer_id'] != $_SESSION['id']) {
        $conn->close();
        exit();
    }

    $sql = "SELECT Product.name, Order_Product.quantity, Order_Product.price
            FROM Order_Product
            JOIN Product ON Order_Product.product_id = Product.id
            WHERE Order_Product.orders_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $subtotal = $row['quantity'] * $row['price'];
        $total += $subtotal;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?>€</td>
                <td><?php echo htmlspecialchars($subtotal); ?>€</td>
            </tr>
        <?php
    }
    $stmt->close();
    $verify_stmt->close();
    $conn->close();
    return round($total, 2);
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_id'])) {
    header('Content-Type: application/json');
    ob_start();
    $total = displayOrderDetails($_GET['order_id']);
    echo json_encode(['success' => true, 'content' => ob_get_clean(), 'total' => $total]);
    exit();
}
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
    <script src="script/orders.js"></script>
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
</head>
<body>
    <!-- Navigation -->
    <?php include "core/pageElements/navBar.php";?>

    <!-- Main content -->
    <div id="errorBox" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <span id="errorMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class='modal fade' id='detailsModal' tabindex='-1'>
        <div class='modal-dialog modal-lg'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Order Details</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                </div>
                <div class='modal-body'>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Products</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderDetailsContent">
                                        <!-- Content will be loaded via AJAX -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Order Total:</th>
                                            <th id="orderTotal">0€</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2>Orders</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Order Date</th>
                                <th>Shipping Address</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $conn = connectToDatabase();
                            $stmt = $conn->prepare("SELECT Orders.*, Customer.first_name, Customer.last_name, Address.street, Address.street_number, Address.city, Address.postal_code, Address.country
                                    FROM Orders
                                    JOIN Customer ON Orders.customer_id = Customer.id
                                    JOIN Address ON Orders.address_id = Address.id
                                    WHERE Customer.id = ?");
                            $stmt->bind_param("i", $_SESSION['id']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['order_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['street']) . ' ' . htmlspecialchars($row['street_number']); ?><br>
                                            <?php echo htmlspecialchars($row['postal_code']) . ' ' . htmlspecialchars($row['city']); ?><br>
                                            <?php echo htmlspecialchars($row['country']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['total_price']); ?>€</td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td>
                                            <button class='btn btn-info btn-sm' onclick='showOrderDetails(<?php echo $row['id']; ?>)'><i class="fas fa-info-circle"></i></button>
                                        </td>
                                    </tr>
                                <?php
                            }
                            $conn->close();?>
                        </tbody>
                    </table>
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
