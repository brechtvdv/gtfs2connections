<?php

require_once "bootstrap.php";

date_default_timezone_set('UTC');

$file_calendar = $argv[1];
$batchSize = 30; // calendar.txt is very big, so we flush regularly

if (($handleRead = fopen($file_calendar, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    $row = 1; // row_counter
    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $calendar = new Calendar();

        for ($i = 0; $i < $column_count; $i++) {
            save($calendar, $header[$i], $line[$i]);
        }

        unset($line);

        $entityManager->persist($calendar);
        if (($row % $batchSize) === 0) {
            $entityManager->flush();
            $entityManager->clear(); // Detaches all objects from Doctrine
        }

        $row++;
    }

    fclose($handleRead);

    $entityManager->flush();

    echo "Created " . $row . " calendars" . "\n";
} else {
    echo "Calendar.txt not found." . "\n";
}

function save($calendar, $property, $value) {
    switch ($property) {
        case 'service_id':
            $calendar->setServiceId($value);
            break;
        case 'monday':
            $calendar->setMonday($value);
            break;
        case 'tuesday':
            $calendar->setTuesday($value);
            break;
        case 'wednesday':
            $calendar->setWednesday($value);
            break;
        case 'thursday':
            $calendar->setThursday($value);
            break;
        case 'friday':
            $calendar->setFriday($value);
            break;
        case 'saturday':
            $calendar->setSaturday($value);
            break;
        case 'sunday':
            $calendar->setSunday($value);
            break;
        case 'start_date':
            $start_date = \DateTime::createFromFormat('Ymd', $value);
            $calendar->setStartDate($start_date);
            break;
        case 'end_date':
            $end_date = \DateTime::createFromFormat('Ymd', $value);
            $calendar->setEndDate($end_date);
            break;
    }
}