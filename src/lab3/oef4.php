<?php
// Array met to-do items
$todoItems = [
    [
        "beschrijving" => "Rapport afwerken",
        "deadline" => 1,
        "categorie" => "school",
    ],
    [
        "beschrijving" => "Boodschappen doen",
        "deadline" => 3,
        "categorie" => "thuis",
    ],
    [
        "beschrijving" => "Presentatie voorbereiden",
        "deadline" => 20,
        "categorie" => "werk",
    ],
    [
        "beschrijving" => "Sportkleding wassen",
        "deadline" => 48,
        "categorie" => "thuis",
    ],
    [
        "beschrijving" => "Project afronden",
        "deadline" => 72,
        "categorie" => "werk",
    ],
];

// CSS voor de opmaak
echo '<style>
    .todo-list {
        font-family: Arial, sans-serif;
        list-style-type: none;
        padding: 0;
    }
    .todo-item {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
    }
    .red { background-color: #ffcccc; }
    .orange { background-color: #ffeeba; }
    .green { background-color: #d4edda; }
</style>';

// Print de lijst
echo '<ul class="todo-list">';
for ($i = 0; $i < count($todoItems); $i++) {
    $item = $todoItems[$i];
    $deadline = $item["deadline"];

    if ($deadline < 2) {
        $color = "red";
    } elseif ($deadline < 24) {
        $color = "orange";
    } else {
        $color = "green";
    }

    echo '<li class="todo-item ' . $color . '">';
    echo "<strong>" . $item["beschrijving"] . "</strong><br>";
    echo "Deadline: " . $deadline . " uur<br>";
    echo "Categorie: " . $item["categorie"];
    echo "</li>";
}
echo "</ul>";
?>
