<?php

include "run-query.php";

/* These functions return pins location for points of interest. */
function getPostOffices($city) {
    $local_query = "(
        SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_point as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS s
        ON ST_WITHIN(p.way,s.city_geo)
        WHERE p.amenity = 'post_office'
    )
    UNION ALL
    (
        SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_polygon as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS s
        ON ST_WITHIN(p.way,s.city_geo)
        WHERE p.amenity = 'post_office'
    )";
    return runQuery($local_query);
}

function getBusStations($city) {
    $local_query = "SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_point as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS s
        ON ST_WITHIN(p.way,s.city_geo)
        WHERE highway = 'bus_stop'";
    return runQuery($local_query);
}

function getATMs($city, $name) {
    $local_query = "SELECT ST_AsGeoJSON(p.way)
    FROM planet_osm_point as p
    JOIN (SELECT city_geo FROM city_geo('Bratislava')) AS s
    ON ST_WITHIN(p.way,s.city_geo)
    WHERE p.amenity = 'atm' ";
    if ($name != "") {
        $local_query .= "AND p.name LIKE '" . $name . "'";
    }

    return runQuery($local_query);
}

function getSupermarkets($city, $name) {
    $local_query = "(
        SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_point as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS s
        ON ST_WITHIN(p.way,s.city_geo)
        WHERE p.shop = 'supermarket'";
    if ($name != "") {
        $local_query .= "AND p.name LIKE '" . $name . "')";
    }
    else {
        $local_query .= ")";
    }
    $local_query .= "UNION ALL (
        SELECT ST_AsGeoJSON(p.way)
        FROM planet_osm_polygon as p
        JOIN (SELECT city_geo FROM city_geo('" . $city . "')) AS s
        ON ST_WITHIN(p.way,s.city_geo)
        WHERE p.shop = 'supermarket' ";
    if ($name != "") {
        $local_query .= "AND p.name LIKE '" . $name . "')";
    }
    else {
        $local_query .= ")";
    }
    return runQuery($local_query);
}

/* Get number of parameters */
$paramNum = $_GET["paramNum"];
$query = "";
$results = array();

for ($i = 0; $i < $paramNum; $i++) {
    $param = $_GET["param" . $i];
    $name = $_GET["name" . $i];

    /* SELECT pins for map. */
    switch ($param) {
    case "supermarket":
        $results["supermarket"] = getSupermarkets("Bratislava", $name);
        break;
    case "post_office":
        $results["post_office"] = getPostOffices("Bratislava");
        break;
    case "atm":
        $results["atm"] = getATMs("Bratislava");
    }
}

echo json_encode($results);

?>
