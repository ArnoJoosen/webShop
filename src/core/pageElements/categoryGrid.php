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
