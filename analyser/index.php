<?php
include("AGNGERGSX.php");
$lblStatus = "Database: $g_DBN -  User: $g_UNM";
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>AGRINEXO SCF - Seasonal Climate Forecast Modeler</title>
    <script src="rger/js/jquery.min.js"></script>
    <script src="rger/js/jszip.min.js"></script>
    <link rel="stylesheet" href="rger/sta/style.css" media="screen">
    <link rel="stylesheet" href="rger/sta/estilo.css" media="screen">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
</head>
<style>

    html,
    body,
    #tabstrip {
        height: calc(100% - 5px);
        margin: 0;
        padding: 0;
        border-width: 0;
        overflow: hidden;

    }
</style>
<body style="width:100%;height:100%">
<table width="100%">
    <tr>
        <td>
            AGRINEXO SCF - Seasonal Climate Forecast Modeler
        </td>
        <td style="width: 20px; height: 20px;">
            <a href="AGNSCFGSI.php">&gt;&gt;</a>
        </td>
    </tr>
</table>
<div id="tabstrip">
    <ul>
        <li><a href='#a'>Seasonal Climate Forecasts</a></li>
        <li><a href='#b'>Model Evaluation</a></li>
    </ul>
</div>

<script>
    $(document).ready(function () {
        var tabStrip = null;
        tabStrip = $("#tabstrip").tabs();

        function mainTab() {
            tabStrip.append(
                '<div id="a" style="height: calc(100% - 20px);"><iframe id="AGNSCFRGRCFRAME" src="AGNSCFRGRC.php" style="align: center; border:1px solid #c5c5c5; width:100%;height: calc(100% - 20px);"></iframe></div>'
            );
        };
        function skillTab() {
            tabStrip.append(
                '<div id="b" style="height: calc(100% - 20px);"><iframe id="AGNSCFRGLCFRAME" src="AGNSCFRGLC.php" style="align: center; border:1px solid #c5c5c5; width:100%;height: calc(100% - 20px);"></iframe></div>'
            );
        };
        mainTab();
        skillTab();
    });
</script>
</body>
</html>