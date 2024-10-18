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
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Webshop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">
                            <i class="fas fa-user"></i> Login
                        </a>
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
    <!-- Main content -->
    <div class="container mt-5">
        <div class="p-5 mb-4 rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-4">Welcome to Our Webshop!</h1>
                <script src="scripts.js"></script>
                <p class="lead">We offer a wide variety of products to suit your needs.</p>
                <hr class="my-4">
                <p>Browse our collection and find what you need.</p>
                <a class="btn btn-primary btn-lg" href="#" role="button">Shop Now</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <footer class="footer mt-auto py-3 theme">
        <div class="container">
            <span class="text-muted">© 2023 Webshop. All rights reserved.</span>
        </div>
    </footer>
    <script src="themeToggle.js"></script>
</body>
</html>
