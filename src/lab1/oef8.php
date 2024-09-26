<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checker Board</title>
    <style>
        table {
            border-collapse: collapse;
            width: 270px;
            border: 2px solid #000;
        }
        td {
            width: 30px;
            height: 30px;
        }
    </style>
</head>
<body>
    <h1>Checker Board</h1>
    <table>
        <?php
        $rows = 9;
        $cols = 9;

        for ($i = 0; $i < $rows; $i++) {
            echo "<tr>";
            for ($j = 0; $j < $cols; $j++) {
                $color = ($i + $j) % 2 == 0 ? "white" : "black";
                echo "<td style='background-color: $color;'></td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
