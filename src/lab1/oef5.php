<?php
$currentMonth = date("F", time());

if (
    $currentMonth == "June" ||
    $currentMonth == "July" ||
    $currentMonth == "August"
) {
    echo "It's summer vacations! Make the most of it!";
} else {
    echo "Sorry, no leave yet, so work!";
}
?>
