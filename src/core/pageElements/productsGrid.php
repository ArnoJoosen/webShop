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
} elseif (isset($_GET["search"]) && $_GET["search"] != "") {
    $search = $_GET["search"];
    // get all products that contain search string
    $sql = "SELECT * FROM Product WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
}
else {
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
