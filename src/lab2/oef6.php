<!--Maak een PHP programma dat het volgende op het scherm toont:
Na invullen en versturen antwoordt de server met iets als:
Zorg ervoor dat het antwoord onder het formulier komt te staan en dat de
ingevulde data in het formulier blijft staan. Genereer een gepaste
foutboodschap indien het veld “naam” of the radio buttons Mevrouw,
Mijnheer niet ingevuld/aangevinkt zijn.-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> Bestelformulier </h1>
    <form action="oef6.php" method="post">
        <div>
        <input type = "radio" name = "gender" value = "Man" id = "man">
        <label> Man </label>
        </div>
        <div>
        <input type = "radio" name="gender" value="Vrouw" id="vrouw">
        <label> Vrouw </label>
        </div>
        <label> Name: </label>
        <input type = "text" name = "naam" id = "naam">
        <!-- bestel tabel -->
        <table>
            <tr>
                <th> Product </th>
                <th> Prijs </th>
                <th> Aantal </th>
            </tr>
            <tr>
                <td> TV </td>
                <td> 100€ </td>
                <td> <input type = "number" name = "tv" id = "tv"> </td>
            </tr>
            <tr>
                <td> Iphone </td>
                <td> 600€ </td>
                <td> <input type = "number" name = "iphone" id = "iphone"> </td>
            </tr>
            <tr>
                <td> Laptop </td>
                <td> 800€ </td>
                <td> <input type = "number" name = "laptop" id = "laptop"> </td>
            </tr>
        </table>
        <button type = "submit" name = "submit"> Bestel </button>
        <button type = "reset"> Reset </button>
    </form>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        if (isset($_POST["gender"]) && !empty($_POST["naam"])) {
            // Form is submitted and both gender and name are filled in
            ?>
            <h2>Geachte <?php
            if ($_POST["gender"] == "Man") {
                echo "man ";
            } else {
                echo "mevrouw ";
            }
            echo $_POST["naam"];
            ?>,
            u bestelde:</h2>
            <table>
            <tr>
                <th> Product </th>
                <th> Prijs </th>
                <th> Aantal </th>
            </tr>
            <tr>
                <td> TV </td>
                <td> 100€ </td>
                <td> <?php if ($_POST["tv"] != "") {echo $_POST["tv"]; } else {echo "0";} ?> </td>
            </tr>
            <tr>
                <td> Iphone </td>
                <td> 600€ </td>
                <td> <?php if ($_POST["iphone"] != "") { echo $_POST["iphone"]; } else {echo "0";} ?> </td>
            </tr>
            <tr>
                <td> Laptop </td>
                <td> 800€ </td>
                <td> <?php if ($_POST["laptop"] != "") { echo $_POST["laptop"]; } else { echo "0";} ?> </td>
            </tr>
        </table>
            <?php

        } else {
            // Either gender or name is missing
            echo "<p>Please fill in your</p>";
        }
    } ?>
</body>

</html>
