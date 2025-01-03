<?php
require_once __DIR__ . "/../core/error_handler.php";
require_once __DIR__ . "/../core/config.php";
session_start();
try {
    if (
        !isset($_SESSION["admin_loggedin"]) &&
        $_SESSION["admin_loggedin"] !== true &&
        $_SESSION["admin_role"] !== "superAdmin"
    ) {
        throw new AdminError("user tried to access admin page without being logged in as admin");
    }
} catch (AdminError $e) {
    logError($e);
    header("Location: login.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    header('Content-Type: application/json');
    try {
        if ($_POST["action"] == "add" && !empty($_POST["action"]) &&
            isset($_POST["firstName"]) && !empty($_POST["firstName"]) &&
            isset($_POST["lastName"]) && !empty($_POST["lastName"]) &&
            isset($_POST["username"]) && !empty($_POST["username"]) &&
            isset($_POST["password"]) && !empty($_POST["password"]) &&
            isset($_POST["superAdmin"]) && !empty($_POST["superAdmin"])
        ) {
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            if (empty($password) && strlen($password) < 8) {
                throw new InputValidationException(
                    "Password must be at least 8 characters long",
                    "Password must be at least 8 characters long"
                );
            }
            if (!preg_match('/\d/', $password)) {
                throw new InputValidationException(
                    "Password must contain at least one number",
                    "Password must contain at least one number"
                );
            }
            if (!preg_match('/[A-Z]/', $password)) {
                throw new InputValidationException(
                    "Password must contain at least one uppercase letter",
                    "Password must contain at least one uppercase letter"
                );
            }
            $password = password_hash($password, PASSWORD_DEFAULT);
            $role = $_POST["superAdmin"] == "true" ? "superAdmin" : "admin";

            $conn = connectToDatabase();
            $stmt = $conn->prepare(
                "INSERT INTO Admins (first_name, last_name, username, passwordhash, role) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "sssss",
                $firstName,
                $lastName,
                $username,
                $password,
                $role
            );
            // Execute the statement
            if (!$stmt->execute()) {
                throw new DatabaseError("Database error occured", "We're sorry, something went wrong. Please try again later.");
            }
            $stmt->close();
            $conn->close();
        } elseif ($_POST["action"] == "delete" && isset($_POST["id"]) && !empty($_POST["id"]) && is_numeric($_POST["id"]) && $_POST["id"] > 0) {
            $conn = connectToDatabase();
            $stmt = $conn->prepare("DELETE FROM Admins WHERE id = ?");
            $stmt->bind_param("i", $_POST["id"]);
            // Execute the statement
            if ($stmt->execute() != true) {
                throw new DatabaseError("Database error occured", "We're sorry, something went wrong. Please try again later.");
            }
            $stmt->close();
            $conn->close();
        }
            ob_start();
            ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = connectToDatabase();
                        $sql =
                            "SELECT * FROM Admins ORDER BY last_name, first_name";
                        $result = $conn->query($sql);
                        if (!$result) {
                            throw new DatabaseError("Database error occured", "We're sorry, something went wrong. Please try again later.");
                        }
                        $conn->close();

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" .
                                htmlspecialchars($row["username"]) .
                                "</td>";
                            echo "<td>" .
                                htmlspecialchars($row["first_name"]) .
                                "</td>";
                            echo "<td>" .
                                htmlspecialchars($row["last_name"]) .
                                "</td>";
                            echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                            echo "<td>";
                            echo "<button class='btn btn-danger btn-sm delete-admin' onclick='onDelete(" .
                                $row["id"] .
                                ")'><i class='fas fa-trash'></i></button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            echo json_encode(['success' => true, 'content' => ob_get_clean()]);
            exit();
    } catch (Exception $e) {
        handleError($e);
        echo json_encode(['success' => false, 'error' => 'An error occured']);
        exit();
    }
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
    <script src="script/admins.js"> </script>
</head>
<body>
    <?php include "pageElements/navBar.php"; ?>
    <div id="errorBox" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <span id="errorMessage"></span>
    </div>
    <!-- Main content -->
    <div class="container mt-4">
                <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Create New Admin</h5>
            </div>
            <div class="card-body">
                <form id="adminForm" onsubmit="submitForm(event)">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="superAdmin" name="superAdmin">
                                <label class="form-check-label" for="superAdmin">
                                    Super Admin
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Admin</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Admin List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" id="ajax">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $conn = connectToDatabase();
                                $sql =
                                    "SELECT * FROM Admins ORDER BY last_name, first_name";
                                $result = $conn->query($sql);
                                $conn->close();

                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" .
                                        htmlspecialchars($row["username"]) .
                                        "</td>";
                                    echo "<td>" .
                                        htmlspecialchars($row["first_name"]) .
                                        "</td>";
                                    echo "<td>" .
                                        htmlspecialchars($row["last_name"]) .
                                        "</td>";
                                    echo "<td>" .
                                        htmlspecialchars($row["role"]) .
                                        "</td>";
                                    echo "<td>";
                                    echo "<button class='btn btn-danger btn-sm delete-admin' onclick='onDelete(" .
                                        $row["id"] .
                                        ")'><i class='fas fa-trash'></i></button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } catch (Exception $e) {
                                    handleError($e);
                                    echo '<div class="alert alert-danger" role="alert">We\'re sorry, something went wrong. Please try again later.</div>';
                            }
                            ?>
                        </tbody>
                    </table>
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
