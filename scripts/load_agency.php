<?php

require_once "bootstrap.php";

$file_agency = $argv[1];

if (($handleRead = fopen($file_agency, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $agency = new Agency();

        for ($i = 0; $i < $column_count; $i++) {
            save($agency, $header[$i], $line[$i]);
        }

        unset($line);

        $entityManager->persist($agency);
    }

    fclose($handleRead);

    $entityManager->flush();

    echo "Created Agency " . $agency->getAgencyName() . " with ID " . $agency->getAgencyId() . "\n";
} else {
    echo "Something went wrong with reading agency.txt" . "\n";
}

function save($agency, $property, $value) {
    switch ($property) {
        case 'agency_id':
            $agency->setAgencyId($value);
            break;
        case 'agency_name':
            $agency->setAgencyName($value);
            break;
        case 'agency_url':
            $agency->setAgencyUrl($value);
            break;
        case 'agency_timezone':
            $agency->setAgencyTimezone($value);
            break;
        case 'agency_lang':
            $agency->setAgencyLang($value);
            break;
        case 'agency_phone':
            $agency->setAgencyPhone($value);
            break;
        case 'agency_fare_url':
            $agency->setAgencyFareUrl($value);
            break;
    }
}