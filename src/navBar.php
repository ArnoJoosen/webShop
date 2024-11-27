<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="/">Webshop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <?php
                    session_start();
                    if (
                        isset($_SESSION["loggedin"]) &&
                        $_SESSION["loggedin"] === true
                    ) { ?>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $_SESSION["first_name"] .
                                    " " .
                                    $_SESSION["last_name"]; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="./cart.php">Winkel Card</a></li>
                                <li><a class="dropdown-item" href="./orders.php">Orders</a></li>
                                <li><a class="dropdown-item" href="./logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php } else { ?>
                    <a class="nav-link" href="./login.php">
                        <i class="fas fa-user"></i> Login
                    </a>
                    <?php }
                    ?>
                </li>
            </ul>
        </div>
        <div class="theme-toggle ms-2">
            <button id="themeToggle" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sun"></i>
            </button>
        </div>
    </div>
</nav>
