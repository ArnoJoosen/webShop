<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webshop Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles-login.css">
    <script src="script/login.js"></script>
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
</head>
<body>
    <!-- Navigation -->
    <?php
    include "core/pageElements/navBar.php";
    require_once __DIR__ . '/core/config.php';
    require_once __DIR__ . "/core/error_handler.php";
    ?>

    <!-- Main content -->
<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
    try {
        $conn = connectToDatabase();
        $email = $_POST["email"];
        // Validate email regex to avoid unnecessary database query
        // /^ = string match start of string
        // [a-zA-Z0-9._%+-] = match any letter, number, or special character
        // + = match one or more of the preceding token
        // \. = match a period
        // {2,} = match two or more of the preceding token
        // $ = match end of string
        $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($emailRegex, $email)) {
            echo '<div class="alert alert-danger" role="alert">Invalid email format</div>';
            exit;
        }

        $password = $_POST["password"];
        // evoide unecessary database query if password is empty or less than 8 characters
        if (empty($password) || strlen($password) < 8) {
            echo '<div class="alert alert-danger" role="alert">Password must be at least 8 characters long</div>';
            echo "<p>" . strlen($password) . "</p>";
            exit;
        }

        $stmt = $conn->prepare(
            "SELECT id, first_name, last_name, email, passwordhash FROM Customer WHERE email = ?"
        );
        $stmt->bind_param("s", $email);

        if (!$stmt->execute()) {
            throw new DatabaseError("Error executing statement");
        }
        $stmt->store_result();

        // Check if email exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $first_name, $last_name, $email, $passwordhash);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $passwordhash)) {
                // Password is correct, start a new session
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;
                $_SESSION["email"] = $email;

                // Redirect to index page
                header("Location: index.php");
                //echo '<div class="alert alert-success" role="alert">User logtin successfully</div>';
            } else {
                // Display an error message if password is not valid
                throw new UnauthorizedError("Invalid password", "The password or email you entered was not valid.");
            }
        } else {
            // Display an error message if email doesn't exist
            throw new UnauthorizedError("Invalid email", "The password or email you entered was not valid.");
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } catch (WebShopErrorHandler $e) {
        $error_message = handleError($e);
        echo "<div class='alert alert-danger' role='alert'>$error_message</div>";
    }
}
?>
    <div class="container mt-5 login-container content">
        <div class="p-5 mb-4 rounded-3 border border-2">
            <h2 class="mb-4">Login</h2>
            <!-- novalidation necessary because bootstrap already validates the form -->
            <form action="login.php" method="post" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <div class="text-center mt-3">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
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
    <script src="script/themeToggle.js"></script>
</body>
</html>
