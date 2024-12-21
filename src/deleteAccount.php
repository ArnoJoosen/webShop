<?php
include_once "core/config.php";
include_once "core/error_handler.php";

try {
    session_start();
    if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
        throw new UnauthorizedError("attempt to access shopping cart without logging in", "Please log in to access the shopping cart");
    }
} catch (WebShopErrorHandler $e) {
    $error_message = handleError($e);
    header("Location: login.php");
    exit();
}

try {
    $conn = connectToDatabase();
    $sql = "UPDATE Customer SET deleted = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["id"]);
    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        header("Location: login.php");
    } else {
        throw new DatabaseError("Error: " . $stmt->error, "We're sorry, something went wrong. Please try again later.");
    }
} catch (Exception $e) {
    $userMessage = handleError($e);
    echo '<div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 0, 0, 0.9); color: white; display: flex; justify-content: center; align-items: center; font-size: 24px; text-align: center; z-index: 9999;">' . $userMessage . '</div>';
}
?>
