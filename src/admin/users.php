<?php
require_once __DIR__ . '/../core/config.php';
session_start();
if (!isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] !== true) {
    header("Location: login.php");
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["user_id"])) {
    $conn = connectToDatabase();
    $userId = mysqli_real_escape_string($conn, $_GET["user_id"]);

    // Get orders with product details
    $sql = "SELECT o.*, a.street, a.street_number, a.city, a.postal_code, a.country,
            GROUP_CONCAT(CONCAT(p.name, ' (', op.quantity, ')') SEPARATOR ', ') as products
            FROM Orders o
            LEFT JOIN Order_Product op ON o.id = op.orders_id
            LEFT JOIN Product p ON op.product_id = p.id
            LEFT JOIN Address a ON o.address_id = a.id
            WHERE o.customer_id = ?
            GROUP BY o.id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    header('Content-Type: application/json');
    if($stmt->execute()) {
        $result = $stmt->get_result();

        $html = '';
        while($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';;
            $html .= '<td>' . htmlspecialchars($row['street'] . ' ' . $row['street_number'] . ', ' .
                     $row['postal_code'] . ' ' . $row['city'] . ', ' . $row['country']) . '</td>';
            $html .= '<td>' . date('d/m/Y', strtotime($row['order_date'])) . '</td>';
            $html .= '<td>' . number_format($row['total_price'], 2) . '€</td>';
            $html .= '<td>' . htmlspecialchars($row['status']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['products']) . '</td>';
            $html .= '</tr>';
        }

        echo json_encode(['success' => true, 'content' => $html]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }

    $conn->close();
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
    <script src="script/users.js"></script>
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
</head>
<body>
    <?php include "pageElements/navBar.php"; ?>
    <div id="errorBox" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <span id="errorMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <!-- Main content -->
    <div class="container mt-4">
        <h2>Customer Management</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = connectToDatabase();
                    $sql = "SELECT * FROM Customer";
                    if($result = mysqli_query($conn, $sql)) {
                        while($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['first_name']) ?></td>
                                <td><?= htmlspecialchars($row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['date_of_birth'])) ?></td>
                                <td>
                                    <button class="btn btn-primary" onclick="viewOrders(<?php echo $row['id']; ?>)"><i class="fas fa-eye"></i> View Orders</button>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Orders Section Modal -->
    <div class="modal fade" id="ordersModal" tabindex="-1" aria-labelledby="ordersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ordersModalLabel">Customer Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="ordersTable">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Address ID</th>
                                    <th>Order Date</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Products</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <!-- Orders will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
