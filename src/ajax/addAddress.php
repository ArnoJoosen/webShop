<?php
require_once __DIR__ . '/../core/config.php';
function addressList() {
    $conn = connectToDatabase();
    $sql = "SELECT * FROM Address WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) { ?>
        <div class="form-group address-list">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="address" id="address<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" required>
                    <label class="form-check-label" for="address<?php echo $row['id']; ?>">
                        <?php echo $row['street'] . ' ' . $row['street_number'] . ', ' . $row['postal_code'] . ' ' . $row['city'] . ', ' . $row['country']; ?>
                    </label>
                </div>
            <?php endwhile; ?>
        </div>
    <?php } else { ?>
        <p class="text-warning">No addresses found. Please add an address first.</p>
    <?php }
}

header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['loggedin'])) {
    echo json_encode(['success' => false, 'message' => 'You need to be logged in to add an address']);
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['street']) && isset($_POST['street_number']) && isset($_POST['city']) && isset($_POST['postal_code']) && isset($_POST['country'])) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("INSERT INTO Address (street, street_number, city, postal_code, country, customer_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssi", $_POST['street'], $_POST['street_number'], $_POST['city'], $_POST['postal_code'], $_POST['country'], $_SESSION['id']);
    $stmt->execute(); // TODO error handling
    $stmt->close();
    $conn->close();
    ob_start();
    addressList();
    echo json_encode(['success' => true, 'content' => ob_get_clean()]);
    exit();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo json_encode(['success' => false, 'message' => 'Please fill out all required fields']);
    exit();
}
?>
