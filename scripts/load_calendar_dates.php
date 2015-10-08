<?php

require_once "bootstrap.php";

date_default_timezone_set('UTC');

$file_calendar_dates = $argv[1];
$batchSize = 30; // calendar_dates.txt is very big, so we flush regularly

if (($handleRead = fopen($file_calendar_dates, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    $row = 1; // row_counter
    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $calendarDate = new CalendarDate();

        for ($i = 0; $i < $column_count; $i++) {
            save($calendarDate, $header[$i], $line[$i]);
        }

        unset($line);

        $entityManager->persist($calendarDate);
        if (($row % $batchSize) === 0) {
            $entityManager->flush();
            $entityManager->clear(); // Detaches all objects from Doctrine
        }

        $row++;
    }

    fclose($handleRead);

    $entityManager->flush();

    echo "Created " . $row . " calendar_dates" . "\n";
} else {
    echo "calendar_dates.txt not found." . "\n";
}

function save($calendarDate, $property, $value) {
    switch ($property) {
        case 'service_id':
            $calendarDate->setServiceId($value);
            break;
        case 'date':
            $date = \DateTime::createFromFormat('Ymd', $value);
            $calendarDate->setDate($date);
            break;
        case 'exception_type':
            $calendarDate->setExceptionType($value);
            break;
    }
}