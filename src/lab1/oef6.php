<?php
function swap(&$a, &$b)
{
    $temp = $a;
    $a = $b;
    $b = $temp;
}

$number1 = 542;
$number2 = 145;
$number3 = 487;

// Order from small to large
if ($number1 > $number2) {
    swap($number1, $number2);
}
if ($number2 > $number3) {
    swap($number2, $number3);
}
if ($number1 > $number2) {
    swap($number1, $number2);
}

echo "<h2> Ordered from small to large:</h2> <p> $number1, $number2, $number3</p>";

// Order from large to small
if ($number1 < $number2) {
    swap($number1, $number2);
}
if ($number2 < $number3) {
    swap($number2, $number3);
}
if ($number1 < $number2) {
    swap($number1, $number2);
}

echo "<h2>Ordered from large to small:</h2> <p>$number1, $number2, $number3</p>";
?>
