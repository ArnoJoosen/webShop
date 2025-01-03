<?php
require_once __DIR__ . '/../core/config.php';
require_once __DIR__ . "/../core/error_handler.php";
function displayTable() {
    $conn = connectToDatabase();
    $sql = "SELECT Orders.*, Customer.first_name, Customer.last_name, Address.street, Address.street_number, Address.city, Address.postal_code, Address.country
            FROM Orders
            JOIN Customer ON Orders.customer_id = Customer.id
            JOIN Address ON Orders.address_id = Address.id
            where Orders.status != 'delivered';";
    $result = $conn->query($sql);
    if (!$result) {
        throw new DatabaseError("Error: " . $conn->error, "We're sorry, something went wrong. Please try again later.");
    }
    while($row = $result->fetch_assoc()) {
    ?>
        <tr>
            <td><?php $html = htmlspecialchars($row['id']); echo $html; ?></td>
            <td><?php $html = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); echo $html; ?></td>
            <td><?php $html = date('d-m-Y', strtotime($row['order_date'])); echo $html; ?></td>
            <td><?php $html = htmlspecialchars($row['total_price']); echo $html; ?>€</td>
            <td><?php $html = htmlspecialchars($row['status']); echo $html; ?></td>
            <td>
                <button class='btn btn-primary btn-sm me-2' data-bs-toggle='modal' data-bs-target='#editModal<?php echo $row['id']; ?>'><i class="fas fa-edit"></i></button>
                <button class='btn btn-info btn-sm' onclick='showOrderDetails(<?php echo htmlspecialchars($row['id']); ?>)'><i class="fas fa-info-circle"></i></button>
            </td>
        </tr>

        <!-- Modal for each order -->
        <div class='modal fade' id='editModal<?php echo htmlspecialchars($row['id']); ?>'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Edit Order Status</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body'>
                        <div class="mb-3">
                            <h6>Shipping Address:</h6>
                            <p>
                                <?php $html = htmlspecialchars($row['street'] . ' ' . $row['street_number']); echo $html; ?><br>
                                <?php $html = htmlspecialchars($row['postal_code'] . ' ' . $row['city']); echo $html; ?><br>
                                <?php $html = htmlspecialchars($row['country']); echo $html; ?>
                            </p>
                        </div>
                        <form onsubmit="return changeStatus(event, <?php echo $row['id']; ?>);">
                            <input type='hidden' name='order_id' value='<?php echo htmlspecialchars($row['id']); ?>'>
                            <select class='form-select' name='status'>
                                <option value='pending' <?php echo (htmlspecialchars($row['status']) == 'pending' ? 'selected="selected"' : ''); ?>>Pending</option>
                                <option value='shipped' <?php echo (htmlspecialchars($row['status']) == 'shipped' ? 'selected="selected"' : ''); ?>>Shipped</option>
                                <option value='delivered' <?php echo (htmlspecialchars($row['status']) == 'delivered' ? 'selected="selected"' : ''); ?>>Delivered</option>
                                <option value='cancelled' <?php echo (htmlspecialchars($row['status']) == 'cancelled' ? 'selected="selected"' : ''); ?>>Cancelled</option>
                            </select>
                            <button type='submit' class='btn btn-primary mt-3'>Update Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    $conn->close();
}

function displayOrderDetails($order_id) {
    $conn = connectToDatabase();
    $total = 0;
    $sql = "SELECT Product.name, Order_Product.quantity, Order_Product.price
            FROM Order_Product
            JOIN Product ON Order_Product.product_id = Product.id
            WHERE Order_Product.orders_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    if (!$stmt->execute()) {
        throw new DatabaseError("Error: " . $conn->error, "We're sorry, something went wrong. Please try again later.");
    }
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
    $conn->close();
    return round($total, 2);
}
session_start();
try {
    if (!isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] !== true) {
        throw new AdminError("Please log in as an admin to access this page.");
    }
} catch (Exception $e) {
    $userMessage = handleError($e);
    header("Location: login.php");
}

// if post change status
if ($_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['order_id']) && is_numeric($_POST['order_id'])
    && isset($_POST['status']) && !empty($_POST['status'])) {
    header('Content-Type: application/json');
    try {
        $conn = connectToDatabase();
        $stmt = $conn->prepare("UPDATE Orders SET status=? WHERE id=?");
        $stmt->bind_param("si", $_POST['status'], $_POST['order_id']);
        if (!$stmt->execute()) {
            throw new DatabaseError("Error: " . $conn->error, "We're sorry, something went wrong. Please try again later.");
        }
        $stmt->close();
        $conn->close();

        ob_start();
        displayTable();
        echo json_encode(['success' => true, 'content' => ob_get_clean()]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    try {
        throw new InputValidationException("Invalid request", "Invalid request");
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    header('Content-Type: application/json');
    try {
        ob_start();
        $total = displayOrderDetails($_GET['order_id']);
        echo json_encode(['success' => true, 'content' => ob_get_clean(), 'total' => $total]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
    <script src="script/orders.js"> </script>
</head>
<body>
    <?php include "pageElements/navBar.php"; ?>
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
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ajax">
                        <?php
                            try {
                                displayTable();
                            } catch (Exception $e) {
                                $userMessage = handleError($e);
                                echo "<div class='alert alert-danger'>An error occurred while fetching categories: " . $userMessage . "</div>";
                            }
                        ?>
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
    <script src="../script/themeToggle.js"></script>
</body>
</html>
