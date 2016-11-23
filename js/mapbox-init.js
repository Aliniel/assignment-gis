/* Initializing the Mapbox Map */
mapboxgl.accessToken = 'pk.eyJ1IjoiYWxpbmllbCIsImEiOiJjaXVxcHNqcDEwMDA5Mm9wZ2o2NGgwdTZuIn0.2qkLzYY98cu9j3S0uHV6Vw';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v9',
    /* Center to Bratislava */
    center: [17.12,48.15],
    zoom: 12
});

/* Get user input and run the query */
function processData(){
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            /* If no result was found, clear map data source */
            if (this.responseText == "") {
                clearMap();
                return;
            }

            /* Parse GeoJSON output from PHP/PostGIS */
            geoJson = JSON.parse(this.responseText);

            /* Define source */
            if (map.getSource("optimal-buildings") == undefined) {
                map.addSource("optimal-buildings", {
                    "type": "geojson",
                    "data": geoJson
                });
                map.addLayer({
                    id: "optimal-buildings-layer",
                    type: "fill",
                    source: "optimal-buildings",
                    "source-layer": "optimal-buildings",
                    layout: {
                      visibility: 'visible'
                    },
                    paint: {
                      'fill-color': 'rgba(24,152,212,0.8)'
                    }
                });
            }
            /* Update source */
            else{
                map.removeSource("optimal-buildings");
                map.addSource("optimal-buildings", {
                    "type": "geojson",
                    "data": geoJson
                });
            }
        }
    }

    /* Get user input */
    var selects = $("select");
    var names = $("input[type='text']");
    var ranges = $("input[type='range']");

    var i, paramNum, n = selects.length;
    var params = "";

    /* Append user input to GET URL */
    for (i = 0, paramNum = 0; i < n; i++){
        if($(selects[i]).val() != ""){
            params += "&param" + paramNum + "=" + $(selects[i]).val();
            params += "&name" + paramNum + "=" + $(names[i]).val();
            params += "&range" + paramNum + "=" + $(ranges[i]).val();
            paramNum ++;
        }
    }
    params = "?paramNum=" + paramNum + params;

    /* Send request to PHP */
    xmlhttp.open("GET", "get-data.php" + params, true);
    xmlhttp.send();
}

/* Clear the map */
function clearMap(){
    if (map.getSource("optimal-buildings") != undefined){
        map.removeSource("optimal-buildings");
        map.removeLayer("optimal-buildings-layer");
    }
}
