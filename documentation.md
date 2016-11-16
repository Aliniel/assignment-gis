*This is a documentation for a fictional project, just to show you what I expect. Notice a few key properties:*
- *no cover page, really*
- *no copy&pasted assignment text*
- *no code samples*
- *concise, to the point, gets me a quick overview of what was done and how*
- *I don't really care about the document length*
- *I used links where appropriate*

# Overview

The app find the optimal appartment building based on user input. The most important features are:
* add points of interest (POI) you want near your apartment,
* specify name for that POI,
* specify the distance in meters for every POI - the max distance the POI can be from the apartment

App screenshot (GUI improvements planned):
![Screenshot of the app.](http://image.prntscr.com/image/e0351896493c496db1cca55c97a59fd9.png)

The application has two parts: [front-end](#Front-end) which is built upon _[Mapbox GL API](https://www.mapbox.com/mapbox-gl-js/api/)_ and the [back-end](#Back-end) written in _PHP_ which runs on the _Apache Web Server_. The data is stored in _[PostgreSQL](https://www.postgresql.org/)_ databse with the _[PostGIS](http://www.postgis.net/)_ extension. The communication between the fron-end and back-end is handled with _JavaScript_ and _AJAX_.

# Front-end

The front-end application is a static web page (`index.php`). It shows the mapbox.js widget main manu for the user input. The map is set to display Bratislava by default but the API supports whole world (data is howeever available only for Slovakia).

The map initialization is handled in an external JavaScript file (`js\mapbox-init.js`). This script handles user input, feeds the GeoJSON provided by back-end (with required modifications) to the map API and handles the map clearings. Secondary JavaScript file (`js\gui-optimus.js`) contains GUI scripts for better User Experience. Custom CSS is also separated in a different file (`css\domus-optimus.css`);

In the current version only the optimal appartment buildings are highlighted after the queries are processed.

# Back-end

The back-end is written in PHP and it's running on the Apache Web Server. It is mainly responsible for running database queries. These queries are dynamically built based on the user input passed down from the JavaScript. The query is built from the most inner SELECT which selects appartment building and joins the first POI. Then the outter SELECTs are built. Only the most outter SELECT generates GeoJSON.

## Data

The app is using data aquired from the [OpenStreetMap](https://www.openstreetmap.org/), more specifically, from the [Geofabrik](http://download.geofabrik.de/). I downloaded data covering whole Slovakia and imported them into PostGIS enabled PostgreSQL database using [osm2pgsql](http://wiki.openstreetmap.org/wiki/Osm2pgsql) tool.

Indexes were automatically created on the geometry during the import. Howeevr, my queries are using casting to geography (::geography) in the `ST_DWITHIN` function. This is because geography supports meters as a distance metric. To speed these queries up, I created additional index on the `way::geography` column. This reduced the query times by approximaltely tenfold.

**NOTE:** Early prototypes were heavily using `ST_BUFFER` and `ST_INTERSECTION` functions. These were removed in favor of `ST_DWITHIN` because of their high computational demands.
