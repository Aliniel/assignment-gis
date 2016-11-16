# General course assignment

Build a map-based application, which lets the user see geo-based data on a map and filter/search through it in a meaningfull way. Specify the details and build it in your language of choice. The application should have 3 components:

1. Custom-styled background map, ideally built with [mapbox](http://mapbox.com). Hard-core mode: you can also serve the map tiles yourself using [mapnik](http://mapnik.org/) or similar tool.
2. Local server with [PostGIS](http://postgis.net/) and an API layer that exposes data in a [geojson format](http://geojson.org/).
3. The user-facing application (web, android, ios, your choice..) which calls the API and lets the user see and navigate in the map and shows the geodata. You can (and should) use existing components, such as the Mapbox SDK, or [Leaflet](http://leafletjs.com/).

## Example projects

- Showing nearby landmarks as colored circles, each type of landmark has different circle color and the more interesting the landmark is, the bigger the circle. Landmarks are sorted in a sidebar by distance to the user. It is possible to filter only certain landmark types (e.g., castles).

- Showing bicykle roads on a map. The roads are color-coded based on the road difficulty. The user can see various lists which help her choose an appropriate road, e.g. roads that cross a river, roads that are nearby lakes, roads that pass through multiple countries, etc.

## Data sources

All the data used in the app are aquired from the [Open Street Maps](https://www.openstreetmap.org/).

## My project

More complete documentation can be found [here](documentation.md).

### Application description

The application's purpose is to help users find their optimal appartment buildings. The user can input multiple points of interest they want near their appartment. The app will then highlight the optimal appartment buildings that fit these criteria.

**Possible scenario:** The user wants following points of interest near his appartment:
* Lidl supermarket at 500 meters,
* Post Office at 1000 meters,
* any ATM machine at 300 meters,
* a bus station at 200 meters.

### Data source

All the data used in this app are from the **OpenStreetMap**.

### Technologies used

The app consists of a front-end and a back-end.

The front-end is written in _HTML5_, styled with _CSS_ with a help of _JavaScript_ and _jQuery_ library. For the map itself, I've used an API from Mapbox, called _Mapbox GL JS_.

The back-end is written in _PHP_ running on the _Apache_ web server. The data is saved in _PostgreSQL_ with _PostGIS_ extension.
