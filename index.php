<html>
    <head>
        <!-- CSS -->
        <link href='https://api.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.css' rel='stylesheet'/>
        <link href='css/domus-optimus.css' rel='stylesheet'/>
        <!-- End of CSS -->
    </head>

    <body>
        <div>

            <!-- Main Menu -->
            <div id='main-menu'>
                <form>
                    <select name="select-1">
                        <option selected value=""> -- Select an Option -- </option>
                        <option value="shop">Shop</option>
                        <option value="post_office">Post Office</option>
                        <option value="amt">AMT</option>
                        <option value="bus_stops">Bus Stop</option>
                    </select>
                    <input type="text" name="input-1"></input>
                    <div>
                        Distance:
                        <input type="range" name="range-1" value="200" min="50" max="1000" step="10" oninput="rangeOutputUpdate(this)">
                        <output>200</output>m
                    </div>

                    <select name="select-2">
                        <option selected value> -- Select an Option -- </option>
                        <option value="shop">Shop</option>
                        <option value="post_office">Post Office</option>
                        <option value="amt">AMT</option>
                        <option value="bus_stops">Bus Stop</option>
                    </select>
                    <input type="text" name="input-2"></input>
                    <div>
                        Distance:
                        <input type="range" name="range-2" value="200" min="50" max="1000" step="10" oninput="rangeOutputUpdate(this)">
                        <output>200</output>m
                    </div>

                    <select name="select-3">
                        <option selected value> -- Select an Option -- </option>
                        <option value="shop">Shop</option>
                        <option value="post_office">Post Office</option>
                        <option value="amt">AMT</option>
                        <option value="bus_stops">Bus Stop</option>
                    </select>
                    <input type="text" name="input-3"></input>
                    <div>
                        Distance:
                        <input type="range" name="range-3" value="200" min="50" max="1000" step="10" oninput="rangeOutputUpdate(this)">
                        <output>200</output>m
                    </div>

                    <select name="select-4">
                        <option selected value> -- Select an Option -- </option>
                        <option value="shop">Shop</option>
                        <option value="post_office">Post Office</option>
                        <option value="amt">AMT</option>
                        <option value="bus_stops">Bus Stop</option>
                    </select>
                    <input type="text" name="input-4"></input>
                    <div>
                        Distance:
                        <input type="range" name="range-4" value="200" min="50" max="1000" step="10" oninput="rangeOutputUpdate(this)">
                        <output>200</output>m
                    </div>

                    <input type="button" name="button" onclick="processData()"/>
                    <input type="reset" name="reset" onclick="clearMap()"/>
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
