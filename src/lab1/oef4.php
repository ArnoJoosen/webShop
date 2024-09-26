<?php
define("SCHOOL", "Thomas More, campus De Nayer");

$participants = 260;
$absentees = 57;
$participant_price = 25.8;
$cancellation_price = 15.5;

$total_cost =
    ($participants - $absentees) * $participant_price +
    $absentees * $cancellation_price;

echo "<html>\n";
echo "    <head>\n";
echo "        <title>Team Building Cost Calculation</title>\n";
echo "    </head>\n";
echo "    <body>\n";
echo "        <h1>Team Building Cost Calculation</h1>\n";
echo "        <p>School: " . SCHOOL . "</p>\n";
echo "        <p>Total Participants: " . $participants . "</p>\n";
echo "        <p>Absentees: " . $absentees . "</p>\n";
echo "        <p>Participant Price: " . $participant_price . " €</p>\n";
echo "        <p>Cancellation Price: " . $cancellation_price . " €</p>\n";
echo "        <p>Total Cost: " . $total_cost . " €</p>\n";
echo "    </body>\n";
echo "</html>\n";
?>
