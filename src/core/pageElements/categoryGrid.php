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
    require_once __DIR__  . '/../config.php';
    require_once __DIR__ . '/../error_handler.php';
    try {
        $conn = connectToDatabase();

        // get categorie from url parameter if exists
        if (isset($_GET["category"])) {
            $category = $_GET["category"];
            // get all subcategories from main category
            $sql = "SELECT * FROM Category WHERE id IN (SELECT sub_category_id FROM Categorys WHERE main_category_id = $category)"; // TDO: prevent sql injection
        } else {
            $sql = "SELECT * FROM Category WHERE id NOT IN (SELECT sub_category_id FROM Categorys)";
        }

        $result = $conn->query($sql);
        if (!$result) {
            throw new DateError("Failed to get categories", "We're sorry, something went wrong. Please try again later.");
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imageUrl = $row["id"]
                ? "https://picsum.photos/id/{$row["id"]}/300/200"
                : "https://picsum.photos/300/200";

                // Check if category has subcategories
                $subCatQuery = "SELECT COUNT(*) as count FROM Categorys WHERE main_category_id = {$row["id"]}";
                $subCatResult = $conn->query($subCatQuery);
                if (!$subCatResult) {
                    throw new DateError("Failed to get subcategories", "We're sorry, something went wrong. Please try again later.");
                }
                $hasSubCategories = $subCatResult->fetch_assoc()["count"] > 0;

                $clickUrl = $hasSubCategories ? "?category=" . $row["id"] : "products.php?category=" . $row["id"]; ?>

                <?php if ($hasSubCategories): ?>
                <div class="card text-decoration-none" style="width: 18rem;" onclick="onCategoryClick('<?php echo $clickUrl; ?>')">
                <?php else: ?>
                <a href="<?php echo $clickUrl; ?>" class="card text-decoration-none" style="width: 18rem;">
                <?php endif; ?>
                    <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row["name"]); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row["name"]); ?></h5>
                    </div>
                <?php if ($hasSubCategories): ?>
                </div>
                <?php else: ?>
                </a>
                <?php endif; ?><?php
            }
        } else {
            echo "No category where found";
        }

        $conn->close();
    } catch (Exception $e) {
        $error_message = handleError($e);
        echo "<h2>Something went wrong</h2>";
    }
    ?>
</div>
