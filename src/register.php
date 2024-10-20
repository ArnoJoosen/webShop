<?php include "cards.php"; ?>
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
        <div class="container">
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
    <rewrite_this>
        <div class="container mt-4">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dbservername = "db";
            $dbusername = "webuser"; // TOD change to env variable (security risk)
            $dbpassword = "webpassword"; // TOD change to env variable (security risk)
            $database = "webshop";
            $conn = new mysqli($dbservername, $dbusername, $dbpassword, $database);

            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $date_of_birth = $_POST["date_of_birth"];
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO Customer (first_name, last_name, email, passwordhash, date_of_birth) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $first_name, $last_name, $email, $passwordHash, $date_of_birth);

            // Execute the statement
            if ($stmt->execute() === TRUE) {
                echo '<div class="alert alert-success" role="alert">User registered successfully</div>'; // TODO change to redirect to login page after 3 seconds
            } else {
                echo '<div class="alert alert-danger" role="alert">Error: ' . $stmt->error . '</div>'; // TODO remove error message in production change to generic error message
            }

            // Close the statement
            $stmt->close();
        } else { ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1>Register</h1>
                    <form action="register.php" method="post">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name">
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        <?php } ?>
        </div>
    </rewrite_this>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 theme">
        <div class="container">
            <span class="text-muted">© 2023 Webshop. All rights reserved.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="themeToggle.js"></script>
</body>
</html>
