<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/error_handler.php";
class ShoppingCart {
    private $customID;
    private $conn;

    public function __construct($customID) {
        $this->customID = $customID;

        // Create connection
        $this->conn = connectToDatabase();
    }

    public function __destruct() {
        mysqli_close($this->conn);
    }

    public function getCount() {
        $stmt = $this->conn->prepare(
            "SELECT SUM(quantity) AS total FROM ShoppingCart WHERE customer_id = ?"
        );
        $stmt->bind_param("i", $this->customID);
        if (!$stmt->execute()) {
            throw new DatabaseError("Error: " . $stmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row["total"];
    }

    public function addItem($itemId) {
        // Check if product exists in cart
        $checkStmt = $this->conn->prepare(
            "SELECT id, quantity FROM ShoppingCart WHERE customer_id = ? AND product_id = ?"
        );
        $checkStmt->bind_param("ii", $this->customID, $itemId);
        if(!$checkStmt->execute()) {
            throw new DatabaseError("Error: " . $checkStmt->error, "We're sorry, something went wrong. Please try again later.");
        }

        $checkResult = $checkStmt->get_result();
        $checkStmt->close();

        if ($checkResult->num_rows > 0) {
            // If product exists, update quantity
            $row = $checkResult->fetch_assoc();
            $newQuantity = $row["quantity"] + 1;
            $updateStmt = $this->conn->prepare(
                "UPDATE ShoppingCart SET quantity = ? WHERE id = ?"
            );
            $updateStmt->bind_param("ii", $newQuantity, $row["id"]);
            if (!$updateStmt->execute()) {
                throw new DatabaseError("Error: " . $updateStmt->error, "We're sorry, something went wrong. Please try again later.");
            }
            $updateStmt->close();
        } else {
            // If product doesn't exist, insert new row
            $insertStmt = $this->conn->prepare(
                "INSERT INTO ShoppingCart (customer_id, product_id, quantity) VALUES (?, ?, 1)"
            );
            $insertStmt->bind_param("ii", $this->customID, $itemId);
            if (!$insertStmt->execute()) {
                throw new DatabaseError("Error: " . $insertStmt->error, "We're sorry, something went wrong. Please try again later.");
            }
            $insertStmt->close();
        }
    }

    public function removeItem($itemId) {
        $deleteStmt = $this->conn->prepare(
            "DELETE FROM ShoppingCart WHERE customer_id = ? AND product_id = ?"
        );
        $deleteStmt->bind_param("ii", $this->customID, $itemId);
        if (!$deleteStmt->execute()) {
            throw new DatabaseError("Error: " . $deleteStmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $deleteStmt->close();
    }

    public function incrementItem($itemId) {
        $updateStmt = $this->conn->prepare(
            "UPDATE ShoppingCart SET quantity = quantity + 1 WHERE customer_id = ? AND product_id = ?"
        );
        $updateStmt->bind_param("ii", $this->customID, $itemId);
        if (!$updateStmt->execute()) {
            throw new DatabaseError("Error: " . $updateStmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $updateStmt->close();
    }

    public function decrementItem($itemId) {
        // Check current quantity
        $checkStmt = $this->conn->prepare(
            "SELECT quantity FROM ShoppingCart WHERE customer_id = ? AND product_id = ?"
        );
        $checkStmt->bind_param("ii", $this->customID, $itemId);
        if (!$checkStmt->execute()) {
           throw new DatabaseError("Error: " . $checkStmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $result = $checkStmt->get_result();
        $checkStmt->close();
        $row = $result->fetch_assoc();

        if ($row["quantity"] <= 1) {
            // If quantity is 1, remove the item
            $this->removeItem($itemId);
        } else {
            // Otherwise decrement the quantity
            $updateStmt = $this->conn->prepare(
                "UPDATE ShoppingCart SET quantity = quantity - 1 WHERE customer_id = ? AND product_id = ?"
            );
            $updateStmt->bind_param("ii", $this->customID, $itemId);
            if (!$updateStmt->execute()) {
                throw new DatabaseError("Error: " . $updateStmt->error, "We're sorry, something went wrong. Please try again later.");
            }
            $updateStmt->close();
        }
    }

    public function emptyCart() {
        $deleteStmt = $this->conn->prepare(
            "DELETE FROM ShoppingCart WHERE customer_id = ?"
        );
        $deleteStmt->bind_param("i", $this->customID);
        if (!$deleteStmt->execute()) {
            throw new DatabaseError("Error: " . $deleteStmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $deleteStmt->close();
    }

    public function displayCart() {
        // Fetch ShoppingCart items for the customer
        $stmt = $this->conn->prepare(
            "SELECT Product.name, Product.price, ShoppingCart.quantity, Product.id FROM ShoppingCart JOIN Product ON ShoppingCart.product_id = Product.id WHERE ShoppingCart.customer_id = ?"
        );
        $stmt->bind_param("i", $this->customID);
        if (!$stmt->execute()) {
            throw new DatabaseError("Error: " . $stmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $result = $stmt->get_result();
        $stmt->close();

        // Display cart items if there are any
        if ($result->num_rows > 0) { ?><table class="table mt-4">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                while ($row = $result->fetch_assoc()) {

                    $row_total = $row["price"] * $row["quantity"];
                    $total += $row_total;
                    ?>
                    <tr>
                        <td> <?php echo htmlspecialchars($row["name"]); ?> </td>
                        <td> <?php echo number_format(
                            $row["price"],
                            2
                        ); ?> € </td>
                        <td> <?php echo htmlspecialchars(
                            $row["quantity"]
                        ); ?> </td>
                        <td> <?php echo number_format($row_total, 2); ?> € </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td><strong> <?php echo htmlspecialchars(
                        number_format($total, 2)
                    ); ?> € </strong></td>
                </tr>
            </tbody>
            </table>
            <?php } else {echo "<p>Your shopping cart is empty.</p>";}
    }

    public function displayCartEditor() {
        // Fetch ShoppingCart items for the customer
        $stmt = $this->conn->prepare(
            "SELECT Product.name, Product.price, ShoppingCart.quantity, Product.id FROM ShoppingCart JOIN Product ON ShoppingCart.product_id = Product.id WHERE ShoppingCart.customer_id = ?"
        );
        $stmt->bind_param("i", $this->customID);
        if(!$stmt->execute()) {
            throw new DatabaseError("Error: " . $stmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $result = $stmt->get_result();
        $stmt->close();

        // Display cart items if there are any
        if ($result->num_rows > 0) { ?><table class="table mt-4">
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

                    $row_total = $row["price"] * $row["quantity"];
                    $total += $row_total;
                    ?>
                    <tr>
                        <td> <?php echo htmlspecialchars($row["name"]); ?> </td>
                        <td> <?php echo number_format(
                            $row["price"],
                            2
                        ); ?> € </td>
                        <td> <?php echo htmlspecialchars(
                            $row["quantity"]
                        ); ?> </td>
                        <td> <?php echo number_format($row_total, 2); ?> € </td>
                        <td>
                            <button class="btn p-0 border-0 me-2" onclick="onDecrementClick(<?php echo $row["id"]; ?>, '<?php echo htmlspecialchars($row["name"]); ?>')">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button class="btn p-0 border-0 me-2" onclick="onIncrementClick(<?php echo $row["id"]; ?>, '<?php echo htmlspecialchars($row["name"]); ?>')">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn p-0 border-0" onclick="onRemoveClick(<?php echo $row["id"]; ?>, '<?php echo htmlspecialchars($row["name"]); ?>')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td><strong> <?php echo number_format($total,2); ?> € </strong></td>
                    <td></td>
                </tr>
            </tbody>
            </table>
            <?php } else {echo "<p>Your shopping cart is empty.</p>";}
    }

    public function checkout($address_id) {
        // get ShoppingCart items for the customer
        $stmt = $this->conn->prepare(
            "SELECT Product.name, Product.price, ShoppingCart.quantity, Product.id FROM ShoppingCart JOIN Product ON ShoppingCart.product_id = Product.id WHERE ShoppingCart.customer_id = ?"
        );
        $stmt->bind_param("i", $this->customID);
        if (!$stmt->execute()) {
            throw new DatabaseError("Error: " . $stmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $result = $stmt->get_result();
        $stmt->close();

        // create order
        $this->conn->begin_transaction();

        // Calculate total price
        $total_price = 0;
        while ($row = $result->fetch_assoc()) {
            $total_price += $row["price"] * $row["quantity"];
        }

        // Insert into Orders table
        $orderStmt = $this->conn->prepare(
            "INSERT INTO Orders (customer_id, address_id, order_date, total_price, status) VALUES (?, ?, CURDATE(), ?, 'pending')"
        );
        $orderStmt->bind_param("iid", $this->customID, $address_id, $total_price);
        if (!$orderStmt->execute()) {
            throw new DatabaseError("Error: " . $orderStmt->error, "We're sorry, something went wrong. Please try again later.");
        }
        $order_id = $orderStmt->insert_id;
        $orderStmt->close();

        // Insert into Order_Product table
        $result->data_seek(0); // Reset result pointer
        while ($row = $result->fetch_assoc()) {
            $orderProductStmt = $this->conn->prepare(
                "INSERT INTO Order_Product (orders_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"
            );
            $orderProductStmt->bind_param(
                "iiid",
                $order_id,
                $row["id"],
                $row["quantity"],
                $row["price"]
            );
            if (!$orderProductStmt->execute()) {
                throw new DatabaseError("Error: " . $orderProductStmt->error, "We're sorry, something went wrong. Please try again later.");
            }
            $orderProductStmt->close();
        }

        // Empty the shopping cart
        $this->emptyCart();

        $this->conn->commit();

        return $order_id;
    }
}
?>
