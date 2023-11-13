<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>Forecast Skill</title>
    <script src="rger/js/jquery.min.js"></script>
    <script src="rger/js/jszip.min.js"></script>
    <link rel="stylesheet" href="rger/sta/style.css" media="screen">
    <link rel="stylesheet" href="rger/sta/estilo.css" media="screen">
    <link href="rger/tabulator/dist/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="rger/tabulator/dist/js/tabulator.min.js"></script>
</head>
<body>
<style>
    #ttable {

        margin: 0;
        padding: 0;
        border-width: 0;
        height: calc(100% - 5px); /* DO NOT USE !important for setting the Grid height! */
    }
</style>
<div style="max-width: 100%; width: 100%; height: 100%; margin: 0 auto;">
    <div id="ttable"></div>
</div>
<div id="okDialog" title="Title" draggable="false" style="display: none;">
    <p id="okDialogMessage" style="margin-top: 5px">Mensagem</p>
</div>
<script>
    $(document).ready(function () {
        var table = new Tabulator("#ttable", {

            layout: "fitColumns",
            placeholder: "No Data Set",
            selectable: 1,
            columns: [

                {
                    field: "xxx",
                    title: "Id", width: 50, visible: false
                }
                ,
                {field: "rfr", title: "Variable", width: 130},
                {field: "fsr", title: "Forecast Source", width: 90},
                {field: "tgt", title: "TGT", width: 90, visible: false},
                {field: "ori", title: "ORI", width: 90, visible: false},
                {field: "mfc", title: "Forecast Average", width: 130},
                {field: "vfr", title: "Observed Average", width: 130},
                {field: "efc", title: "Forecast Mean Absolute Error", width: 130},
                {field: "pfc", title: "Anomaly Correlation Coefficient", width: 130},
                {field: "enm", title: "Observed Mean Absolute Deviation", width: 130}, {}
            ],
            ajaxURL: "AGNSCFRGL.php?ACTION=X",
        });
    });
</script>
</body>
</html>