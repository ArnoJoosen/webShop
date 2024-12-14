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

// Check if the request is a POST request and if it's for a review
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    switch ($_POST["action"]) {
        case "addReview":
            if (isset($_POST["comment"]) && isset($_POST["rating"]) && isset($_POST["product_id"])) {
                // Verify the user is logged in
                session_start();
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    header("Location: login.php");
                    exit;
                }
                // Prepare and execute the SQL query to insert the review
                $sql = "INSERT INTO Review (product_id, customer_id, rating, comment) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiis", $_POST["product_id"], $_SESSION["id"], $_POST["rating"], $_POST["comment"]);
                $stmt->execute();
            }
            break;
        case "deleteReview":
            if (isset($_POST["review_id"])) {
                // Verify the user is logged in
                session_start();
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    header("Location: login.php");
                    exit;
                }
                // check if the review belongs to the user
                $review_sql = "SELECT customer_id FROM Review WHERE id = ?";
                $review_stmt = $conn->prepare($review_sql);
                $review_stmt->bind_param("i", $_POST["review_id"]);
                $review_stmt->execute();
                $review_result = $review_stmt->get_result();
                $review = $review_result->fetch_assoc();
                if ($review["customer_id"] == $_SESSION["id"]) {
                    // Prepare and execute the SQL query to delete the review
                    $sql = "DELETE FROM Review WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $_POST["review_id"]);
                    $stmt->execute();
                }
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webshop Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/product.css">
    <!--<link href="css/blue-theme.css" rel="stylesheet">-->
</head>
<body>
    <!-- Navigation -->
    <?php include "core/pageElements/navBar.php"; ?>

    <!-- Main content -->
    <div class="container mt-4">
        <?php
            $id = $_GET["id"];
            $sql = "SELECT * FROM Product WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if (!$product) {
                header("Location: products.php");
                exit;
            }

            // Get reviews for this product
            $review_sql = "SELECT r.*, c.first_name, c.last_name, c.id AS customer_id FROM Review r
                          JOIN Customer c ON r.customer_id = c.id
                          WHERE r.product_id = ?";
            $review_stmt = $conn->prepare($review_sql);
            $review_stmt->bind_param("i", $id);
            $review_stmt->execute();
            $reviews = $review_stmt->get_result();
        ?>

        <div class="card mb-4">
            <div class="row g-0">
                <div class="col-md-6 product-image-container">
                    <img src="<?php echo htmlspecialchars($product["imagePath"]); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($product["name"]); ?>">
                </div>
                <div class="col-md-6">
                    <div class="card-body">
                        <h1 class="card-title"><?php echo htmlspecialchars($product["name"]); ?></h1>
                        <p class="card-text lead">€<?php echo number_format($product["price"], 2); ?></p>
                        <p class="card-text"><?php echo htmlspecialchars($product["description"]); ?></p>

                        <div class="card-text mb-3">
                            <strong>Manufacturer:</strong> <?php echo htmlspecialchars($product["manufacturer"]); ?>
                        </div>

                        <div class="card-text mb-3">
                            <strong>Stock:</strong> <?php echo $product["stock"]; ?> units
                        </div>

                        <?php if ($product["available"] && $product["stock"] > 0): ?>
                        <button class="btn btn-primary btn-lg">
                            Add to Cart
                        </button>
                        <?php else: ?>
                        <button class="btn btn-secondary btn-lg" disabled>
                            Out of Stock
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h2>Reviews</h2>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <!-- novalidation necessary because bootstrap already validates the form -->
                <form class="mb-4" method="POST" action="product.php?id=<?php echo $id; ?>">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5"><label for="star5"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star1" name="rating" value="1" required checked><label for="star1"><i class="fas fa-star"></i></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="action" value="addReview">
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
                <?php else:?>
                <p><a href="login.php">Log in</a> to leave a review.</p>
                <?php endif; ?>

                <?php if ($reviews->num_rows > 0): ?>
                    <?php while($review = $reviews->fetch_assoc()): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></h5>
                            <div class="mb-2">
                                <?php for($i = 0; $i < $review['rating']; $i++): ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php endfor; ?>
                                <?php for($i = $review['rating']; $i < 5; $i++): ?>
                                    <i class="far fa-star text-warning"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="card-text"><?php echo htmlspecialchars($review['comment']); ?></p>
                            <?php if (isset($_SESSION["id"]) && $_SESSION["id"] == $review['customer_id']): ?>
                            <form method="POST" action="product.php?id=<?php echo $id; ?>">
                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                <input type="hidden" name="action" value="deleteReview">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="card-text">No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
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
