<h1> Tafel van 0 met arrays </h1>
<?php
$tafels = range(0, 10);
// multiply each element by 9
foreach($tafels as &$value) {
    $value *= 9;
}
foreach ($tafels as $tafel) {
    echo $tafel . "<br>";
}
?>
