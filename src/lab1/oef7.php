<?php
$lower_limit = 487;
$upper_limit = 1784;
$numbers_per_line = 20;

echo "<pre>";
$count = 0;
for ($i = $lower_limit; $i <= $upper_limit; $i++) {
    if ($i % 2 == 0) {
        printf("%5d ", $i);
        $count++;
        if ($count % $numbers_per_line == 0) {
            echo "\n";
        }
    }
}
echo "</pre>";
?>
