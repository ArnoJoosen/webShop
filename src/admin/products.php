<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/config.php';
    // function to display products in a table
    function displayProducts() {
        $conn = connectToDatabase();
        $sql = "SELECT p.*, c.name as category_name FROM Product p
            LEFT JOIN Category c ON p.category_id = c.id";
        $result = $conn->query($sql);
        $conn->close();

        while($row = $result->fetch_assoc()) {
            ?><tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><img src='<?php echo htmlspecialchars($row['imagePath']); ?>' alt='<?php echo htmlspecialchars($row['name']); ?>' style='width: 50px;'></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars(number_format($row['price'], 2)); ?>€</td>
                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                <td><?php echo htmlspecialchars($row['available'] ? 'Available' : 'Not Available'); ?></td>
                <td>
                    <button type='button' class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editModal<?php echo htmlspecialchars($row['id']); ?>'><i class='fas fa-edit'></i></button>
                    <button onclick='toggleAvailability(<?php echo htmlspecialchars($row['id']); ?>)' class='btn btn-sm btn-warning'><i class='fas fa-power-off'></i></button>
                </td>
            </tr>

            <!-- Modal for each product -->
            <div class='modal fade' id='editModal<?php echo htmlspecialchars($row['id']); ?>' tabindex='-1' aria-labelledby='editModalLabel<?php echo htmlspecialchars($row['id']); ?>' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='editModalLabel<?php echo htmlspecialchars($row['id']); ?>'>Edit Product: <?php echo htmlspecialchars($row['name']); ?></h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <!-- novalidation necessary because bootstrap already validates the form -->
                            <form id="editProduct" enctype="multipart/form-data" onsubmit="return editProduct(event)">
                                <input type='hidden' name='id' value='<?php echo htmlspecialchars($row['id']); ?>'>
                                <div class='mb-3'>
                                    <label for='name<?php echo htmlspecialchars($row['id']); ?>' class='form-label'>Product Name</label>
                                    <input type='text' class='form-control' id='name<?php echo htmlspecialchars($row['id']); ?>' name='name' value='<?php echo htmlspecialchars($row['name']); ?>' required>
                                </div>
                                <div class='mb-3'>
                                    <label for='price<?php echo htmlspecialchars($row['id']); ?>' class='form-label'>Price</label>
                                    <input type='number' step='0.01' class='form-control' id='price<?php echo htmlspecialchars($row['id']); ?>' name='price' value='<?php echo htmlspecialchars($row['price']); ?>' required>
                                </div>
                                <div class='mb-3'>
                                    <label for='description<?php echo htmlspecialchars($row['id']); ?>' class='form-label'>Description</label>
                                    <textarea class='form-control' id='description<?php echo htmlspecialchars($row['id']); ?>' name='description' rows='3' required><?php echo htmlspecialchars($row['description']); ?></textarea>
                                </div>
                                <div class='mb-3'>
                                    <label for='manufacturer<?php echo htmlspecialchars($row['id']); ?>' class='form-label'>Manufacturer</label>
                                    <input type='text' class='form-control' id='manufacturer<?php echo htmlspecialchars($row['id']); ?>' name='manufacturer' value='<?php echo htmlspecialchars($row['manufacturer']); ?>' required>
                                </div>
                                <div class='mb-3'>
                                    <label for='stock<?php echo htmlspecialchars($row['id']); ?>' class='form-label'>Stock</label>
                                    <input type='number' class='form-control' id='stock<?php echo htmlspecialchars($row['id']); ?>' name='stock' value='<?php echo htmlspecialchars($row['stock']); ?>' required>
                                </div>
                                <div class='mb-3'>
                                    <label for='category<?php echo htmlspecialchars($row['id']); ?>' class='form-label'>Category</label>
                                    <select class='form-select' id='category<?php echo htmlspecialchars($row['id']); ?>' name='category_id' required>
                                        <?php
                                            $conn = connectToDatabase();
                                            $sql = "SELECT id, name FROM Category";
                                            $categoryResult = $conn->query($sql);
                                            $conn->close();
                                            while($category = $categoryResult->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($category['id']) . "'" .
                                                    ($category['id'] == $row['category_id'] ? ' selected' : '') .
                                                    ">" . htmlspecialchars($category['name']) . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <button type='submit' class='btn btn-primary'>Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><?php
        }
    }
    session_start();
    if (!isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] !== true) {
        header("Location: login.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        header('Content-Type: application/json');

        if (isset($_POST["action"])) {
            switch($_POST["action"]) {
                case "add":
                    if(isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description']) &&
                       isset($_POST['manufacturer']) && isset($_POST['stock']) && isset($_POST['category_id']) &&
                       isset($_FILES['image'])) {

                        $targetDir = "/uploads/products/";
                        $fileName = basename($_FILES["image"]["name"]);
                        $targetFilePath = $targetDir . time() . '_' . $fileName;
                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $targetDir)) {
                            echo json_encode(['success' => false, 'error' => 'Upload directory does not exist']);
                            exit;
                        }

                        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
                        if(in_array(strtolower($fileType), $allowTypes)) {
                            if(move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $targetFilePath)) {
                                $conn = connectToDatabase();
                                $stmt = $conn->prepare("INSERT INTO Product (name, description, price, manufacturer, stock, category_id, available, imagePath) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                                $name = $_POST['name'];
                                $description = $_POST['description'];
                                $price = $_POST['price'];
                                $manufacturer = $_POST['manufacturer'];
                                $stock = $_POST['stock'];
                                $category_id = $_POST['category_id'];
                                $available = isset($_POST['available']) ? 1 : 0;

                                $stmt->bind_param("ssdsiiis", $name, $description, $price, $manufacturer, $stock, $category_id, $available, $targetFilePath);

                                if($stmt->execute()) {
                                    ob_start();
                                    displayProducts();
                                    echo json_encode(['success' => true, 'tableContent' => ob_get_clean()]);
                                } else {
                                    echo json_encode(['success' => false, 'error' => 'Database error']);
                                }
                                $stmt->close();
                                $conn->close();
                            } else {
                                echo json_encode(['success' => false, 'error' => 'Failed to upload image']);
                            }
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Invalid file type']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                    }
                    break;

                case "edit":
                    if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description']) &&
                       isset($_POST['manufacturer']) && isset($_POST['stock']) && isset($_POST['category_id'])) {
                        $conn = connectToDatabase();
                        $stmt = $conn->prepare("UPDATE Product SET name=?, description=?, price=?, manufacturer=?, stock=?, category_id=? WHERE id=?");

                        $stmt->bind_param("ssdsiii", $_POST['name'], $_POST['description'], $_POST['price'],
                                        $_POST['manufacturer'], $_POST['stock'], $_POST['category_id'], $_POST['id']);

                        if($stmt->execute()) {
                            ob_start();
                            displayProducts();
                            echo json_encode(['success' => true, 'tableContent' => ob_get_clean()]);
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Database error']);
                        }
                        $stmt->close();
                        $conn->close();
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                    }
                    break;

                case "toggleAvailability":
                    if(isset($_POST['id'])) {
                        $conn = connectToDatabase();
                        $stmt = $conn->prepare("UPDATE Product SET available = !available WHERE id=?");
                        $stmt->bind_param("i", $_POST['id']);

                        if($stmt->execute()) {
                            ob_start();
                            displayProducts($conn);
                            echo json_encode(['success' => true, 'tableContent' => ob_get_clean()]);
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Database error']);
                        }
                        $stmt->close();
                        $conn->close();
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                    }
                    break;
            }
        }
        exit;
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
    <script src="script/products.js"></script>
</head>
<body>
    <?php include "pageElements/navBar.php"; ?>
    <!-- Main content -->
    <div id="errorBox" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <span id="errorMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="container mt-4">
        <div class="container">
            <!-- Add Product Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New Product</h5>
                </div>
                <div class="card-body">
                    <!-- novalidation necessary because bootstrap already validates the form -->
                    <form id="addProductForm" enctype="multipart/form-data" onsubmit="return addItem(event)">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="manufacturer" class="form-label">Manufacturer</label>
                                <input type="text" class="form-control" id="manufacturer" name="manufacturer" required>
                            </div>
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category_id" required>
                                    <?php
                                        $conn = connectToDatabase();
                                        $sql = "SELECT id, name FROM Category";
                                        $result = $conn->query($sql);
                                        $conn->close();
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="available" name="available" checked>
                                <label class="form-check-label" for="available">
                                    Available for Sale
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>

            <!-- Product List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Product List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ajax">
                                <?php displayProducts(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
    <script src="../script/themeToggle.js"></script>
</body>
</html>
