<html>
    <head>
        <meta charset="utf-8"/>
        <!-- CSS -->
        <link href='https://api.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.css' rel='stylesheet'/>
        <link href='css/domus-optimus.css' rel='stylesheet'/>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
        <!-- End of CSS -->
    </head>

    <body>
        <div>

            <!-- Main Menu -->
            <div id='main-menu'>
                <select id="city-dropdown" name="city" class="dropdown-list">
                    <option selected value="Bratislava">Bratislava</option>
                    <option value="Košice">Košice</option>
                    <option value="Žilina">Žilina</option>
                    <option value="Banská Bystrica">Banská Bystrica</option>
                </select>

                <form>
                    <div class="option-group">
                        <select name="select-1" class="dropdown-list" onchange="limitParkRange(this)">
                            <option selected value=""> -- Select Point of Interest -- </option>
                            <option value="supermarket">Supermarket</option>
                            <option value="post_office">Post Office</option>
                            <option value="atm">ATM</option>
                            <option value="bus_stops">Bus Stop</option>
                            <option value="park">Park</option>
                        </select>
                        <input type="text" name="input-1" class="input-field"></input>
                        <div>
                            Distance:
                            <input type="range" name="range-1" value="200" min="50" max="1000" step="10" oninput="rangeOutputUpdate(this)">
                            <output>200</output>m
                        </div>
                    </div>

                    <i id="add-button" class="fa fa-plus-circle fa-2x" aria-hidden="true" onclick="addOptionGroup()"></i>
                    <br/>

                    <input id="submit-button" type="button" name="button" onclick="processData()" value="Submit"/>
                    <input id="reset-button"type="reset" name="reset" onclick="clearMap();removeOptionGroups()" value="Reset"/>
                </form>
            </div>
            <!-- End of Main Menu -->

            <div id='map' style='width: 85%; height: 100%;'></div>
        </div>

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src='https://api.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.js'></script>
        <script src="js/mapbox-init.js"></script>
        <script src="js/gui-optimus.js"></script>
        <!-- End of Scripts -->
    </body>
</html>
