<?php
function renderProductCard($name, $description, $price, $productId, $imageUrl = null) {?>
    <div class="card" style="width: 18rem;" onclick="window.location.href='/product.php?id=<?php echo htmlspecialchars($productId); ?>'" style="cursor: pointer;">
        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>" width="286" height="150" style="object-fit: cover; object-position: top;">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars(
                $description
            ); ?></p>
            <p class="card-text"><strong>Price: <?php echo number_format(
                $price,
                2
            ); ?>€</strong></p>
            <form method="post" action="shoppingCart.php">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>
    <?php
}

$dbservername = "db";
$dbusername = "webuser"; // TOD change to env variable (security risk)
$dbpassword = "webpassword"; // TOD change to env variable (security risk)
$database = "webshop";

// Create connection
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $database);

// check if category is set in url and get all products from category or subcategory
if (isset($_GET["category"])) {
    $category = intval($_GET["category"]); // Sanitize category input
    // get all products in category and subcategories and all subcategories of subcategories
    $sql = "WITH RECURSIVE CategoryHierarchy AS (
                SELECT id, name
                FROM Category
                WHERE id = ?

                UNION ALL

                SELECT c.id, c.name
                FROM Category c
                INNER JOIN Categorys cs ON c.id = cs.sub_category_id
                INNER JOIN CategoryHierarchy ch ON cs.main_category_id = ch.id
            )
            SELECT DISTINCT p.*, ch.name AS category_name
            FROM Product p
            INNER JOIN CategoryHierarchy ch ON p.category_id = ch.id
            WHERE p.available = true;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category);
    $result = $stmt->execute();
    $result = $stmt->get_result();

} elseif (isset($_GET["search"]) && $_GET["search"] != "") {
    $search = $conn->real_escape_string($_GET["search"]);
    // get all products that contain search string
    $sql = "SELECT * FROM Product WHERE (name LIKE ? OR description LIKE ?) AND available";
    $stmt = $conn->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $result = $stmt->execute();
    $result = $stmt->get_result();
}
else {
    // get all products
    $sql = "SELECT * FROM Product WHERE available";
    $result = $conn->query($sql);
}

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        renderProductCard(
            $row["name"],
            $row["description"],
            $row["price"],
            $row["id"],
            $row["imagePath"]
        );
    }
} else {
    echo "<div class='alert alert-warning'>No products found</div>";
}
?>
