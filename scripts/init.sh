#!/bin/bash

# Read database configuration
USERNAME=`cat db-config.php | grep username | cut -d \' -f 4`
PASSWORD=`cat db-config.php | grep password | cut -d \' -f 4`
DATABASE=`cat db-config.php | grep database | cut -d \' -f 4`

# Create database
mysql --local-infile --user=$USERNAME --password=$PASSWORD < scripts/create_database.sql

# Create datatables
vendor/bin/doctrine orm:schema-tool:update --force --dump-sql

# Unzip GTFS files to temporary directory
GTFS_PATH=$1

rm -rf /tmp/gtfs2arrdep
unzip $GTFS_PATH -d /tmp/gtfs2arrdep

# Load data into tables
## agency.txt
php scripts/load_agency.php /tmp/gtfs2arrdep/agency.txt

## stops.txt
php scripts/load_stops.php /tmp/gtfs2arrdep/stops.txt

## routes.txt
php scripts/load_routes.php /tmp/gtfs2arrdep/routes.txt

## trips.txt
php scripts/load_trips.php /tmp/gtfs2arrdep/trips.txt

## stop_times.txt
php scripts/load_stop_times.php /tmp/gtfs2arrdep/stop_times.txt

## calendar.txt
php scripts/load_calendar.php /tmp/gtfs2arrdep/calendar.txt

## calendar_dates.txt
php scripts/load_calendar_dates.php /tmp/gtfs2arrdep/calendar_dates.txt

## frequencies.txt
php scripts/load_frequencies.php /tmp/gtfs2arrdep/frequencies.txt