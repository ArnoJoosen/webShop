<?php
$docentenVakken = [
    "Wim Dams" => "Labview",
    "Dirk Van Merode" => "Digitale elektronica 2",
    "Lars Struyf" => "Software 2",
    "Sofie Beerens" => "Applicaties ICT",
];
// Stap 2: Overzicht van alle docenten
?>
<h2>Overzicht Docenten:</h2>
<ul>
<?php foreach ($docentenVakken as $docent => $vak): ?>
    <li><?= $docent ?></li>
<?php endforeach; ?>
</ul>

<?php
// Stap 3: Overzicht van alle vakken
?>
<h2>Overzicht Vakken:</h2>
<ul>
<?php foreach ($docentenVakken as $vak): ?>
    <li><?= $vak ?></li>
<?php endforeach; ?>
</ul>

<?php
// Stap 4: Overzicht van docenten met hun vak
?>
<h2>Overzicht Docenten met Vakken:</h2>
<ul>
<?php foreach ($docentenVakken as $docent => $vak): ?>
    <li><?= $docent ?> - <?= $vak ?></li>
<?php endforeach; ?>
</ul>

<?php
// Stap 5: Elk vak 5 keer tonen met toenemende spaties
?>
<h2>Vakken Herhaald:</h2>
<?php // todo fix
foreach ($docentenVakken as $vak):
    for ($i = 0; $i < 5; $i++):
        $spaties = str_repeat("&nbsp;", $i * 10); ?>
    <p><?= $spaties . $vak ?></p>
<?php
    endfor;
endforeach; ?>

<style>
    body {
        font-family: Arial, sans-serif;
    }
    h2 {
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
    }
    ul {
        list-style-type: none;
        padding-left: 0;
    }
    li {
        margin-bottom: 5px;
    }
</style>
