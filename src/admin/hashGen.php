<!DOCTYPE html>
<html>
<head>
    <title>Generate Password Hash</title>
</head>
<body>
    <form method="POST">
        <input type="password" name="password" placeholder="Enter password">
        <input type="submit" value="Generate Hash">
    </form>

    <?php
    if(isset($_POST['password'])) {
        $password = $_POST['password'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        echo "<p>Password Hash: " . $hash . "</p>";
    }
    ?>
</body>
</html>
