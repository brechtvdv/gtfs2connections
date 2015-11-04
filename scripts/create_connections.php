<?php

/**
 * This script generates connections from a GTFS feed that is loaded in MySQL database.
 * Status: only feeds with calendar_dates.txt
 *
 */
ini_set('memory_limit', '-1'); // Although variables are always unset, just to make sure the script doesn't stop because it reached it's limit

require_once "bootstrap.php";

use Ivory\JsonBuilder\JsonBuilder;

date_default_timezone_set('UTC');

// get agencyId for output filename
$sql = "
        SELECT *
          FROM agency
    ";

$stmt = $entityManager->getConnection()->prepare($sql);
$stmt->execute();
$agencyArray = $stmt->fetchAll();
$agencyId = $agencyArray[0]['agencyId'];

// Change '/' to '-' in  name, otherwise problem with filename
$agencyId = preg_replace('/\//', '-', $agencyId);
$connectionsFilename = 'dist/connections-' . $agencyId . '.jsonldstream';

// delete previous file
if (file_exists($connectionsFilename)) {
    unlink($connectionsFilename);
}

// Write context to the files
$context = createContext($agencyId);
writeToFile($connectionsFilename, $context);

// This holds array of dates with corresponding serviceIds
// We need serviceIds per day to query ordered arrivals/departures
$date_serviceIdsArray = [];

$connectionsCounter = 1; // counter for connection identifier

// Associative array of stops that hold for every stop:
// Direct reachable stop (from some trip) + minimum time
$MST = []; // Todo search datastructure to find in O(1) minimum: $mst['ex1]['neigh1'] if exists...
$mstFilename = 'dist/mst-' . $agencyId . '.txt';

// delete previous file
if (file_exists($mstFilename)) {
    unlink($mstFilename);
}

// Let's generate list of dates with corresponding serviceId from calendars first
$sql = "
        SELECT *
          FROM calendars
    ";

$stmt = $entityManager->getConnection()->prepare($sql);
$stmt->execute();
$calendars = $stmt->fetchAll();

