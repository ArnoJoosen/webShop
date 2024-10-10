<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leeftijdcalculator</title>
</head>
<body>
    <h1>Bereken uw leeftijd</h1>
    <form method="post" action="">
        <label for="geboortedatum">Geboortedatum:</label>
        <input type="date" id="geboortedatum" name="geboortedatum" required>
        <input type="submit" value="Bereken leeftijd">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $geboortedatum = new DateTime($_POST["geboortedatum"]);
        $vandaag = new DateTime();
        $leeftijd = $vandaag->diff($geboortedatum);

        echo "<p>Uw leeftijd is: " . $leeftijd->y . " jaar</p>";
        echo "<p>Huidige datum: " . date("d-m-Y") . "</p>";
    } ?>
</body>
</html>
