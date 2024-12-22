<?php
    require_once __DIR__ . '/error_handler.php';
    // to include the config file use "require_once __DIR__ . '/core/config.php';"
    function connectToDatabase() {
        $DB_SERVER = 'db';
        $DB_USERNAME = 'webuser';
        $DB_PASSWORD = 'webpassword';
        $DB_NAME = 'webshop';
        // Attempt to connect to MySQL database
        $conn = new mysqli($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            throw new DatabaseError("Connection failed to databace", "We're sorry, something went wrong. Please try again later.");
        }

        return $conn;
    }
?>
