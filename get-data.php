<?php

include "run-query.php";

/* Return table name for given param */
function getTable($param) {
    switch ($param) {
    case "supermarket":
        return "planet_osm_polygon";
    case "post_office":
        return "planet_osm_point";
    case "atm":
        return "planet_osm_point";
    case "bus_stops":
        return "planet_osm_point";
    case "park":
        return "planet_osm_polygon";
    default:
        retun;
    }
}

/* Return condition for given param */
function getCondition($param) {
    switch ($param) {
    case "supermarket":
        return "p.shop = 'supermarket'";
    case "post_office":
        return "p.amenity = 'post_office'";
    case "atm":
        return "p.amenity = 'atm'";
    case "bus_stops":
        return "p.highway = 'bus_stop'";
    case "park":
        return "p.leisure = 'park'";
    default:
        retun;
    }
}

/* Build the base select - the most inner one. */
function buildBaseQuery($city, $param, $name, $range) {
    $query = "FROM planet_osm_polygon s ";
    $query .= "JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS c ";
    $query .= "ON ST_WITHIN(s.way,c.city_geo) ";
    $query .= "JOIN " . getTable($param) . " p ";
    $query .= "ON ST_DWITHIN(s.way::geography, p.way::geography, " . $range . ") ";
    $query .= "WHERE s.building = 'apartments' ";
    $query .= "AND " . getCondition($param) . " ";
    $query .= "AND ST_WITHIN(p.way,c.city_geo) ";
    if ($name != ""){
        $query .= "AND p.name = '" . $name . "' ";
    }
    return $query;
}

/* Build second part of query - JOIN, conditions... */
function buildQueryEnding($city, $param, $name, $range) {
    $query = ") AS s ";
    $query .= "JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS c ";
    $query .= "ON ST_WITHIN(s.way,c.city_geo) ";
    $query .= "JOIN " . getTable($param) . " p ";
    $query .= "ON ST_DWITHIN(s.way::geography, p.way::geography, " . $range . ") ";
    $query .= "WHERE " . getCondition($param) . " ";
    $query .= "AND ST_WITHIN(p.way,c.city_geo) ";
    if ($name != ""){
        $query .= "AND p.name = '" . $name . "' ";
    }
    return $query;
}

/* These functions return pins location for points of interest. */
function getPostOffices($city) {
    $local_query = "(
        SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_point as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS c
        ON ST_WITHIN(p.way,c.city_geo)
        WHERE p.amenity = 'post_office'
    )
    UNION ALL
    (
        SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_polygon as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS c
        ON ST_WITHIN(p.way,c.city_geo)
        WHERE p.amenity = 'post_office'
    )";
    return runQuery($local_query);
}

function getBusStations($city) {
    $local_query = "SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_point as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS c
        ON ST_WITHIN(p.way,c.city_geo)
        WHERE highway = 'bus_stop'";
    return runQuery($local_query);
}

function getATMs($city, $name) {
    $local_query = "SELECT ST_AsGeoJSON(p.way)
    FROM planet_osm_point as p
    JOIN (SELECT city_geo FROM city_geo('Bratislava')) AS c
    ON ST_WITHIN(p.way,c.city_geo)
    WHERE p.amenity = 'atm' ";

    if ($name != "") {
        $local_query .= "AND p.operator LIKE '%" . $name . "%'";
    }
    return runQuery($local_query);
}

function getSupermarkets($city, $name) {
    $local_query = "(
        SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_point as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS c
        ON ST_WITHIN(p.way,c.city_geo)
        WHERE p.shop = 'supermarket'";
    if ($name != "") {
        $local_query .= "AND p.name LIKE '%" . $name . "%')";
    }
    else {
        $local_query .= ")";
    }
    $local_query .= "UNION ALL (
        SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_polygon as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS c
        ON ST_WITHIN(p.way,c.city_geo)
        WHERE p.shop = 'supermarket' ";
    if ($name != "") {
        $local_query .= "AND p.name LIKE '" . $name . "')";
    }
    else {
        $local_query .= ")";
    }
    return runQuery($local_query);
}

function getParks($city) {
    $local_query = "SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_polygon as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS c
        ON ST_WITHIN(p.way,c.city_geo)
        WHERE p.leisure = 'park'";
    return runQuery($local_query);
}

/* Get number of parameters */
$paramNum = $_GET["paramNum"];
$city = $_GET["city"];
$query = "";
$results = array();

for ($i = 0; $i < $paramNum; $i++) {
    $param = $_GET["param" . $i];
    $name = $_GET["name" . $i];
    $range = $_GET["range" . $i];

    /* SELECT pins for map. */
    switch ($param) {
    case "supermarket":
        $results["supermarket"] = getSupermarkets($city, $name);
        break;
    case "post_office":
        $results["post_office"] = getPostOffices($city);
        break;
    case "atm":
        $results["atm"] = getATMs($city, $name);
        break;
    case "bus_stops":
        $results["bus_stops"] = getBusStations($city);
        break;
    case "park":
        $results["park"] = getParks($city);
        break;
    }

    /* Core SELECT */
    if ($i == 0) {
        if ($paramNum == 1) {
            $query .= "SELECT DISTINCT ON (s.osm_id) ST_AsGeoJSON(s.way) ";
        }
        else {
            $query .= "SELECT DISTINCT ON (s.osm_id) s.osm_id, s.way ";
        }
        $query .= buildBaseQuery($city, $param, $name, $range);
    }
    /* Final SELECT -> exporting to GeoJSON */
    else if ($i == $paramNum - 1) {
        $query = "SELECT DISTINCT ON (s.osm_id) ST_AsGeoJSON(s.way) FROM ( " . $query;
        $query .= buildQueryEnding($city, $param, $name, $range);
    }
    /* Inter SELECTs and JOINs */
    else {
        $query = "SELECT DISTINCT ON (s.osm_id) s.osm_id, s.way FROM ( " . $query;
        $query .= buildQueryEnding($city, $param, $name, $range);
    }
}

$results["building"] = runQuery($query);
echo json_encode($results);

?>
