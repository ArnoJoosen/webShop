<?php
$initialCapital = 1000;
$duration = 10;
$interestRate = 0.05;

echo "<table border='1'>";
echo "<tr><th>Year</th><th>Interest</th><th>Capital</th></tr>";

$capital = $initialCapital;
for ($year = 1; $year <= $duration; $year++) {
    $interest = $capital * $interestRate;
    $capital += $interest;
    echo "<tr><td>$year</td><td>" .
        number_format($interest, 2) .
        "</td><td>" .
        number_format($capital, 2) .
        "</td></tr>";
}

echo "</table>";
?>
