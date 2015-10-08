<?php

require_once "bootstrap.php";

date_default_timezone_set('UTC');

$file_frequencies = $argv[1];
$batchSize = 30; // frequencies.txt can be very big, so we flush regularly

if (($handleRead = fopen($file_frequencies, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    $row = 1; // row_counter
    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $frequency = new Frequency();

        for ($i = 0; $i < $column_count; $i++) {
            save($frequency, $header[$i], $line[$i]);
        }

        unset($line);

        $entityManager->persist($frequency);
        if (($row % $batchSize) === 0) {
            $entityManager->flush();
            $entityManager->clear(); // Detaches all objects from Doctrine
        }

        $row++;
    }

    fclose($handleRead);

    $entityManager->flush();

    echo "Created " . $row . " frequencies" . "\n";
} else {
    echo "frequencies.txt not found." . "\n";
}

function save($frequency, $property, $value) {
    switch ($property) {
        case 'trip_id':
            $frequency->setTripId($value);
            break;
        case 'start_time':
            $start_time = \DateTime::createFromFormat('H:i:s', $value);
            $frequency->setStartTime($start_time);
            break;
        case 'end_time':
            $end_time = \DateTime::createFromFormat('H:i:s', $value);
            $frequency->setEndTime($end_time);
            break;
        case 'headway_secs':
            $frequency->setHeadwaySecs($value);
            break;
        case 'exact_times':
            $frequency->setExactTimes($value);
            break;
    }
}