<p> php script to make a table of multiplication of an integer (from 1 to 10) </p>

<?php
$number = 5;
echo "<table border='1'>";
echo "<tr>";
echo "<th>Number</th>";
echo "<th>Multiplication</th>";
echo "<th>Result</th>";
echo "</tr>";
for ($i = 1; $i <= 10; $i++) {
    echo "<tr>";
    echo "<td>$number</td>";
    echo "<td>$i</td>";
    echo "<td>" . $number * $i . "</td>";
    echo "</tr>";
}
echo "</table>";


?>
