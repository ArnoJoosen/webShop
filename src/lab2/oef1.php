<h1> Tafel van 9 met arrays </h1>
</br>
<?php
$tafes = array();
for ($i = 0; $i < 11; $i++) {
    $tafels[$i] = $i * 9;
}
foreach ($tafels as $tafel) {
    echo $tafel . "<br>";
}
?>
</br>
<h1> reverse tafel van 9 met arrays </h1>
<?php
foreach (array_reverse($tafels) as $tafel) {
    echo $tafel . "<br>";
}
?>