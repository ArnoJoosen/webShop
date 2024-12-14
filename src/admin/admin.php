<?php
    session_start();
    if (!isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] !== true) {
        header("Location: login.php");
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
</head>
<body>
    <?php include "pageElements/navBar.php"; ?>
    <!-- Main content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1>Welcome to Admin Dashboard</h1>
                <p class="lead">Manage your webshop from here.</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-box"></i> Products</h5>
                        <p class="card-text">Manage products, inventory, and categories.</p>
                        <a href="products.php" class="btn btn-primary">Manage Products</a>
                        <a href="categories.php" class="btn btn-secondary">Manage Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users"></i> Users</h5>
                        <p class="card-text">Manage customer accounts and admin users.</p>
                        <a href="users.php" class="btn btn-primary">Manage Users</a>
                        <!-- Only superAdmin can manage admins -->
                        <a href="admins.php" class="btn btn-secondary <?php echo ($_SESSION['admin_role'] !== 'superAdmin') ? 'disabled' : ''; ?>">Manage Admins</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Orders</h5>
                        <p class="card-text">View and process customer orders.</p>
                        <a href="orders.php" class="btn btn-primary">Manage Orders</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <!-- TODO use database  -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-line"></i> Recent Activity</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">New order #1234 received</li>
                            <li class="list-group-item">Product inventory updated</li>
                            <li class="list-group-item">New user registration</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-bar"></i> Quick Stats</h5>
                        <ul class="list-group list-group-flush">
                            <?php
                                require_once $_SERVER['DOCUMENT_ROOT'] . '/core/config.php';

                                // Count registered users
                                $conn = connectToDatabase();
                                $userQuery = "SELECT COUNT(*) as count FROM Customer";
                                $userResult = $conn->query($userQuery);
                                $userCount = $userResult->fetch_assoc()['count'];

                                // Count pending orders
                                $orderQuery = "SELECT COUNT(*) as count FROM Orders";
                                $orderResult = $conn->query($orderQuery);
                                $orderCount = $orderResult->fetch_assoc()['count'];

                                // Count out of stock items
                                $stockQuery = "SELECT COUNT(*) as count FROM Product WHERE stock = 0";
                                $stockResult = $conn->query($stockQuery);
                                $stockCount = $stockResult->fetch_assoc()['count'];
                                $conn->close();
                            ?>
                            <li class="list-group-item">Registered Users: <span class="fw-bold text-primary"><?php echo htmlspecialchars($userCount); ?></span></li>
                            <li class="list-group-item">Pending Orders: <span class="fw-bold text-primary"><?php echo htmlspecialchars($orderCount); ?></span></li>
                            <li class="list-group-item">Out of Stock Items: <span class="fw-bold text-primary"><?php echo htmlspecialchars($stockCount); ?></span></li>
                        </ul>
                    </div>
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
