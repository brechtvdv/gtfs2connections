<?php

require_once "bootstrap.php";

$file_trips = $argv[1];
$batchSize = 30; // trips.txt is very big, so we flush regularly

if (($handleRead = fopen($file_trips, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    $row = 1; // row_counter
    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $trip = new Trip();

        for ($i = 0; $i < $column_count; $i++) {
            save($trip, $header[$i], $line[$i]);
        }

        unset($line);

        $entityManager->persist($trip);
        if (($row % $batchSize) === 0) {
            $entityManager->flush();
            $entityManager->clear(); // Detaches all objects from Doctrine
        }

        $row++;
    }

    fclose($handleRead);

    $entityManager->flush();

    echo "Created " . $row . " trips" . "\n";
} else {
    echo "Something went wrong with reading trips.txt" . "\n";
}

function save($trip, $property, $value) {
    switch ($property) {
        case 'route_id':
            $trip->setRouteId($value);
            break;
        case 'service_id':
            $trip->setServiceId($value);
            break;
        case 'trip_id':
            $trip->setTripId($value);
            break;
        case 'trip_headsign':
            $trip->setTripHeadsign($value);
            break;
        case 'trip_short_name':
            $trip->setTripShortName($value);
            break;
        case 'direction_id':
            $trip->setDirectionId($value);
            break;
        case 'block_id':
            $trip->setBlockId($value);
            break;
        case 'shape_id':
            $trip->setShapeId($value);
            break;
        case 'wheelchair_accessible':
            $trip->setWheelchairAccessible($value);
            break;
        case 'bikes_allowed':
            $trip->setBikesAllowed($value);
            break;
    }
}