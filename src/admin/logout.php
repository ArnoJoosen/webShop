<?php
// logout the user
session_start();
session_unset();
session_destroy();
header("Location: ./admin.php");
?>
