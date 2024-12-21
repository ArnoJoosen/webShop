<?php
require_once __DIR__ . '/core/config.php';
include "core/error_handler.php"
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
    <script src="script/register.js"></script>
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
</head>
<body>
    <!-- Navigation -->
    <?php include "core/pageElements/navBar.php"; ?>
    <div id="errorBox" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <span id="errorMessage"></span>
    </div>

    <!-- Main content -->
    <div class="container mt-4 ">
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $email = $_POST["email"];
            // Validate email regex
            // /^ = string match start of string
            // [a-zA-Z0-9._%+-] = match any letter, number, or special character
            // + = match one or more of the preceding token
            // \. = match a period
            // {2,} = match two or more of the preceding token
            // $ = match end of string
            $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
            if (!preg_match($emailRegex, $email)) {
                throw new InputValidationException("Invalid email format", "Invalid email format");
            }
            $password = $_POST["password"];
            if (empty($password)) {
                throw new InputValidationException("Password cannot be empty", "Password cannot be empty");
            }
            if (strlen($password) < 8) {
                throw new InputValidationException("Password must be at least 8 characters long", "Password must be at least 8 characters long");
            }
            if (!preg_match('/\d/', $password)) {
                throw new InputValidationException("Password must contain at least one number", "Password must contain at least one number");
            }
            if (!preg_match('/[A-Z]/', $password)) {
                throw new InputValidationException("Password must contain at least one uppercase letter", "Password must contain at least one uppercase letter");
            }
            $date_of_birth = $_POST["date_of_birth"];
            if (empty($date_of_birth)) {
                throw new InputValidationException("Date of birth cannot be empty", "Date of birth cannot be empty");
            }
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and bind
            $conn = connectToDatabase();
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
                throw new DatabaseError("Error: " . $stmt->error, "We're sorry, something went wrong. Please try again later.");
            }

            // Close the statement
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $userMessage = handleError($e);
            echo '<div class="alert alert-danger" role="alert">' . $userMessage . '</div>';
            exit();
        }
    } else {
         ?>
        <div class="row justify-content-center">
            <div class="col-md-6 p-5 mb-4 rounded-3 border border-2">
                <h1>Register</h1>
                <!-- novalidation necessary because bootstrap already validates the form -->
                <form action="register.php" method="post" onsubmit="return validatePassword()">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div id="passwordHelp" class="form-text">Password must be at least 8 characters long and contain at least one number, one uppercase letter, and one special character.</div>
                        <div id="passwordError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    <?php
    } ?>
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
