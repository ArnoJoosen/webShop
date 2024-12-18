<?php
    session_start();
    if (!isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] !== true) {
        header("Location: login.php");
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
</head>
<body>
    <?php include "pageElements/navBar.php"; ?>
    <!-- Main content -->
    <div class="container mt-4">
        <h2>Customer Management</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once __DIR__ . '/../core/config.php';
                    $conn = connectToDatabase();
                    $sql = "SELECT * FROM Customer";
                    if($result = mysqli_query($conn, $sql)) {
                        while($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['date_of_birth']; ?></td>
                                <td>
                                    <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#addressModal<?php echo $row['id']; ?>'>
                                    View Address
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal for address details -->
                            <div class='modal fade' id='addressModal<?php echo $row['id']; ?>' tabindex='-1' aria-hidden='true'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title'>Address Details for <?php echo $row['first_name'] . " " . $row['last_name']; ?></h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>

                                        <?php
                                        // Fetch address details
                                        $address_sql = "SELECT * FROM Address WHERE customer_id = " . $row['id'];
                                        $address_result = mysqli_query($conn, $address_sql);
                                        if($address = mysqli_fetch_array($address_result)) {
                                        ?>
                                            <p><strong>Street:</strong> <?php echo $address['street'] . " " . $address['street_number']; ?></p>
                                            <p><strong>City:</strong> <?php echo $address['city']; ?></p>
                                            <p><strong>Postal Code:</strong> <?php echo $address['postal_code']; ?></p>
                                            <p><strong>Country:</strong> <?php echo $address['country']; ?></p>
                                        <?php
                                        } else {
                                        ?>
                                            <p>No address information available.</p>
                                        <?php
                                        }
                                        ?>

                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    $conn->close();
                ?>
                </tbody>
            </table>
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
