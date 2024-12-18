<?php
require_once __DIR__ . '/../core/config.php';
function displayTable() {
    $conn = connectToDatabase();
    $sql = "SELECT Orders.*, Customer.first_name, Customer.last_name FROM Orders JOIN Customer ON Orders.customer_id = Customer.id";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
    ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
            <td><?php echo $row['order_date']; ?></td>
            <td>€<?php echo $row['total_price']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <button class='btn btn-primary btn-sm me-2' data-bs-toggle='modal' data-bs-target='#editModal<?php echo $row['id']; ?>'><i class="fas fa-edit"></i></button>
                <button class='btn btn-info btn-sm' onclick='showOrderDetails(<?php echo $row['id']; ?>)'><i class="fas fa-info-circle"></i></button>
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
                        <form onsubmit="return changeStatus(event, <?php echo $row['id']; ?>);">
                            <input type='hidden' name='order_id' value='<?php echo htmlspecialchars($row['id']); ?>'>
                            <select class='form-select' name='status'>
                                <option value='pending' <?php echo ($row['status'] == 'pending' ? 'selected="selected"' : ''); ?>>Pending</option>
                                <option value='shipped' <?php echo ($row['status'] == 'shipped' ? 'selected="selected"' : ''); ?>>Shipped</option>
                                <option value='delivered' <?php echo ($row['status'] == 'delivered' ? 'selected="selected"' : ''); ?>>Delivered</option>
                                <option value='cancelled' <?php echo ($row['status'] == 'cancelled' ? 'selected="selected"' : ''); ?>>Cancelled</option>
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
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $subtotal = $row['quantity'] * $row['price'];
        $total += $subtotal;
        ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['price']; ?>€</td>
                <td><?php echo $subtotal; ?>€</td>
            </tr>
        <?php
    }
    $stmt->close();
    $conn->close();
    return round($total, 2);
}
session_start();
if (!isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] !== true) {
    header("Location: login.php");
}

// if post change status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    header('Content-Type: application/json');
    $conn = connectToDatabase();
    $order_id = $conn->real_escape_string($_POST['order_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $sql = "UPDATE Orders SET status='$status' WHERE id='$order_id'";
    $conn->query($sql);
    $conn->close();

    ob_start();
    displayTable();
    echo json_encode(['success' => true, 'content' => ob_get_clean()]);
    exit();
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
                            <?php displayTable(); ?>
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
