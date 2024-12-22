<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
require_once __DIR__ . "/../core/error_handler.php";
require_once __DIR__ . '/../core/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['username']) && !empty($_POST['username'])
    && isset($_POST['password']) && !empty($_POST['password'])) {
    try {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $conn = connectToDatabase();
        $stmt = $conn->prepare("SELECT * FROM Admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        if (!$stmt->execute()) {
            throw new DatabaseError("Error: " . $conn->error, "We're sorry, something went wrong. Please try again later.");
        }
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        if ($admin && password_verify($password, $admin['passwordhash'])) {
            $_SESSION["admin_loggedin"] = true;
            $_SESSION["admin_username"] = $admin['username'];
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_role'] = $admin['role'];
            header("Location: /admin/admin.php");
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Invalid username or password</div>';
        }
    } catch (Exception $e) {
        $userMessage = handleError($e);
        echo '<div class="alert alert-danger" role="alert">' . $userMessage . '</div>';
    }
}
?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-center mb-0">Admin</h1>
                    </div>
                    <div class="card-body">
                        <!-- novalidation necessary because bootstrap already validates the form -->
                        <form action="" method="post">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                            <div class="d-grid">
                                <input type="submit" class="btn btn-primary" value="Login">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../script/themeToggle.js"></script>
</body>
