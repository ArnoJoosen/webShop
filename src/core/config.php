<?php
    // to include the config file use "require_once $_SERVER['DOCUMENT_ROOT'] . '/core/config.php';"
    function connectToDatabase() {
        $DB_SERVER = 'db';
        $DB_USERNAME = 'webuser';
        $DB_PASSWORD = 'webpassword';
        $DB_NAME = 'webshop';
        // Attempt to connect to MySQL database
        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
?>
