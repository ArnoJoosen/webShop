<?php
class ShoppingCart {
    private $customID;
    private $conn;
    // connect to database
    private $dbservername = "db";
    private $dbusername = "webuser"; // TOD change to env variable (security risk)
    private $dbpassword = "webpassword"; // TOD change to env variable (security risk)
    private $database = "webshop";

    public function __construct($customID) {
        $this->customID = $customID;

        // Create connection
        $this->conn = new mysqli($this->dbservername, $this->dbusername, $this->dbpassword, $this->database);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error); // TOD change to error page (security risk)
        }
    }

    public function __destruct() {
        mysqli_close($this->conn);
    }

    public function addItem($itemId) {
        // Check if product exists in cart
        $checkStmt = $this->conn->prepare("SELECT id, quantity FROM ShoppingCart WHERE customer_id = ? AND product_id = ?");
        $checkStmt->bind_param("ii", $this->customID, $itemId);
        $checkStmt->execute();

        if (!$checkStmt->execute()) {
            die("Error: " . $checkStmt->error); // TODO change to error page (security risk)
        }

        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // If product exists, update quantity
            $row = $checkResult->fetch_assoc();
            $newQuantity = $row["quantity"] + 1;
            $updateStmt = $this->conn->prepare("UPDATE ShoppingCart SET quantity = ? WHERE id = ?");
            $updateStmt->bind_param("ii", $newQuantity, $row["id"]);
            if (!$updateStmt->execute()) {
                die("Error: " . $updateStmt->error); // TODO change to error page (security risk)
            }
        } else {
            // If product doesn't exist, insert new row
            $insertStmt = $this->conn->prepare("INSERT INTO ShoppingCart (customer_id, product_id, quantity) VALUES (?, ?, 1)");
            $insertStmt->bind_param("ii", $this->customID, $itemId);
            if (!$insertStmt->execute()) {
                die("Error: " . $insertStmt->error); // TODO change to error page (security risk)
            }
        }
    }

    public function removeItem($itemId) {
        $deleteStmt = $this->conn->prepare("DELETE FROM ShoppingCart WHERE customer_id = ? AND product_id = ?");
        $deleteStmt->bind_param("ii", $this->customID, $itemId);
        if (!$deleteStmt->execute()) {
            die("Execute failed: " . $deleteStmt->error); // Debugging statement
        }
    }

    public function incrementItem($itemId) {
        $updateStmt = $this->conn->prepare("UPDATE ShoppingCart SET quantity = quantity + 1 WHERE customer_id = ? AND product_id = ?");
        $updateStmt->bind_param("ii", $this->customID, $itemId);
        if (!$updateStmt->execute()) {
            die("Error: " . $updateStmt->error); // TODO change to error page (security risk)
        }
    }

    public function decrementItem($itemId) {
        // Check current quantity
        $checkStmt = $this->conn->prepare("SELECT quantity FROM ShoppingCart WHERE customer_id = ? AND product_id = ?");
        $checkStmt->bind_param("ii", $this->customID, $itemId);
        if (!$checkStmt->execute()) {
            die("Error: " . $checkStmt->error);
        }
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['quantity'] <= 1) {
            // If quantity is 1, remove the item
            $this->removeItem($itemId);
        } else {
            // Otherwise decrement the quantity
            $updateStmt = $this->conn->prepare("UPDATE ShoppingCart SET quantity = quantity - 1 WHERE customer_id = ? AND product_id = ?");
            $updateStmt->bind_param("ii", $this->customID, $itemId);
            if (!$updateStmt->execute()) {
                die("Error: " . $updateStmt->error); // TODO change to error page (security risk)
            }
        }
    }

    public function emptyCart() {
        $deleteStmt = $this->conn->prepare("DELETE FROM ShoppingCart WHERE customer_id = ?");
        $deleteStmt->bind_param("i", $this->customID);
        if (!$deleteStmt->execute()) {
            die("Error: " . $deleteStmt->error); // TODO change to error page (security risk)
        }
    }

    public function displayCart() {
        // Fetch ShoppingCart items for the customer
        $stmt = $this->conn->prepare("SELECT Product.name, Product.price, ShoppingCart.quantity, Product.id FROM ShoppingCart JOIN Product ON ShoppingCart.product_id = Product.id WHERE ShoppingCart.customer_id = ?");
        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display cart items if there are any
        if ($result->num_rows > 0) {
            ?><table class="table mt-4">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th></th> <!-- Empty column for delete button -->
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                while ($row = $result->fetch_assoc()) {
                    $row_total = $row['price'] * $row['quantity'];
                    $total += $row_total;
                    ?>
                    <tr>
                        <td> <?php echo htmlspecialchars($row['name']); ?> </td>
                        <td> <?php echo number_format($row['price'], 2); ?> € </td>
                        <td> <?php echo $row['quantity']; ?> </td>
                        <td> <?php echo number_format($row_total, 2); ?> € </td>
                        <td>
                            <form class="action-icons d-inline me-2" method="post" action="shoppingCart.php" onsubmit="history.replaceState(null, null, document.referrer)">
                                <input type="hidden" name="action" value="decrement">
                                <input type="hidden" name="product_id" value="<?php echo $row["id"]; ?>">
                                <input type="hidden" name="name" value="<?php echo $row["name"]; ?>">
                                <button type="submit" class="btn p-0 border-0"><i class="fas fa-minus"></i></button>
                            </form>
                            <form class="action-icons d-inline me-2" method="post" action="shoppingCart.php" onsubmit="history.replaceState(null, null, document.referrer)">
                                <input type="hidden" name="action" value="increment">
                                <input type="hidden" name="product_id" value="<?php echo $row["id"]; ?>">
                                <input type="hidden" name="name" value="<?php echo $row["name"]; ?>">
                                <button type="submit" class="btn p-0 border-0"><i class="fas fa-plus"></i></button>
                            </form>
                            <form class="action-icons d-inline" method="post" action="shoppingCart.php" onsubmit="history.replaceState(null, null, document.referrer)">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $row["id"]; ?>">
                                <input type="hidden" name="name" value="<?php echo $row["name"]; ?>">
                                <button type="submit" class="btn p-0 border-0"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td><strong> <?php echo number_format($total, 2); ?> € </strong></td>
                    <td></td>
                </tr>
            </tbody>
            </table>
            <?php
        } else {
            echo '<p>Your shopping cart is empty.</p>';
        }
    }
}
?>
