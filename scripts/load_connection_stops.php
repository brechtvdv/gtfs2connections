<?php

require_once "bootstrap.php";

$file_connection_stops = $argv[1];

if (($handleRead = fopen($file_connection_stops, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    $row = 1; // row_counter
    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $stopId = $line[0];
        $connectionStopId = $line[1];

        // Find stop with stop_id
        $sql = "
            SELECT *
              FROM stops
              WHERE stopId = ?
        ";
        $stmt = $entityManager->getConnection()->prepare($sql);
        $stmt->bindParam(1, $stopId);
        $stmt->execute();
        $stop = $stmt->fetchAll();

        // There's a stop with this ID
        if (count($stop)) {
            // Update stoptimes with connection stop id
            $sql = "
                UPDATE stoptimes
                SET connectionStopId=?
                WHERE stopId=?
            ";
            $stmt = $entityManager->getConnection()->prepare($sql);
            $stmt->bindParam(1, $connectionStopId);
            $stmt->bindParam(2, $stopId);
            $stmt->execute();
            $row++;
        }

        unset($line);
    }

    fclose($handleRead);

    $entityManager->flush();

    echo "Added " . $row . " connection_stop_ids" . "\n";
} else {
    echo "Something went wrong with reading connection_stops.txt" . "\n";
}