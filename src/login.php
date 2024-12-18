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
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
</head>
<body>
    <!-- Navigation -->
    <?php include "core/pageElements/navBar.php"; ?>

    <!-- Main content -->
<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
    require_once __DIR__ . '/core/config.php';
    $conn = connectToDatabase();

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and bind
    $stmt = $conn->prepare(
        "SELECT id, first_name, last_name, email, passwordhash FROM Customer WHERE email = ?"
    );
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();
    if ($stmt->errno) {
        echo "Error executing query: " . $stmt->error;
        exit();
    }

    // Store the result
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
            echo "<div class='alert alert-danger' role='alert'>The password or email you entered was not valid.</div>";
        }
    } else {
        // Display an error message if email doesn't exist
        echo "<div class='alert alert-danger' role='alert'>The password or email you entered was not valid.</div>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
    <div class="container mt-5 login-container content">
        <div class="p-5 mb-4 rounded-3 border border-2">
            <h2 class="mb-4">Login</h2>
            <!-- novalidation necessary because bootstrap already validates the form -->
            <form action="login.php" method="post">
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
