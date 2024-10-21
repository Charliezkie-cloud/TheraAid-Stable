<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MWF Date Generator</title>
</head>

<body>
    <h1>MWF Date Generator</h1>
    <form method="post">
        <label for="start_date">Select Start Date:</label>
        <select id="start_date" name="start_date" required>
            <?php
            $currentMonth = date('m');
            $currentYear = date('Y');
            
            for ($day = 1; $day <= 31; $day++) {
                $dateString = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                $date = DateTime::createFromFormat('Y-m-d', $dateString);

                if ($date && $date->format('m') == $currentMonth) {
                    $dayOfWeek = $date->format('N');
                    if ($dayOfWeek == 1 || $dayOfWeek == 3 || $dayOfWeek == 5) {
                        echo "<option value='" . $date->format('Y-m-d') . "'>" . $date->format('Y-m-d') . "</option>";
                    }
                }
            }
            ?>
        </select>
        <br><br>
        <input type="submit" value="Generate MWF Dates">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        function generateMWFDates($startDate, $totalSessions)
        {
            $mwfDates = [];
            $date = new DateTime($startDate);
            $sessionsGenerated = 0;

            while ($sessionsGenerated < $totalSessions) {
                if ($date->format('N') == 1 || $date->format('N') == 3 || $date->format('N') == 5) {
                    $mwfDates[] = $date->format('Y-m-d');
                    $sessionsGenerated++;
                }
                $date->modify('+1 day');
            }

            return $mwfDates;
        }

        $startDate = $_POST['start_date'];
        $totalSessions = 7;
        $mwfDates = generateMWFDates($startDate, $totalSessions);

        echo "<h2>Generated MWF Dates:</h2>";
        echo "<ul>";

        foreach ($mwfDates as $date) {
            echo "<li>" . htmlspecialchars($date) . "</li>";
        }

        echo "</ul>";
    }
    ?>
</body>

</html>