<h1> Programmagids </h1>
<hr>
<h2> Docentenkorps </h2>
<hr>
<?php
// vul de array &docentenVakken met volgende gegevens:
// key, value: (Wim Dams, Labview), (Dirk Van Merde), (Lars Struyf, Software 2), (Sofie Beerens, Applicaties ITC)
$docentenVakken = array(
    "Wim Dams" => "Labview",
    "Dirk Van Merde" => "Digital elektronica 2",
    "Lars Struyf" => "Software 2",
    "Sofie Beerens" => "Applicaties ITC"
);
// display all docenten
foreach ($docentenVakken as $docent => $vak) {
    echo $docent . " geeft " . $vak . "<br>";
}
?>
<hr>
<h2> Vakkenlijst </h2>
<hr>
<?php
// dispaly all vakken
foreach ($docentenVakken as $vak) {
    echo $vak . "<br>";
}
?>
<hr>
<h2> Docent - vak </h2>
<hr>
<?php
// display all docenten with their vak
foreach ($docentenVakken as $docent => $vak) {
    echo $docent . "(" . $vak . ") aan 2 EICT studenten<br>";
}
?>
<hr>
<h2> 5 keer elk vak met sprongen </h2>
<hr>
<?php
// display all vakken 5 keer met telkens 10 spaties
$i = 0;
foreach ($docentenVakken as $vak) {
    for ($j = 0; $j < 5; $j++) {
        echo str_repeat("&nbsp;", 10*$i) . $vak . "<br>";
    }
    $i++;
}