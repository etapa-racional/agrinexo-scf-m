<?php
include "AGNGERGSA.php";
?>
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
</style>
<div style="max-width: 100%; width: 100%; height: 100%; margin: 0 auto; text-align: center; padding-top: 10%;">
    <button onclick="runCommand()">Process pending requests of Seasonal Forecasts</button><br>
    <div style="border: 1px solid grey; width: 300px; height: 30px; margin-top: 20px; display: inline-block;">
        <span id="live-output"></span>
    </div>
</div>
<div id="okDialog" title="Title" draggable="false" style="display: none;">
    <p id="okDialogMessage" style="margin-top: 5px">Mensagem</p>
</div>
<script>
    $(document).ready(function () {

    });
    function runCommand() {
        $.ajax({
            type: 'GET',
            url: '<?php echo "$modelerAPI";?>',
            success: function(data) {
                $("#live-output").html(data);
            }
        });
    }
</script>
</body>
</html>