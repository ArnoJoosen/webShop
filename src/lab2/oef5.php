<!--
Ontwikkel een webpagina waar:
-
 met een formulier uw geboortedatum gevraagd wordt
-
 met een PHP script uw leeftijd afgedrukt wordt
Hint: date (“Y”), date(“m”) en date(“d”) geven, respectievelijk het huidige
jaar, maand en dag. date(“d-m-Y”) geeft alles samen in een string.
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>oef5</title>
</head>
<body>
<h1> Geboortedatum </h1>
<form action="oef5.php" method="post">
    <label for="geboortedatum"> Geboortedatum: </label>
    <input type="date" name="geboortedatum" id="geboortedatum">
    <input type="submit" value="Verzenden">
</form>
<?php
if (isset($_POST["geboortedatum"])) {
    $geboortedatum = $_POST["geboortedatum"];
    $geboortedatum = explode("-", $geboortedatum);
    $geboortedatum = array_reverse($geboortedatum);
    $geboortedatum = implode("-", $geboortedatum);
    $geboortedatum = new DateTime($geboortedatum);
    $huidigeDatum = new DateTime();
    $interval = $geboortedatum->diff($huidigeDatum);
    echo "Uw leeftijd is: " . $interval->format("%y jaar");
}
?>
</body>
</html>