<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tafel van 9</title>
    <style>
        table {
            border-collapse: collapse;
        }
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
    // Array vullen met getallen 1 t.e.m. 10
    $getallen = range(1, 10);

    // Getallen omzetten naar de tafel van 9
    foreach ($getallen as &$getal) {
        $getal *= 9;
    }

    // Tabel maken met 2 rijen
    echo "<table>";
    echo "<tr>";
    for ($i = 0; $i < 10; $i++) {
        echo "<td>" . $getallen[$i] . "</td>";
    }
    echo "</tr>";
    echo "<tr>";
    for ($i = 9; $i >= 0; $i--) {
        echo "<td>" . $getallen[$i] . "</td>";
    }
    echo "</tr>";
    echo "</table>";
    ?>
</body>
</html>
