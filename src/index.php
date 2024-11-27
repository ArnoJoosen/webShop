<?php include "cards.php"; ?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webshop Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
</head>
<body>
    <?php include "navBar.php"; ?>
    <!-- Main content -->
    <div class="container mt-4">
        <div class="content-wrapper">
            <h1>Welcome to Our Webshop!</h1>
            <hr>
            <div class="text-center mb-3">
                <div class="d-flex align-items-center justify-content-center">
                    <p class="mb-0 me-3">Browse products by category</p>
                    <a href="<?php echo isset($_GET["category"])
                        ? "products.php?category=" . $_GET["category"]
                        : "products.php"; ?>" class="link">View Products</a>
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-4">
                <?php
                $servername = "db";
                $username = "webuser"; // TOD change to env variable (security risk)
                $password = "webpassword"; // TOD change to env variable (security risk)
                $database = "webshop";

                // Create connection
                $conn = new mysqli(
                    $servername,
                    $username,
                    $password,
                    $database
                );

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error); // TODO: remove (security risk)
                }

                // get categorie from url parameter if exists
                if (isset($_GET["category"])) {
                    $category = $_GET["category"];
                    // get all subcategories from main category
                    $sql = "SELECT * FROM Category WHERE id IN (SELECT sub_category_id FROM Categorys WHERE main_category_id = $category)"; // TDO: prevent sql injection
                } else {
                    $sql =
                        "SELECT * FROM Category WHERE id NOT IN (SELECT sub_category_id FROM Categorys)";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        renderCategoryCard(
                            $row["name"],
                            "?category=" . $row["id"],
                            $row["id"]
                        );
                    }
                } else {
                    if (isset($_GET["category"])) {
                        $category = $_GET["category"];
                        header("Location: products.php?category=" . $category);
                    } else {
                        echo "No category where found";
                    }
                }

                $conn->close();
                ?>
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
    <script src="script/themeToggle.js"></script>
</body>
</html>
