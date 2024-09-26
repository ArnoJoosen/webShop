<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Lottery Numbers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .numbers {
            font-size: 24px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="numbers">
        <?php
        // Function to swap two values
        function swap(&$a, &$b)
        {
            $temp = $a;
            $a = $b;
            $b = $temp;
        }

        // Generate 6 unique random numbers
        $numbers = [];
        while (count($numbers) < 6) {
            $random = rand(1, 42);
            if (!in_array($random, $numbers)) {
                $numbers[] = $random;
            }
        }

        // Bubble sort algorithm
        $n = count($numbers);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($numbers[$j] > $numbers[$j + 1]) {
                    swap($numbers[$j], $numbers[$j + 1]);
                }
            }
        }

        // Display the sorted numbers
        echo "Today's lucky numbers are:<br>";
        foreach ($numbers as $number) {
            echo $number . " ";
        }
        ?>
    </div>
</body>
</html>
