<!-- Maak een array met 5 “to do” items. Elk item heeft volgende kenmerken:
-
 Beschrijving
-
 deadline in x uur?
-
 categorie (vb: thuis, school, werk, …)
Print via een for lus een mooie lijst af (gebruik CSS voor de opmaak):
-
 rood indien minder dan 2 uur voor deadline
-
 oranje indien minder dan 24u voor deadline
-
 groen indien meer dan 24u voor deadline
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>oef4</title>
    <style>
        .red {
            color: red;
        }
        .orange {
            color: orange;
        }
        .green {
            color: green;
        }
    </style>
</head>
<body>
<h1> To do list </h1>
<?php
$toDoList = array(
    array(
        "Beschrijving" => "Kuisen",
        "deadline" => 1,
        "categorie" => "thuis"
    ),
    array(
        "Beschrijving" => "Studeren",
        "deadline" => 23,
        "categorie" => "school"
    ),
    array(
        "Beschrijving" => "Werken",
        "deadline" => 48,
        "categorie" => "werk"
    ),
    array(
        "Beschrijving" => "Sporten",
        "deadline" => 72,
        "categorie" => "sport"
    ),
    array(
        "Beschrijving" => "Slapen",
        "deadline" => 96,
        "categorie" => "thuis"
    )
);
foreach ($toDoList as $toDo) {
    if ($toDo["deadline"] < 2) {
        echo "<p class='red'>" . $toDo["Beschrijving"] . " - " . $toDo["categorie"] . "</p>";
    } elseif ($toDo["deadline"] < 24) {
        echo "<p class='orange'>" . $toDo["Beschrijving"] . " - " . $toDo["categorie"] . "</p>";
    } else {
        echo "<p class='green'>" . $toDo["Beschrijving"] . " - " . $toDo["categorie"] . "</p>";
    }
}
?>
</body>
</html>