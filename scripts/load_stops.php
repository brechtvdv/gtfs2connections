<?php

require_once "bootstrap.php";

$file_stops = $argv[1];
$batchSize = 30; // stops.txt is very big, so we flush regularly

if (($handleRead = fopen($file_stops, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    $row = 1; // row_counter
    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $stop = new Stop();

        for ($i = 0; $i < $column_count; $i++) {
            save($stop, $header[$i], $line[$i]);
        }

        unset($line);

        $entityManager->persist($stop);
        if (($row % $batchSize) === 0) {
            $entityManager->flush();
            $entityManager->clear(); // Detaches all objects from Doctrine
        }

        $row++;
    }

    fclose($handleRead);

    $entityManager->flush();

    echo "Created " . $row . " stops" . "\n";
} else {
    echo "Something went wrong with reading stops.txt" . "\n";
}

function save($stop, $property, $value) {
    switch ($property) {
        case 'stop_id':
            $stop->setStopId($value);
            break;
        case 'stop_code':
            $stop->setStopCode($value);
            break;
        case 'stop_name':
            $stop->setStopName($value);
            break;
        case 'stop_desc':
            $stop->setStopDesc($value);
            break;
        case 'stop_lat':
            $stop->setStopLat($value);
            break;
        case 'stop_lon':
            $stop->setStopLon($value);
            break;
        case 'zone_id':
            $stop->setZoneId($value);
            break;
        case 'stop_url':
            $stop->setStopUrl($value);
            break;
        case 'location_type':
            $stop->setLocationType($value);
            break;
        case 'parent_station':
            $stop->setParentStation($value);
            break;
        case 'stop_timezone':
            $stop->setStopTimezone($value);
            break;
        case 'wheelchair_boarding':
            $stop->setWheelchairBoarding($value);
            break;
    }
}