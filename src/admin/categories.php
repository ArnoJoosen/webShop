<?php
    require_once __DIR__ . '/../core/config.php';
    function displayCategories() {
        $conn = connectToDatabase();
        $sql = "SELECT c1.id, c1.name, c1.imagePath, c2.name as parent_name
                FROM Category c1
                LEFT JOIN Categorys cat ON c1.id = cat.sub_category_id
                LEFT JOIN Category c2 ON cat.main_category_id = c2.id";
        $result = $conn->query($sql);
        $conn->close();

        while($row = $result->fetch_assoc()) {
            ?><tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><img src="<?php echo htmlspecialchars($row['imagePath']); ?>" alt="Category Image" style="max-width: 50px;"></td>
                <td><?php echo ($row['parent_name'] ? htmlspecialchars($row['parent_name']) : 'None'); ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo htmlspecialchars($row['id']); ?>">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?php echo htmlspecialchars($row['id']); ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?php echo htmlspecialchars($row['id']); ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- novalidation necessary because bootstrap already validates the form -->
                            <form id="editCategoryForm<?php echo htmlspecialchars($row['id']); ?>" onsubmit="return editCategory(event, <?php echo htmlspecialchars($row['id']); ?>)">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Parent Category</label>
                                    <select class="form-control" name="parent_category">
                                        <option value="">None (Main Category)</option>
                                        <?php
                                            $conn = connectToDatabase();
                                            $stmt = $conn->prepare("SELECT id, name FROM Category WHERE id != ?");
                                            $stmt->bind_param("i", $row['id']);
                                            $stmt->execute();
                                            $categoryResult = $stmt->get_result();
                                            $stmt->close();
                                            $conn->close();
                                            while($category = $categoryResult->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($category['id']) . '"' .
                                                    ($category['id'] == $row['parent_name'] ? ' selected' : '') . '>' .
                                                    htmlspecialchars($category['name']) . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Image (Optional)</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
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

        if (isset($_POST["action"]) && !empty($_POST["action"])) {
            switch($_POST["action"]) {
                case "add":
                    if(isset($_POST['name']) && isset($_FILES['image'])
                            && isset($_POST['parent_category']) && is_numeric($_POST['parent_category'])
                            && isset($_POST['name']) && !empty($_POST['name'])) {

                        $targetDir = "/uploads/categories/";
                        $fileName = time() . '_' . basename($_FILES["image"]["name"]);
                        $targetFilePath = $targetDir . $fileName;

                        // Check if folder exists
                        if (!is_dir(__DIR__ . "/../" . $targetDir)) {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'error' => 'Upload directory does not exist']);
                            exit;
                        }

                        if(move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__ . "/../" . $targetFilePath)) {
                            $conn = connectToDatabase();
                            $stmt = $conn->prepare("INSERT INTO Category (name, imagePath) VALUES (?, ?)");
                            $stmt->bind_param("ss", $_POST['name'], $targetFilePath);

                            if($stmt->execute()) {
                                $categoryId = $stmt->insert_id;

                                if(!empty($_POST['parent_category'])) {
                                    $stmt2 = $conn->prepare("INSERT INTO Categorys (main_category_id, sub_category_id) VALUES (?, ?)");
                                    $stmt2->bind_param("ii", $_POST['parent_category'], $categoryId);
                                    $stmt2->execute();
                                    $stmt2->close();
                                }

                                ob_start();
                                displayCategories();
                                echo json_encode(['success' => true, 'content' => ob_get_clean()]);
                            } else {
                                echo json_encode(['success' => false, 'error' => 'Database error']);
                            }
                            $stmt->close();
                            $conn->close();
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Failed to upload image']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                    }
                    break;

                case "edit":
                    if(isset($_POST['id']) && is_numeric($_POST['id'])
                            && isset($_POST['name']) && !empty($_POST['name'])
                            && isset($_POST['parent_category']) && is_numeric($_POST['parent_category'])) {
                        $updateImage = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK;

                        if($updateImage) {
                            $targetDir = "/uploads/categories/";
                            $fileName = time() . '_' . basename($_FILES["image"]["name"]);
                            $targetFilePath = $targetDir . $fileName;
                            move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__ . "/../" . $targetFilePath);

                            $conn = connectToDatabase();
                            $stmt = $conn->prepare("UPDATE Category SET name = ?, imagePath = ? WHERE id = ?");
                            $stmt->bind_param("ssi", $_POST['name'], $targetFilePath, $_POST['id']);
                        } else {
                            $conn = connectToDatabase();
                            $stmt = $conn->prepare("UPDATE Category SET name = ? WHERE id = ?");
                            $stmt->bind_param("si", $_POST['name'], $_POST['id']);
                        }

                        if($stmt->execute()) {
                            if(isset($_POST['parent_category'])) {
                                $stmt = $conn->prepare("REPLACE INTO Categorys (main_category_id, sub_category_id) VALUES (?, ?)");
                                $stmt->bind_param("ii", $_POST['parent_category'], $_POST['id']);
                                $stmt->execute();
                            }

                            ob_start();
                            displayCategories();
                            echo json_encode(['success' => true, 'content' => ob_get_clean()]);
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Database error']);
                        }
                        $stmt->close();
                        $conn->close();
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                    }
                    break;

                case "delete":
                    if(isset($_POST['id']) && is_numeric($_POST['id'])) {
                        // Check for dependent products
                        $conn = connectToDatabase();
                        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM Product WHERE category_id = ?");
                        $stmt->bind_param("i", $_POST['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $stmt->close();

                        if($row['count'] > 0) {
                            echo json_encode(['success' => false, 'error' => 'Cannot delete: Category has products']);
                            exit;
                        }

                        // Check for dependent categories
                        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM Categorys WHERE main_category_id = ?");
                        $stmt->bind_param("i", $_POST['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $stmt->close();

                        if($row['count'] > 0) {
                            echo json_encode(['success' => false, 'error' => 'Cannot delete: Category has subcategories']);
                            exit;
                        }

                        $stmt = $conn->prepare("DELETE FROM Category WHERE id = ?");
                        $stmt->bind_param("i", $_POST['id']);

                        if($stmt->execute()) {
                            ob_start();
                            displayCategories($conn);
                            echo json_encode(['success' => true, 'content' => ob_get_clean()]);
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
    <script src="script/categories.js"></script>
</head>
<body>
    <?php include "pageElements/navBar.php"; ?>
    <div id="errorBox" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <span id="errorMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="container mt-4">
        <div class="container">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New Category</h5>
                </div>
                <div class="card-body">
                    <!-- novalidation necessary because bootstrap already validates the form -->
                    <form onsubmit="return addCategory(event)" enctype="multipart/form-data" class="mb-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="parent_category" class="form-label">Parent Category (Optional)</label>
                                <select class="form-control" id="parent_category" name="parent_category">
                                    <option value="">None (Main Category)</option>
                                    <?php
                                        $conn = connectToDatabase();
                                        $sql = "SELECT id, name FROM Category";
                                        $result = $conn->query($sql);
                                        $conn->close();
                                        while($row = $result->fetch_assoc()) {
                                            echo '<option value="' . htmlspecialchars($row['id']) . '">' .
                                                htmlspecialchars($row['name']) . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Category List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Parent Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="categoryList">
                                <?php displayCategories(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 theme">
        <div class="container">
            <span class="text-muted">© 2023 Webshop. All rights reserved.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../script/themeToggle.js"></script>
</body>
</html>
