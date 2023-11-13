<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>Climatologies</title>
    <script src="rger/js/jquery.min.js"></script>
    <script src="rger/js/jszip.min.js"></script>
    <link rel="stylesheet" href="rger/sta/style.css" media="screen">
    <link rel="stylesheet" href="rger/sta/estilo.css" media="screen">
    <link rel="stylesheet" href="rger/leaflet/leaflet.css"/>
    <script src="rger/leaflet/leaflet.js"></script>
    <link rel="stylesheet" href="rger/leaflet/leaflet-geoman.css"/>
    <script src="rger/leaflet/leaflet-geoman.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
</head>
<body>
<style>
    #lmap {

        margin: 0;
        padding: 0;
        border: 0;
        height: calc(100% - 10px); /* DO NOT USE !important for setting the Grid height! */
        width: 100%;
        min-width: 300px;
        display: inline-block;
    }
    .ui-dialog { z-index: 1000 !important ;}
    .k-listview-content {
        overflow: hidden;
    }
</style>
<div style="width:calc(100%); height: calc(100%);">
    <div id="lmap"></div>
</div>
<div id="agnfieldDialog" style="display: none; width: 100%; height: 100%;">
    <div id="divrpt">
        <button type="button" id="btnVSR" class="k-button k-button-solid-base k-button-solid k-button-md k-rounded-md">
            Seasonal Forecast
        </button>
        <button type="button" id="btnWSR" class="k-button k-button-solid-base k-button-solid k-button-md k-rounded-md">
            Forecast Skill
        </button>
        <hr>
    </div>

    <iframe id="frmrpt" title="x" style="border:1px solid #c5c5c5; width:calc(100% - 2px);height:calc(100% - 50px);"
            src="wait.html"></iframe>
</div>

<script>
    var curss = "";
    var curagf = null;
    var boarditems = null;
    var context = null;
    var dialog = null;
    var dataSource = null;
    var curmkr = null;
    $(document).ready(function () {

        dialog = $('#agnfieldDialog');
        $("#agnfieldDialog").dialog({
            autoOpen: false, modal: true, show: "blind", hide: "blind", width: 1000, height: 800
        });

        function onClose(e) {
            $("#frmrpt").attr('src', "wait.html");
            refetch();
        }

        $("#btnA").click(function () {
            lmap.pm.enableDraw('Marker');
        });

        var lmapOptions = {
            center: [0,0],
            zoom: 2,
            minZoom: 2,
            maxZoom: 14
        }

        var lmap = new L.map('lmap', lmapOptions); // Creating a map object
        lmap.attributionControl.setPrefix('API: Leaflet');
        lmap.attributionControl.addAttribution('Tiles: &copy; OpenStreetMap');
        // Creating a Layer object
        llayer = new L.TileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {opacity: 0.8, maxZoom: 14});
        lmap.addLayer(llayer);  // Adding layer to the map
        var ALayer = null;
        var BLayer = null;

        function onEachFeature(feature, layer) {
                layer.on('click', function (evt) {
                    onMapClick(evt);
                });
        }

        function onMapClick(e) {
            agnfieldAction('r', e.sourceTarget.feature.properties.dws);
        }

<?php if ($g_UNM==""): ?>
        lmap.pm.addControls({
            position: 'topleft',
            drawControls: false,
            editControls: false,
            optionsControls: false,
            customControls: true,
            oneBlock: false,
        });


        const actions = [
            // uses the default 'cancel' action
            "cancel",
        ];
        lmap.pm.Toolbar.copyDrawControl("Marker", {
            name: "AddSeasonalClimateForecast",
            block: "custom",
            title: "New Seasonal Climate Forecast",
            actions: actions,
        });
        lmap.on('pm:actionclick', (e) => {
            console.log(e);
        });
        lmap.on('pm:buttonclick', (e) => {
            console.log(e);
        });

        lmap.on('pm:create', function (e) {
            var layer = e.layer;
            var feature = layer.toGeoJSON();
            console.log(feature.geometry.coordinates);
            curmkr = feature.geometry.coordinates;
            //lpolygonClose(feature.geometry.coordinates);
            dialog.dialog("option", "maxHeight", 200);
            dialog.attr('title', 'Add Seasonal Climate Forecast').dialog();
            dialog.dialog("open");

            $("#divrpt").hide();
            $("#frmrpt").attr('src', "AGNSCFDLKC.php");

            lmap.pm.Draw.disable();
            lmap.removeLayer(e.layer);
        });
<?php  endif; ?>

        function refetch() {
            if (ALayer !== null) {
                lmap.removeLayer(ALayer);
                console.log("A removed");
            };
            $.getJSON("../app/AGNSCFDLK.php?map=mapg", function (data) {
                ALayer = L.geoJson(data).addTo(lmap);
            })  ;
            if (BLayer !== null) {
                lmap.removeLayer(BLayer);
            };
            $.getJSON("../app/AGNSCFDLK.php?map=map", function (data) {
                BLayer = L.geoJson(data, {onEachFeature: onEachFeature}).addTo(lmap);
            })
        }

        window.agnrefetch = refetch;
        refetch();
    });

    function agnfieldAction(a, e) {
        console.log(a, e);
        curagf={dws:e};
        console.log(curagf);

        if (a === 'r') {
            dialog.dialog("open");
            $("#divrpt").show();
            $("#frmrpt").attr('src', "AGNSCFRGFC.php");
            $("#btnVSR").css('font-weight', 'bold');
            $("#btnWSR").css('font-weight', 'normal');
        }
        if (a === 'e') {
            var postSTR = "<HQUERY>\n<HKEY/>\n<HUSR/>\n<HDBN/>";
            postSTR += "\n<HACT>S20ACNDLK-DELACNDLK</HACT>";
            postSTR += "\n<HPRM>\n";
            postSTR += "<XXX>" + curagf.id + "</XXX>\n";
            postSTR += "\n</HPRM>";
            postSTR += "\n</HQUERY>\n";
            $.ajax({
                url: "../app/AGNSCFDLK.php",
                data: postSTR,
                type: 'POST',
                contentType: "application/xml",
                dataType: "text/xml",
                complete: agnrefetch
            })
        }
    }

    $("#btnVSR").click(function () {
        $("#frmrpt").attr('src', "AGNSCFRGFC.php");
        $("#btnVSR").css('font-weight', 'bold');
        $("#btnWSR").css('font-weight', 'normal');
    });
    $("#btnWSR").click(function () {
        $("#frmrpt").attr('src', "AGNSCFRELC.php");
        $("#btnVSR").css('font-weight', 'normal');
        $("#btnWSR").css('font-weight', 'bold');
    });
</script>
</body>
</html>