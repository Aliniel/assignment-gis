/* Initializing the Mapbox Map */
mapboxgl.accessToken = 'pk.eyJ1IjoiYWxpbmllbCIsImEiOiJjaXVxcHNqcDEwMDA5Mm9wZ2o2NGgwdTZuIn0.2qkLzYY98cu9j3S0uHV6Vw';
var map = new mapboxgl.Map({
    container: 'map',
    // style: 'mapbox://styles/mapbox/streets-v9',
    style: 'mapbox://styles/mapbox/light-v9',
    /* Center to Bratislava */
    center: [17.12,48.15],
    zoom: 12
});

var activeLayers = [];

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

            /* Add sources for pins */
            setPins(geoJson.supermarket, "supermarket", "shop-15");
            setPins(geoJson.post_office, "post_office", "post-15");
            setPins(geoJson.atm, "atm", "bank-15");
            setPins(geoJson.bus_stops, "bus_stops", "bus-15");

            /* Add sources for areas */
            setArea(geoJson.park, "park", "rgba(0,255,0,0.3)");

            /* Define source for building appartments */
            if (map.getSource("optimal-buildings") == undefined) {
                map.addSource("optimal-buildings", {
                    "type": "geojson",
                    "data": JSON.parse(geoJson.building)
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
                      'fill-color': 'rgba(255,0,0,0.3)'
                    }
                });
            }
            /* Update source */
            else{
                map.removeSource("optimal-buildings");
                map.addSource("optimal-buildings", {
                    "type": "geojson",
                    "data": JSON.parse(geoJson.building)
                });
            }
        }
    }

    /* Get user input */
    var city = $("#city-dropdown").val();
    var selects = $("select:not('#city-dropdown')");
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
    params = "?city=" + city + "&paramNum=" + paramNum + params;
7
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

    for (layer in activeLayers) {
        if (map.getLayer(activeLayers[layer] + "-layer") != undefined){
            map.setLayoutProperty(activeLayers[layer] + "-layer", 'visibility', 'none');
        }
    }
}

/* Add source and layer for Points of interest */
function setPins(geoJson, type, icon) {
    if (geoJson == undefined) {
        if (map.getSource(type) != undefined) {
            map.setLayoutProperty(type + "-layer", 'visibility', 'none');
        }
        return;
    }
    if (map.getSource(type) == undefined) {
        map.addSource(type, {
            "type": "geojson",
            "data": JSON.parse(geoJson)
        });
        map.addLayer({
            id: type + "-layer",
            type: 'symbol',
            source: type,
            "source-layer": type,
            layout: {
              'icon-image': icon,
              'icon-allow-overlap': true
            }
        });
        activeLayers.push(type);
    }
    /* Clear Map */
    else{
        map.removeSource(type);
        map.addSource(type, {
            "type": "geojson",
            "data": JSON.parse(geoJson)
        });
        map.setLayoutProperty(type + "-layer", 'visibility', 'visible');
    }
}

/* Add source and layer for Areas of interest */
function setArea(geoJson, type, color) {
    if (geoJson == undefined) {
        if (map.getSource(type) != undefined) {
            map.setLayoutProperty(type + "-layer", 'visibility', 'none');
        }
        return;
    }
    if (map.getSource(type) == undefined) {
        map.addSource(type, {
            "type": "geojson",
            "data": JSON.parse(geoJson)
        });
        map.addLayer({
            id: type + "-layer",
            type: 'fill',
            source: type,
            "source-layer": type,
            layout: {
              visibility: 'visible'
            },
            paint: {
              'fill-color': color
            }
        });
        activeLayers.push(type);
    }
    /* Clear Map */
    else{
        map.removeSource(type);
        map.addSource(type, {
            "type": "geojson",
            "data": JSON.parse(geoJson)
        });
        map.setLayoutProperty(type + "-layer", 'visibility', 'visible');
    }
}