// When there are calendars
// TODO
if (count($calendars) > 0) {
    // Check if start- and/or endDate is given as parameter
    // Otherwise set minimum and maximum of calendars
//    if (!isset($argv[1]) || !isset($argv[2])) {
//        $sql = "
//            SELECT MIN(startDate) startDate, MAX(endDate) endDate
//              FROM calendars
//        ";
//        $stmt = $entityManager->getConnection()->prepare($sql);
//        $stmt->execute();
//        $startAndEndDate = $stmt->fetchAll();
//
//        $startDate_ = $startAndEndDate[0]['startDate'];
//        $endDate_ = $startAndEndDate[0]['endDate'];
//
//        if (isset($argv[1])) {
//            $startDate_ = $argv[1];
//        }
//        if (isset($arg[2])) {
//            $endDate_ = $argv[2];
//        }
//    } else {
//        $startDate_ = $argv[1];
//        $endDate_ = $argv[2];
//    }
//
//    for ($i = 0; $i < count($calendars); $i++) {
//        $calendar = $calendars[$i];
//
//        $startDate = $calendar['startDate'];
//        $endDate = $calendar['endDate'];
//
//        // When start- and endDate of calendar fall in given interval
//        if ($startDate >= $startDate_ && $endDate <= $endDate_) {
//            // Connections that happen after midnight of the previous day need to be added too
//            $prevDate = strtotime('-1 day', strtotime($startDate));
//            $calendarDates = getCalendarDatesOfSpecificDate($entityManager, $prevDate);
//            $date_serviceIdsArray = addCalendarDates($date_serviceIdsArray, $calendarDates);
//
//            // loop all days between start_date and end_date
//            for ($date = strtotime($startDate); $date <= strtotime($endDate); $date = strtotime('+1 day', $date)) {
//                // check if the day on this date drives
//                // we use dayOfWeek as offset in calendar array
//                $dayOfWeekNum = date('N',$date);
//                $day = getDayFromNum($dayOfWeekNum);
//                if ($calendar[$day] == '1') {
//                    // add to pairs
//                    $arrdepdate = date('Y-m-d', $date);
//                    $service = $calendar['serviceId'];
//                    $date_serviceIdsArray = addDateServiceId($date_serviceIdsArray, $arrdepdate, $service);
//                }
//            }
//        } else if ($startDate < $startDate_ && $endDate <= $endDate_) {
//            // Connections that happen after midnight of the previous day need to be added too
//            $prevDate = strtotime('-1 day', strtotime($startDate_));
//            $calendarDates = getCalendarDatesOfSpecificDate($entityManager, $prevDate);
//            $date_serviceIdsArray = addCalendarDates($date_serviceIdsArray, $calendarDates);
//
//            // StartDate falls before given startDate_
//            for ($date = strtotime($startDate_); $date <= strtotime($endDate); $date = strtotime('+1 day', $date)) {
//                // check if the day on this date drives
//                // we use dayOfWeek as offset in calendar array
//                $dayOfWeekNum = date('N',$date);
//                $day = getDayFromNum($dayOfWeekNum);
//                if ($calendar[$day] == '1') {
//                    // add to pairs
//                    $arrdepdate = date('Y-m-d', $date);
//                    $service = $calendar['serviceId'];
//                    $date_serviceIdsArray = addDateServiceId($date_serviceIdsArray, $arrdepdate, $service);
//                }
//            }
//        } else if ($startDate >= $startDate_ && $endDate > $endDate_) {
//            // Connections that happen after midnight of the previous day need to be added too
//            $prevDate = strtotime('-1 day', strtotime($startDate));
//            $calendarDates = getCalendarDatesOfSpecificDate($entityManager, $prevDate);
//            $date_serviceIdsArray = addCalendarDates($date_serviceIdsArray, $calendarDates);
//
//            // EndDate falls after given endDate_
//            for ($date = strtotime($startDate); $date <= strtotime($endDate_); $date = strtotime('+1 day', $date)) {
//                // check if the day on this date drives
//                // we use dayOfWeek as offset in calendar array
//                $dayOfWeekNum = date('N',$date);
//                $day = getDayFromNum($dayOfWeekNum);
//                if ($calendar[$day] == '1') {
//                    // add to pairs
//                    $arrdepdate = date('Y-m-d', $date);
//                    $service = $calendar['serviceId'];
//                    $date_serviceIdsArray = addDateServiceId($date_serviceIdsArray, $arrdepdate, $service);
//                }
//            }
//        } else if ($startDate < $startDate_ && $endDate > $endDate_) {
//            // Connections that happen after midnight of the previous day need to be added too
//            $prevDate = strtotime('-1 day', strtotime($startDate_));
//            $calendarDates = getCalendarDatesOfSpecificDate($entityManager, $prevDate);
//            $date_serviceIdsArray = addCalendarDates($date_serviceIdsArray, $calendarDates);
//
//            // Both overlap interval
//            for ($date = strtotime($startDate_); $date <= strtotime($endDate_); $date = strtotime('+1 day', $date)) {
//                // check if the day on this date drives
//                // we use dayOfWeek as offset in calendar array
//                $dayOfWeekNum = date('N',$date);
//                $day = getDayFromNum($dayOfWeekNum);
//                if ($calendar[$day] == '1') {
//                    // add to pairs
//                    $arrdepdate = date('Y-m-d', $date);
//                    $service = $calendar['serviceId'];
//                    $date_serviceIdsArray = addDateServiceId($date_serviceIdsArray, $arrdepdate, $service);
//                }
//            }
//        } else {
//            // No intersection with given parameters
//        }
//    }
//
//    // Parse calendarDates
//    $sql = "
//        SELECT *
//          FROM calendarDates
//    ";
//    $stmt = $entityManager->getConnection()->prepare($sql);
//    $stmt->execute();
//    $calendarDates = $stmt->fetchAll();
//
//    // Hopefully no memory problems
//    $date_serviceIdsArray = addCalendarDates($date_serviceIdsArray, $calendarDates);

    // We have now merged calendars and calendar_dates
    // Time for generating departures and arrivals
//    generateConnections($date_serviceIdsArray, $entityManager);
} else {
    // There are only calendarDates, so there can be a LOT of serviceIds
    // Check if start- and/or endDate is given as parameter
    // Otherwise set minimum and maximum of calendar_dates
    if (!isset($argv[1]) || !isset($argv[2])) {
        $sql = "
            SELECT MIN(date) startDate, MAX(date) endDate
              FROM calendarDates
        ";
        $stmt = $entityManager->getConnection()->prepare($sql);
        $stmt->execute();
        $startAndEndDate = $stmt->fetchAll();

        $startDate_ = $startAndEndDate[0]['startDate'];
        $endDate_ = $startAndEndDate[0]['endDate'];

        if (isset($argv[1])) {
            $startDate_ = $argv[1];
        }
        if (isset($arg[2])) {
            $endDate_ = $argv[2];
        }
    } else {
        $startDate_ = $argv[1];
        $endDate_ = $argv[2];
    }

    // loop all days between start_date and end_date
    // + day before interval for the stoptimes after midnight
    for ($date = strtotime('-1 day', strtotime($startDate_)); $date <= strtotime($endDate_); $date = strtotime('+1 day', $date)) {
        // Get service ids on that date
        $serviceIds = getServiceIdsOnDate($date, $entityManager);

        $AMOUNTOFSERVICES = 30;
        // Only process X serviceIds in a time to preserve memory
        for ($i=0; $i<count($serviceIds)/$AMOUNTOFSERVICES; $i++) {
            $serviceMatches = join(' , ', array_slice($serviceIds, $i * $AMOUNTOFSERVICES, $AMOUNTOFSERVICES));

            $tripData = getTripDataWithServiceIds($serviceMatches, $entityManager);

            $tripPointer = current($tripData);
            if ($date == strtotime('-1 day', strtotime($startDate_))) {
                $AMOUNTOFTRIPS = 20000; // There are not much stoptimes after midnight, so we can make this bigger
            } else if ($date == strtotime($endDate_)) {
                $AMOUNTOFTRIPS = 1000;
            } else {
                $AMOUNTOFTRIPS = 2000; // How much trips we'll fetch the stoptimes from, to prevent memory shortage
            }

            for ($j = 0; $j < count($tripData)/$AMOUNTOFTRIPS; $j++) {
                $t = 0;
                $tripMatches = [];
                while ($t < $AMOUNTOFTRIPS) {
                    $tripMatches[] = "'" . key($tripData) . "'";
                    next($tripData);
                    $t++;
                }

                // Make one string of tripIds for query
                $tripIdsString = join(' , ', array_slice($tripMatches, 0, $AMOUNTOFTRIPS));

                // Only stoptimes after midnight
                if ($date == strtotime('-1 day', strtotime($startDate_))) {
                    // Add stoptimes that depart after midnight
                    $stopTimes = queryStoptimesAfterMidnight($tripIdsString, $entityManager);
                    // these stoptimes happen actually on the next day because it's after midnight
                    stopTimesToConnections($stopTimes, $tripData, strtotime($startDate_));
                } else if ($date != strtotime($endDate_)) {
                    // Add all stoptimes
                    $stopTimes = queryStoptimes($tripIdsString, $entityManager);
                    stopTimesToConnections($stopTimes, $tripData, $date);
                } else {
                    // Only stoptimes before midnight
                    $stopTimes = queryStoptimesBeforeMidnight($tripIdsString, $entityManager);
                    stopTimesToConnections($stopTimes, $tripData, $date);
                }

                unset($stopTimes);
            }
        }

        unset($tripData);
        unset($serviceIds);
    }
}

