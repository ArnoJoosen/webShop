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
    <?php include "navBar.php"; ?>

    <!-- Main content -->
    <rewrite_this>
        <div class="container mt-4 ">
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dbservername = "db";
            $dbusername = "webuser"; // TOD change to env variable (security risk)
            $dbpassword = "webpassword"; // TOD change to env variable (security risk)
            $database = "webshop";
            $conn = new mysqli(
                $dbservername,
                $dbusername,
                $dbpassword,
                $database
            );

            $first_name = $_POST["first_name"]; // TODO check if the input is valid
            $last_name = $_POST["last_name"]; // TODO check if the input is valid
            $email = $_POST["email"]; // TODO check if the input is valid
            $password = $_POST["password"]; // TODO check if the input is valid
            $date_of_birth = $_POST["date_of_birth"]; // TODO check if the input is valid
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and bind
            $stmt = $conn->prepare(
                "INSERT INTO Customer (first_name, last_name, email, passwordhash, date_of_birth) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "sssss",
                $first_name,
                $last_name,
                $email,
                $passwordHash,
                $date_of_birth
            );

            // Execute the statement
            if ($stmt->execute() === true) {
                echo '<div class="alert alert-success" role="alert">User registered successfully</div>'; // TODO change to redirect to login page after 3 seconds
            } else {
                echo '<div class="alert alert-danger" role="alert">Error: ' .
                    $stmt->error .
                    "</div>"; // TODO remove error message in production change to generic error message
            }

            // Close the statement
            $stmt->close();
            $conn->close();
        } else {
             ?>
            <div class="row justify-content-center">
                <div class="col-md-6 p-5 mb-4 rounded-3 border border-2">
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
        <?php
        } ?>
        </div>
    </rewrite_this>

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
