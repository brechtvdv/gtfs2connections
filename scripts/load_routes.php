<?php

require_once "bootstrap.php";

$file_routes = $argv[1];
$batchSize = 30; // routes.txt is very big, so we flush regularly

if (($handleRead = fopen($file_routes, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    $row = 1; // row_counter
    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $route = new Route();

        for ($i = 0; $i < $column_count; $i++) {
            save($route, $header[$i], $line[$i]);
        }

        unset($line);

        $entityManager->persist($route);
        if (($row % $batchSize) === 0) {
            $entityManager->flush();
            $entityManager->clear(); // Detaches all objects from Doctrine
        }

        $row++;
    }

    fclose($handleRead);

    $entityManager->flush();

    echo "Created " . $row . " routes" . "\n";
} else {
    echo "Something went wrong with reading routes.txt" . "\n";
}

function save($route, $property, $value) {
    switch ($property) {
        case 'route_id':
            $route->setRouteId($value);
            break;
        case 'agency_id':
            $route->setAgencyId($value);
            break;
        case 'route_short_name':
            $route->setRouteShortName($value);
            break;
        case 'route_long_name':
            $route->setRouteLongName($value);
            break;
        case 'route_desc':
            $route->setRouteDesc($value);
            break;
        case 'route_type':
            $route->setRouteType($value);
            break;
        case 'route_url':
            $route->setRouteUrl($value);
            break;
        case 'route_color':
            $route->setRouteColor($value);
            break;
        case 'route_text_color':
            $route->setRouteTextColor($value);
            break;
    }
}