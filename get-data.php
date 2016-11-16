<?php

/* Return table name for given param */
function getTable($param) {
    switch ($param) {
    case "shop":
        return "planet_osm_polygon";
    case "post_office":
        return "planet_osm_point";
    case "amt":
        return "planet_osm_point";
    case "bus_stops":
        return "planet_osm_point";
    default:
        retun;
    }
}

/* Return condition for given param */
function getCondition($param) {
    switch ($param) {
    case "shop":
        return "p.shop = 'supermarket'";
    case "post_office":
        return "p.amenity = 'post_office'";
    case "amt":
        return "p.amenity = 'atm'";
    case "bus_stops":
        return "p.highway = 'bus_stop'";
    default:
        retun;
    }
}

/* Build the base select - the most inner one. */
function buildBaseQuery($param, $name, $range) {
    $query = "FROM planet_osm_polygon s ";
    $query .= "JOIN " . getTable($param) . " p ";
    $query .= "ON ST_DWITHIN(s.way::geography, p.way::geography, " . $range . ") ";
    $query .= "WHERE s.building = 'apartments' ";
    $query .= "AND " . getCondition($param) . " ";
    if ($name != ""){
        $query .= "AND p.name = '" . $name . "' ";
    }
    return $query;
}

/* Build second part of query - JOIN, conditions... */
function buildQueryEnding($param, $name, $range) {
    $query = ") AS s ";
    $query .= "JOIN " . getTable($param) . " p ";
    $query .= "ON ST_DWITHIN(s.way::geography, p.way::geography, " . $range . ") ";
    $query .= "WHERE " . getCondition($param) . " ";
    if ($name != ""){
        $query .= "AND p.name = '" . $name . "' ";
    }
    return $query;
}

/* Get number of parameters */
$paramNum = $_GET["paramNum"];
$query = "";

for ($i = 0; $i < $paramNum; $i++) {
    $param = $_GET["param" . $i];
    $name = $_GET["name" . $i];
    $range = $_GET["range" . $i];

    /* Core SELECT */
    if ($i == 0) {
        if ($paramNum == 1) {
            $query .= "SELECT DISTINCT ON (s.osm_id) ST_AsGeoJSON(s.way) ";
        }
        else {
            $query .= "SELECT DISTINCT ON (s.osm_id) s.osm_id, s.way ";
        }
        $query .= buildBaseQuery($param, $name, $range);
    }
    /* Final SELECT -> exporting to GeoJSON */
    else if ($i == $paramNum - 1) {
        $query = "SELECT DISTINCT ON (s.osm_id) ST_AsGeoJSON(s.way) FROM ( " . $query;
        $query .= buildQueryEnding($param, $name, $range);
    }
    /* Inter SELECTs and JOINs */
    else {
        $query = "SELECT DISTINCT ON (s.osm_id) s.osm_id, s.way FROM ( " . $query;
        $query .= buildQueryEnding($param, $name, $range);
    }
}

// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=domus-optimus user=postgres password=postgres")
    or die('Could not connect: ' . pg_last_error());

// echo $query;

/* Run query */
if ($query != "") {
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
}

if ($result != ""){
    // Passing GeoJSON rows from database to JavaScript
    $rows = pg_fetch_all_columns($result, pg_field_num('geojson'));

    // Printing results in HTML
    $geojson = '{"type": "FeatureCollection","features": [';

    foreach ($rows as &$row) {
        $geojson .= '{"type": "Feature","geometry":' . $row . '},';
    }
    $geojson = rtrim($geojson, ",");
    $geojson .= ']}';

    echo $geojson;
}

// Free resultset
pg_free_result($result);

// Closing connection
pg_close($dbconn);
?>