// write MST of all stops to CSV file
writeMST($MST);

/**
 * @param $time
 * @param $entityManager
 * @return array Service ids on corresponding time.
 */
function getServiceIdsOnDate($time, $entityManager) {
    $sql = "
            SELECT serviceId
              FROM calendarDates
              WHERE date = ? AND exceptionType = 1
        ";
    $stmt = $entityManager->getConnection()->prepare($sql);
    $d = date('Y-m-d', $time);
    $stmt->bindParam(1, $d);
    $stmt->execute();
    $serviceIds = $stmt->fetchAll();

    // Add ' ' for query later
    $serviceIdsWithString = [];
    for ($j = 0; $j < count($serviceIds); $j++) {
        $serviceIdsWithString[] = "'" . $serviceIds[$j]['serviceId'] . "'";
    }

    return $serviceIdsWithString;
}

/**
 * @param $serviceIds Service ids encapsulated as string. (e.g.: 'trip_id')
 * @param $entityManager
 * @return array Array with tripId data. TripId as key. Trip as value.
 */
function getTripDataWithServiceIds($serviceIds, $entityManager) {
    $sql = "
                SELECT *
                  FROM trips
                  WHERE serviceId IN ( $serviceIds )
            ";

    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    $trips = $stmt->fetchAll();

    $tripData = []; // array with tripId as key and tripdata as value
    for($i=0; $i<count($trips); $i++) {
        $tripData[$trips[$i]['tripId']] = $trips[$i];
    }

    return $tripData;
}

