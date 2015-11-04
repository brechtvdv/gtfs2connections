# gtfs2connections
Transform GTFS feed into an unordered [JSON-LD stream](https://github.com/pietercolpaert/jsonld-stream) of connections.

This project is written in PHP and uses [doctrine](http://www.doctrine-project.org/) to load the GTFS files into a MySQL database.

Note: only feeds with pure calendar_dates.txt are supported for now

## Connection

A connection tells when a vehicle starts and ends with respectively a different stop.

Example:
```json
{
    "@type":"Connection",
    "@id":"connection:130660",
    "arrivalTime":"2015-10-02T12:12:00.000Z",
    "arrivalStop":"34604",
    "departureTime":"2015-10-02T12:09:00.000Z",
    "departureStop":"88863",
    "trip":"14865676",
    "route":"24332",
    "headsign":"Tongeren - Vroenhoven"
}
```

## Requirements

    * Composer
    * MySQL
    * PHP 5.4+

## Install

We use the PHP package manager [Composer](http://getcomposer.org). Make sure it's installed and then run from this directory:

```bash
composer install
```

## Generating connections

### Step 1: Setup database configuration

Fill in your MySQL credentials inside ```db-config.php```.

### Step 2: Run database load script

```bash
scripts/init.sh path-to-gtfs.zip
```

### (Optional) Step 3: Load connection stop_ids
To enable interoperability between different operators, generate a CSV-file that maps neighbour stops to one "connection stop":
[connection stops](https://github.com/brechtvdv/gtfs-connectionstops)

```bash
php scripts/load_connection_stops.php connection-stops.txt
```

### Step 4: Run connections generator script

```bash
php scripts/create_connections.php [startDate] [endDate]
```

The format of date parameters must be 'YYYY-MM-DD'.

Note: optional parameters ```startDate``` and ```endDate``` only work when there are no calendars.

### Step 5: Done

You can find connections-[agency_id].jsonldstream in ```dist``` folder.
