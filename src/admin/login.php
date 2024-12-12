<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db_servername = "db";
    $db_username = "webuser"; // TOD change to env variable (security risk)
    $db_password = "webpassword"; // TOD change to env variable (security risk)
    $db_database = "webshop";

    // Create connection
    $conn = new mysqli(
        $db_servername,
        $db_username,
        $db_password,
        $db_database
    );

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // TODO: remove (security risk)
    }

    $stmt = $conn->prepare("SELECT * FROM Admins WHERE first_name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['passwordhash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_role'] = $admin['role'];
        header("Location: /admin/admin.php");
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert">Invalid username or password</div>';
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
                        <form action="" method="post">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="username" placeholder="Username">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password">
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
