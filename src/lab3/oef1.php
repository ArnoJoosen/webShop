<?php
// Stap 1: Vul de array $tafels met de vermenigvuldigingstafel van 9
$tafels = array();
for ($i = 1; $i <= 10; $i++) {
    $tafels[] = 9 * $i;
}

// Stap 2: Toon de inhoud van de array $tafels met een foreach-lus
echo "Vermenigvuldigingstafel van 9 (oplopend):<br>";
foreach ($tafels as $getal) {
    echo $getal . "<br>";
}

// Stap 3: Toon de inhoud van de array $tafels omgekeerd met een for-lus
echo "<br>Vermenigvuldigingstafel van 9 (aflopend):<br>";
for ($i = count($tafels) - 1; $i >= 0; $i--) {
    echo $tafels[$i] . "<br>";
}
?>
