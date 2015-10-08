<?php

require_once "bootstrap.php";

date_default_timezone_set('UTC');

$file_stop_times = $argv[1];

if (($handleRead = fopen($file_stop_times, 'r')) !== false) {
    // header
    // important to know which column data is provided
    $header = fgetcsv($handleRead);
    $column_count = count($header);

    $row = 1; // row_counter
    $maxStopSequence = 1; // keeps track of highest stopSequence per trip
    $currentTripId = null; // trip that is currently processed
    $stopTimesOfSameTrip = []; // stop_times.txt is very big, so we flush after every trip

    // loop through the file line-by-line
    while (($line = fgetcsv($handleRead)) !== false) {
        $stopTime = new Stoptime();

        for ($i = 0; $i < $column_count; $i++) {
            if (!array_key_exists($i, $line)) {
                save($stopTime,$header[$i], '');
            } else {
                save($stopTime, $header[$i], $line[$i]);
            }
        }

        // First line
        if ($currentTripId === null) {
            $currentTripId = $stopTime->getTripId();
            $stopTime->setMaxStopSequence($stopTime->getStopSequence());
        }

        // Update maxStopSequence when still the same trip
        if ($stopTime->getTripId() === $currentTripId) {
            if ($stopTime->getStopSequence() > $maxStopSequence) {
                $maxStopSequence = $stopTime->getStopSequence();
            }
        } else {
            // New trip started so update previous one
            $stopTimesOfSameTrip = sortStopTimes($stopTimesOfSameTrip, $maxStopSequence);
            updateMaxStopSequenceAndAfterMidnightOfStopTimes($stopTimesOfSameTrip, $maxStopSequence);
            persistAndFlushStopTimes($entityManager, $stopTimesOfSameTrip);
            $stopTimesOfSameTrip = []; // new trip -> new stoptimes sequence
            $currentTripId = $stopTime->getTripId();
            $maxStopSequence = 1;
        }

        $stopTimesOfSameTrip[] = $stopTime;

        unset($line);

        $row++;
    }

    fclose($handleRead);

    $stopTimesOfSameTrip = sortStopTimes($stopTimesOfSameTrip, $maxStopSequence);
    updateMaxStopSequenceAndAfterMidnightOfStopTimes($stopTimesOfSameTrip, $maxStopSequence);
    persistAndFlushStopTimes($entityManager, $stopTimesOfSameTrip);

    echo "Created " . $row . " stoptimes" . "\n";
} else {
    echo "Something went wrong with reading stop_times.txt" . "\n";
}

/**
 * Updates maxStopSequence field of stopTimes.
 *
 * @param array $stopTimes Array of stopTimes.
 * @param int $maxStopSequence Maximum stopSequence number.
 */
function updateMaxStopSequenceAndAfterMidnightOfStopTimes($stopTimes, $maxStopSequence) {
    $arrivalAfterMidnight = false; // there is always a transition of dates
    $departureAfterMidnight = false;
    $prevStopTime = null;

    for ($i = 0; $i < count($stopTimes); $i++) {
        $stopTime = $stopTimes[$i];
        save($stopTime, 'max_stop_sequence', $maxStopSequence);

        // Check arrivalTime if after midnight
        if ($arrivalAfterMidnight) {
            save($stopTime, 'arrival_after_midnight',$arrivalAfterMidnight);
        } else if ($i > 0 && $stopTime->getArrivalTime()->format('H') < $prevStopTime->getArrivalTime()->format('H')) {
            $arrivalAfterMidnight = true;
        }

        save($stopTime, 'arrival_after_midnight',$arrivalAfterMidnight);

        // Check departureTime if after midnight
        if ($departureAfterMidnight) {
            save($stopTime, 'departure_after_midnight',$departureAfterMidnight);
        } else if ($i > 0 && $stopTime->getDepartureTime()->format('H') < $prevStopTime->getDepartureTime()->format('H')) {
            $departureAfterMidnight = true;
        }

        save($stopTime, 'departure_after_midnight',$departureAfterMidnight);

        $prevStopTime = $stopTime;
    }
}

/**
 * Sorts array of stoptimes by stopSequence.
 *
 * @param array $stopTimes Array of stoptimes.
 * @param int $maxStopSequence Maximum stop sequence.
 * @return array $sortedStopTimes Array of stoptimes that are ordered by stopSequence.
 */
function sortStopTimes($stopTimes, $maxStopSequence) {
    $sortedStopTimes = [];

    for ($stopSequence = 1; $stopSequence <= $maxStopSequence; $stopSequence++) {
        // Loop through stoptimes to find stoptime with given stopSequence
        // Supposes are no holes in stopsequences
        for ($i = 0; $i < count($stopTimes); $i++) {
            if ($stopTimes[$i]->getStopSequence() == $stopSequence) {
                $sortedStopTimes[] = $stopTimes[$i];
            }

            if ($stopTimes[$i]->getStopSequence() == null) {
                var_dump($stopTimes[$i]);
            }
        }
    }

    return $sortedStopTimes;
}

/**
 * Save the stopTimes in the database.
 *
 * @param mixed $entityManager EntityManager of Doctrine.
 * @param array $stopTimes Array of stopTimes.
 */
function persistAndFlushStopTimes($entityManager, $stopTimes) {
    for ($i = 0; $i < count($stopTimes); $i++) {
        $stopTime = $stopTimes[$i];
        $entityManager->persist($stopTime);
    }

    $entityManager->flush();
    $entityManager->clear(); // Detaches all objects from Doctrine
}

/**
 * Updates property of a stopTime with given value.
 *
 * @param object $stoptime GTFS stopTime.
 * @param string $property Property of stopTime that needs to be updated.
 * @param mixed $value Value of the property that needs to be updated.
 */
function save($stoptime, $property, $value) {
    switch ($property) {
        case 'trip_id':
            $stoptime->setTripId($value);
            break;
        case 'arrival_time':
            $arrival_time = \DateTime::createFromFormat('H:i:s', $value);
            $stoptime->setArrivalTime($arrival_time);
            break;
        case 'departure_time':
            $departure_time = \DateTime::createFromFormat('H:i:s', $value);
            $stoptime->setDepartureTime($departure_time);
            break;
        case 'stop_id':
            $stoptime->setStopId($value);
            break;
        case 'stop_sequence':
            $stoptime->setStopSequence($value);
            break;
        case 'max_stop_sequence':
            $stoptime->setMaxStopSequence($value);
            break;
        case 'arrival_after_midnight':
            $stoptime->setArrivalAfterMidnight($value);
            break;
        case 'departure_after_midnight':
            $stoptime->setDepartureAfterMidnight($value);
            break;
        case 'stop_headsign':
            $stoptime->setStopHeadsign($value);
            break;
        case 'pickup_type':
            $stoptime->setPickupType($value);
            break;
        case 'drop_off_type':
            $stoptime->setDropOffType($value);
            break;
        case 'shape_dist_traveled':
            $stoptime->setShapeDistTraveled($value);
            break;
        case 'timepoint':
            $stoptime->setTimepoint($value);
            break;
    }
}