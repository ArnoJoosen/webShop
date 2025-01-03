<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../error_handler.php';
try {
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

        $conn = connectToDatabase();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $category);
        $result = $stmt->execute();
        if (!$result) {
            $stmt->close();
            $conn->close();
            throw new DatabaseError("Failed to get products from category", "We're sorry, something went wrong. Please try again later.");
        }
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();

    } elseif (isset($_GET["search"]) && $_GET["search"] != "") {
        $conn = connectToDatabase();
        $search = $conn->real_escape_string($_GET["search"]);
        // get all products that contain search string
        $sql = "SELECT * FROM Product WHERE (name LIKE ? OR description LIKE ?) AND available";
        $stmt = $conn->prepare($sql);
        $searchParam = "%$search%";
        $stmt->bind_param("ss", $searchParam, $searchParam);
        $result = $stmt->execute();
        if (!$result) {
            $stmt->close();
            $conn->close();
            throw new DatabaseError("Failed to get products from search", "We're sorry, something went wrong. Please try again later.");
        }
        $result = $stmt->get_result();
        $stmt->close();
    }
    else {
        // get all products
        $conn = connectToDatabase();
        $sql = "SELECT * FROM Product WHERE available";
        $result = $conn->query($sql);
        if (!$result) {
            $conn->close();
            throw new DatabaseError("Failed to get products", "We're sorry, something went wrong. Please try again later.");
        }
        $conn->close();
    }

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="card" style="width: 18rem;" onclick="window.location.href='./product.php?id=<?php echo htmlspecialchars($row["id"]); ?>'" style="cursor: pointer;">
                <img src="./<?php echo $row["imagePath"]; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row["name"]); ?>" width="286" height="150" style="object-fit: cover; object-position: top;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row["name"]); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($row["description"]); ?></p>
                    <p class="card-text"><strong>Price: <?php echo number_format($row["price"], 2); ?>€</strong></p>
                    <?php if ($row["stock"] > 0): ?>
                        <form method="post" action="shoppingCart.php">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row["id"]); ?>">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($row["name"]); ?>">
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled> Out of Stock </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='alert alert-warning'>No products found</div>";
    }
} catch (Exception $e) {
    $error_message = handleError($e);
    echo "<h2>" . $error_message . "</h2>";
}
?>
