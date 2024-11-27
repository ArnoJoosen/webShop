<?php include "core/cards.php"; ?>
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
    <!-- Navigation -->
    <?php include "core/navBar.php"; ?>

    <!-- Main content -->
    <div class="container mt-4">
        <div class="d-flex flex-wrap justify-content-center gap-4">
        <?php
        $dbservername = "db";
        $dbusername = "webuser"; // TOD change to env variable (security risk)
        $dbpassword = "webpassword"; // TOD change to env variable (security risk)
        $database = "webshop";

        // Create connection
        $conn = new mysqli($dbservername, $dbusername, $dbpassword, $database);

        // check if category is set in url and get all products from category or subcategory
        if (isset($_GET["category"])) {
            $category = $_GET["category"];
            // get all products in category and subcategories and all subcategories of subcategories
            $sql = "WITH RECURSIVE CategoryHierarchy AS (
                        SELECT id, name
                        FROM Category
                        WHERE id = $category

                        UNION ALL

                        SELECT c.id, c.name
                        FROM Category c
                        INNER JOIN Categorys cs ON c.id = cs.sub_category_id
                        INNER JOIN CategoryHierarchy ch ON cs.main_category_id = ch.id
                    )
                    SELECT DISTINCT p.*, ch.name AS category_name
                    FROM Product p
                    INNER JOIN CategoryHierarchy ch ON p.category_id = ch.id;"; // TODO remove SQL injection risk
        } else {
            // get all products
            $sql = "SELECT * FROM Product";
        }
        $result = $conn->query($sql); // TODO split up in pages
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                renderProductCard(
                    $row["name"],
                    $row["description"],
                    $row["price"],
                    $row["id"],
                    564
                );
            }
        } else {
            echo "<div class='alert alert-warning'>No products found</div>";
        }
        ?>
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