/**
 * Queries stoptimes with certain tripIds.
 *
 * @param string $tripIdsString String of concatenated tripIds (of one day).
 * @param mixed $entityManager Entity manager of Doctrine.
 * @return array Stoptimes with corresponding tripId.
 */
function queryStoptimes($tripIdsString, $entityManager) {
    $sql = "
            SELECT tripId, arrivalTime, departureTime, stopId, connectionStopId
              FROM stoptimes
                -- JOIN stops
                --  ON stops.stopId = stoptimes.stopId
              WHERE stoptimes.tripId IN ( $tripIdsString )
        ";

    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Queries stoptimes with certain tripIds that happen after midnight.
 *
 * @param string $tripIdsString String of concatenated tripIds (of one day).
 * @param mixed $entityManager Entity manager of Doctrine.
 * @return array Stoptimes with corresponding tripId.
 */
function queryStoptimesAfterMidnight($tripIdsString, $entityManager) {
    $sql = "
            SELECT tripId, arrivalTime, departureTime, stopId, connectionStopId
              FROM stoptimes
                -- JOIN stops
                --  ON stops.stopId = stoptimes.stopId
              WHERE stoptimes.tripId IN ( $tripIdsString )
              AND stoptimes.departureAfterMidnight
        ";

    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Queries stoptimes with certain tripIds that happen before midnight.
 *
 * @param string $tripIdsString String of concatenated tripIds (of one day).
 * @param mixed $entityManager Entity manager of Doctrine.
 * @return array Stoptimes with corresponding tripId.
 */
function queryStoptimesBeforeMidnight($tripIdsString, $entityManager) {
    $sql = "
            SELECT tripId, arrivalTime, departureTime, stopId, connectionStopId
              FROM stoptimes
                -- JOIN stops
                --  ON stops.stopId = stoptimes.stopId
              WHERE stoptimes.tripId IN ( $tripIdsString )
              AND NOT stoptimes.departureAfterMidnight
        ";

    $stmt = $entityManager->getConnection()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Generates connections out of stoptimes and writes connections to file.
 *
 * @param $stopTimes Array of stoptimes.
 * @param $tripData Array of trips with tripId as key and trip as value.
 * @param $time Date in time format.
 */
function stopTimesToConnections($stopTimes, $tripData, $time) {
    global $connectionsFilename, $connectionsCounter;
    $localMST = []; // array of stops with [ other reachable stop_id, minimum time to get there from previous stop, arrival time stop_id]
    $j = 0; // Points to location to add new stop in MST

    if (count($stopTimes) > 0) {
        $prevStopTime = replaceWithConnectionStopId($stopTimes[0]); // We'll keep track of previous. Connection = departure stoptime1 + arrival stoptime2
        $localMST[$j] = [$prevStopTime['stopId'], 0, strtotime($prevStopTime['departureTime'])]; // Minimum time is amount of seconds between previous stop and current stop
        $j++;

        for ($i = 1; $i < count($stopTimes); $i++) {
            $stopTime = replaceWithConnectionStopId($stopTimes[$i]);

            // Same trip
            if ($stopTime['tripId'] === $prevStopTime['tripId']) {
                $localMST[] = [$stopTime['stopId'], strtotime($stopTime['arrivalTime']) - $localMST[$j-1][2], strtotime($stopTime['arrivalTime'])];
                $j++;
                writeToFile($connectionsFilename, generateConnection($prevStopTime, $stopTime, $tripData, $time, $connectionsCounter));
                $connectionsCounter++;
            } else {
                updateMST($localMST);
                $localMST = [];
                $j = 0; // reset
                $localMST[$j] = [$stopTime['stopId'], 0, strtotime($stopTime['departureTime'])];
                $j++;
            }

            $prevStopTime = $stopTime;
        }

        updateMST($localMST);
    }
}

/*
 * Updates the current MST of every stop if there is a faster travel time between two reachable stops
 */
function updateMST($localMST) {
    global $MST;

    for ($start=0; $start<count($localMST)-1; $start++) {
        $sumWeights = 0;
        for ($end=$start+1; $end<count($localMST); $end++) {
            $startStopId = $localMST[$start][0];
            $endStopId = $localMST[$end][0];
            $sumWeights += $localMST[$end][1];

            if (!isset($MST[$startStopId])) {
                $MST[$startStopId] = [];
            }

            // New minimum time
            if (!isset($MST[$startStopId][$endStopId]) || $sumWeights < $MST[$startStopId][$endStopId]) {
                $MST[$startStopId][$endStopId] = $sumWeights;
            }
        }
    }
}

/*
 * Replaces stop_id with connection_stop_id if available
 */
function replaceWithConnectionStopId($stopTime) {
    if ($stopTime['connectionStopId'] != null) {
        $stopTime['stopId'] = $stopTime['connectionStopId'];
    }
    return $stopTime;
}

/**
 * @param $stopTime1 Stoptime to depart from.
 * @param $stopTime2 Stoptime to arrive to.
 * @param $tripData Array of trips.
 * @param $time Date in time format.
 * @param int $connectionNr Number of connection. Represents a counter.
 * @return array Connection.
 */
function generateConnection($stopTime1, $stopTime2, $tripData, $time, $connectionNr) {
    $departureTime = date('Y-m-d', $time) . 'T' . $stopTime1['departureTime'] . '.000Z';
    $arrivalTime = date('Y-m-d', $time) . 'T' . $stopTime2['arrivalTime'] . '.000Z';

    return [
        '@type' => 'Connection',
        '@id' => 'connection:' . $connectionNr,
        'arrivalTime' => $arrivalTime,
        'arrivalStop' => $stopTime2['stopId'],
        'departureTime' => $departureTime,
        'departureStop' => $stopTime1['stopId'],
        'trip' => $stopTime2['tripId'],
        'route' => $tripData[$stopTime1['tripId']]['routeId'],
        'headsign' => $tripData[$stopTime1['tripId']]['tripHeadSign']
    ];
}

function createContext() {
    return [
        "@context"      =>
        [
            "gtfs"          => "http://vocab.gtfs.org/terms#",
            "trip"          => "gtfs:trip",
            "route"         => "gtfs:route",
            "headsign"      => "gtfs:headsign",
            "lc"            => "http://semweb.mmlab.be/ns/linkedconnections#",
            "arrivalStop"   => "lc:arrivalStop",
            "arrivalTime"   => "lc:arrivalTime",
            "departureStop" => "lc:departureStop",
            "departureTime" => "lc:departureTime",
            "Connection"    => "lc:Connection",
            "connection"    => "http://linkedconnections.org/connections/"
        ]
    ];
}

/**
 * Returns day of week in string representation.
 *
 * @param int $dayOfWeekNum Number that represents day of week, e.g. 1 for monday
 * @return string Day of week
 */
function getDayFromNum($dayOfWeekNum) {
    switch ($dayOfWeekNum) {
        case 1:
            return 'monday';
        case 2:
            return 'tuesday';
        case 3:
            return 'wednesday';
        case 4:
            return 'thursday';
        case 5:
            return 'friday';
        case 6:
            return 'saturday';
        case 7:
            return 'sunday';
    }
}

/**
 * Converts data to JSON and writes/appends this to specified filename.
 *
 * @param string $filename Name of file to write to.
 * @param mixed $data Data to write to file.
 */
function writeToFile($filename, $data) {
    $builder = new JsonBuilder();
    $builder->setJsonEncodeOptions(JSON_UNESCAPED_SLASHES);
    $builder->setValues($data);
    $json = $builder->build();

    file_put_contents($filename, $json.PHP_EOL, FILE_APPEND);
}

/*
 * Writes minimum spanning trees for every stop to a CSV file with following columns:
 * start_stop_id,end_stop_id,minimum_time
 */
function writeMST($MST) {
    global $mstFilename;

    // Add header
    $csv = 'start_stop_id,end_stop_id,minimum_time_seconds';
    appendCSV($mstFilename, $csv);

    foreach ($MST as $start_stop_id => $neighbours) {
        foreach ($neighbours as $neighbour_stop_id => $minimum_time) {
            $csv = $start_stop_id . ',' . $neighbour_stop_id . ',' . $minimum_time;
            appendCSV($mstFilename, $csv);
        }
    }
}

function appendCSV($dist, $csv) {
    file_put_contents($dist, trim($csv).PHP_EOL, FILE_APPEND);
}