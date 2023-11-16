<?php
include "AGNGERGSX.php";
?>
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
</style>
<input type="hidden" name="edit_record_dest_xxx" id="edit_record_dest_xxx" value="0">
<input type="hidden" name="edit_record_orig_xxx" id="edit_record_orig_xxx">
<div style="width:calc(100%); height: calc(100%);">
    <div id="lmap"></div>
</div>
<div id="agnfieldDialog" style="display: none; width: 100%; height: 100%;">
    <div id="divrpt">
        <button type="button" id="btnVSR">
            Seasonal Forecast
        </button>
        <button type="button" id="btnWSR">
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

<?php echo "//$p_AUT\n"; if ($p_AUT!=""): ?>
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
            editOk();
            lmap.pm.Draw.disable();
            lmap.removeLayer(e.layer);
        });


        function onCompleteCUD(jqXHR, textStatus) {
            if (jqXHR.status === 200) {
                //onRefresh();
                refetch();
            } else {
                var msg = 'Requested operation not completed.';
                msg = msg + ' [' + jqXHR.status + "-" + textStatus + ": " + jqXHR.responseText + ']';
                alertmsg(msg);
            }
        }

        var ptxdataSource=[];

        function escapeHtml(unsafe) {
            if (unsafe === undefined)
                return unsafe;
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function editOk(e) {
            lpolygonClose()
            var postSTR = "<HQUERY>\n<HKEY/>\n<HUSR/>\n<HDBN/>";
            postSTR += "\n<HACT>S20ACNDLK-INSACNDLK</HACT>";
            postSTR += "\n<HPRM>\n<ACNDLK>\n";;
            postSTR += "<DSC>" + escapeHtml($("#edit_record_DSC").val()) + "</DSC>\n";
            postSTR += "</ACNDLK>";
            postSTR += "\n<detail>";
            postSTR += "\n<SENMOPS>";
            for (n = 0; n < ptxdataSource.length; n++) {
                postSTR += "\n<SENMOP>\n";
                postSTR += "<MMM>" + $("#edit_record_dest_xxx").val() + "</MMM>\n";
                if (ptxdataSource.length > 0) {
                    if (ptxdataSource[n].xxx !== undefined) {
                        postSTR += "<XXX>" + escapeHtml(ptxdataSource[n].xxx) + "</XXX>\n";
                    }
                }
                if (ptxdataSource.length > 0) {
                    if (ptxdataSource[n].IDP !== undefined) {
                        postSTR += "<IDP>" + escapeHtml(ptxdataSource[n].IDP) + "</IDP>\n";
                    }
                }
                if (ptxdataSource.length > 0) {
                    if (ptxdataSource[n].PTX !== undefined) {
                        postSTR += "<PTX>" + escapeHtml(ptxdataSource[n].PTX) + "</PTX>\n";
                    }
                }
                if (ptxdataSource.length > 0) {
                    if (ptxdataSource[n].PTY !== undefined) {
                        postSTR += "<PTY>" + escapeHtml(ptxdataSource[n].PTY) + "</PTY>\n";
                    }
                }
                postSTR += "</SENMOP>\n";
            }
            postSTR += "</SENMOPS>";
            postSTR += "\n</detail>";
            postSTR += "\n</HPRM>";
            postSTR += "\n</HQUERY>\n";
            $.ajax({
                url: "AGNSCFDLK.php",
                data: postSTR,
                type: 'POST',
                contentType: "application/xml",
                dataType: "text/xml",
                complete: onCompleteCUD
            })
        }

        function lpolygonClose() {
            e=curmkr;
            console.log(e)
            ptxdataSource.push({
                IDP: "0",
                PTX: e[0].toString(),
                PTY: e[1].toString()
            });
            ptxdataSource.push({
                IDP: "0",
                PTX: (e[0] + 0.0001).toString(),
                PTY: (e[1] + 0.0001).toString()
            });
            ptxdataSource.push({
                IDP: "0",
                PTX: e[0].toString(),
                PTY: (e[1] + 0.0001).toString()
            });
            ptxdataSource.push({
                IDP: "0",
                PTX: e[0].toString(),
                PTY: e[1].toString()
            });
            $("#edit_record_orig_xxx").val("");
            $("#edit_record_dest_xxx").val("");

        };

<?php  endif; ?>

        function refetch() {
            if (ALayer !== null) {
                lmap.removeLayer(ALayer);
                console.log("A removed");
            };
            $.getJSON("AGNSCFDLK.php?map=mapg", function (data) {
                ALayer = L.geoJson(data).addTo(lmap);
            })  ;
            if (BLayer !== null) {
                lmap.removeLayer(BLayer);
            };
            $.getJSON("AGNSCFDLK.php?map=map", function (data) {
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
                url: "AGNSCFDLK.php",
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