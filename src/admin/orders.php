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
    <script src="script/admins.js"> </script>
</head>
<body>
    <?php session_start(); ?>
    <?php include "pageElements/navBar.php"; ?>
    <!-- Main content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2>Orders</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Order Date</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                require_once __DIR__ . '/../core/config.php';
                                $conn = connectToDatabase();
                                $sql = "SELECT * FROM Orders";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>".$row['id']."</td>";
                                    echo "<td>".$row['customer_id']."</td>";
                                    echo "<td>".$row['order_date']."</td>";
                                    echo "<td>€".$row['total_price']."</td>";
                                    echo "<td>".$row['status']."</td>";
                                    echo "<td>";
                                    echo "<button class='btn btn-primary btn-sm me-2' data-bs-toggle='modal' data-bs-target='#editModal".$row['id']."'>Edit Status</button>";
                                    echo "</td>";
                                    echo "</tr>";

                                    // Modal for each order
                                    echo "<div class='modal fade' id='editModal".$row['id']."' tabindex='-1'>";
                                    echo "<div class='modal-dialog'>";
                                    echo "<div class='modal-content'>";
                                    echo "<div class='modal-header'>";
                                    echo "<h5 class='modal-title'>Edit Order Status</h5>";
                                    echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
                                    echo "</div>";
                                    echo "<div class='modal-body'>";
                                    echo "<form action='updateOrder.php' method='post'>";
                                    echo "<input type='hidden' name='order_id' value='".$row['id']."'>";
                                    echo "<select class='form-select' name='status'>";
                                    echo "<option value='pending' ".($row['status']=='pending'?'selected':'').">Pending</option>";
                                    echo "<option value='shipped' ".($row['status']=='shipped'?'selected':'').">Shipped</option>";
                                    echo "<option value='delivered' ".($row['status']=='delivered'?'selected':'').">Delivered</option>";
                                    echo "<option value='cancelled' ".($row['status']=='cancelled'?'selected':'').">Cancelled</option>";
                                    echo "</select>";
                                    echo "<button type='submit' class='btn btn-primary mt-3'>Update Status</button>";
                                    echo "</form>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                                $conn->close();
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
