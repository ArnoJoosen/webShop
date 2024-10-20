<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "You must be logged in to access this page.";
} else {
    $loggedin = $_SESSION["loggedin"];
    $id = $_SESSION["id"];
    $first_name = $_SESSION["first_name"];
    $last_name = $_SESSION["last_name"];
    $email = $_SESSION["email"];

    echo "Welcome to the member's area, " . $first_name . " " . $last_name . "!";
}
?>
