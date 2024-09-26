<table style='border-collapse: collapse; background-color: yellow;'>
<tr><th></th>
<?php for ($i = 1; $i <= 7; $i++) {
    echo "<th style='border: 1px solid black; padding: 5px;'>$i</th>";
} ?>
</tr>
<?php for ($i = 1; $i <= 7; $i++) {
    echo "<tr>";
    echo "<th style='border: 1px solid black; padding: 5px;'>$i</th>";
    for ($j = 1; $j <= 7; $j++) {
        $result = $i * $j;
        echo "<td style='border: 1px solid black; padding: 5px;'>$result</td>";
    }
    echo "</tr>";
} ?>
</table>
