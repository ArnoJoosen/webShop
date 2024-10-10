<?php
// Start the session
session_start();

// Define products
$products = [
    ["name" => "TV", "price" => 499.99],
    ["name" => "iPhone", "price" => 999.99],
    ["name" => "Laptop", "price" => 1299.99],
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    // Validate form data
    if (empty($_POST["gender"])) {
        $errors[] = "Selecteer alstublieft uw geslacht.";
    }

    if (empty($_POST["name"])) {
        $errors[] = "Vul alstublieft uw naam in.";
    }

    if (empty($errors)) {
        // Process form data here
        $success = "Uw bestelling is succesvol verwerkt.";

        // Calculate prices for each item and total price
        $itemPrices = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $quantity = isset($_POST["quantity"][$product["name"]])
                ? intval($_POST["quantity"][$product["name"]])
                : 0;
            $itemPrice = $product["price"] * $quantity;
            $itemPrices[$product["name"]] = $itemPrice;
            $totalPrice += $itemPrice;
        }

        // Store calculations in session
        $_SESSION["itemPrices"] = $itemPrices;
        $_SESSION["totalPrice"] = $totalPrice;

        // Calculate total
        $total = 0;
        foreach ($products as $product) {
            $quantity = isset($_POST["quantity"][$product["name"]])
                ? intval($_POST["quantity"][$product["name"]])
                : 0;
            $total += $product["price"] * $quantity;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelformulier</title>
    <style>
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Bestelformulier</h1>
    <form method="post" action="">
        <label>
            <input type="radio" name="gender" value="man" <?php echo isset(
                $_POST["gender"]
            ) && $_POST["gender"] == "man"
                ? "checked"
                : ""; ?>> Man
        </label>
        <label>
            <input type="radio" name="gender" value="vrouw" <?php echo isset(
                $_POST["gender"]
            ) && $_POST["gender"] == "vrouw"
                ? "checked"
                : ""; ?>> Vrouw
        </label>
        <br><br>
        <label for="name">Naam:</label>
        <input type="text" id="name" name="name" value="<?php echo isset(
            $_POST["name"]
        )
            ? htmlspecialchars($_POST["name"])
            : ""; ?>">
        <br><br>
        <table>
            <tr>
                <th>Product</th>
                <th>Prijs</th>
                <th>Aantal</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product["name"]); ?></td>
                <td>€<?php echo number_format($product["price"], 2); ?></td>
                <td>
                    <input type="number" name="quantity[<?php echo htmlspecialchars(
                        $product["name"]
                    ); ?>]" min="0" value="<?php echo isset(
    $_POST["quantity"][$product["name"]]
)
    ? intval($_POST["quantity"][$product["name"]])
    : 0; ?>">
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <input type="submit" value="Bestellen">
    </form>

    <?php
    if (isset($errors) && !empty($errors)) {
        echo "<div class='error'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    }

    if (isset($success)) {
        echo "<div class='success'><p>$success</p></div>";
        echo "<h2>Factuur</h2>";
        echo "<table>";
        echo "<tr><th>Product</th><th>Prijs</th><th>Aantal</th><th>Subtotaal</th></tr>";
        foreach ($products as $product) {
            $quantity = isset($_POST["quantity"][$product["name"]])
                ? intval($_POST["quantity"][$product["name"]])
                : 0;
            $subtotal = $product["price"] * $quantity;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($product["name"]) . "</td>";
            echo "<td>€" . number_format($product["price"], 2) . "</td>";
            echo "<td>" . $quantity . "</td>";
            echo "<td>€" . number_format($subtotal, 2) . "</td>";
            echo "</tr>";
        }
        echo "<tr><td colspan='3'><strong>Totaal</strong></td><td>€" .
            number_format($total, 2) .
            "</td></tr>";
        echo "</table>";
    }
    ?>
</body>
</html>
