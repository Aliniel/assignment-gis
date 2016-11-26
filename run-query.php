<?php
    function runQuery($query) {
        // Connecting, selecting database
        $dbconn = pg_connect("host=localhost dbname=domus-optimus user=postgres password=postgres")
            or die('Could not connect: ' . pg_last_error());

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

            // Free resultset
            pg_free_result($result);

            // Closing connection
            pg_close($dbconn);

            return $geojson;
        }
    }
?>
