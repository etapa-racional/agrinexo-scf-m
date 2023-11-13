<?php
include("AGNGERGSA.php");
$lblStatus = "Database: $g_DBN -  User: $g_UNM";
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>AGRINEXO SCF - Seasonal Climate Forecast and Modeler</title>
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
        height: calc(100% - 50px);
        margin: 0;
        padding: 0;
        border-width: 0;
        overflow: hidden;

    }

    #tabstrip-parent {
        height: 90%;
        margin: 0;
        padding: 0;
        border: 0px solid green;
        overflow: hidden;
    }

    .dropbtn {
        background-color: #3498DB;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .dropbtn:hover, .dropbtn:focus {
        background-color: #2980B9;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        overflow: auto;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown a:hover {
        background-color: #ddd;
    }

    .show {
        display: block;
    }

</style>
<body style="width:100%;height:100%">
<div id="AppsDialog" style="display: none; width: 100%; height: 100%;">
    <table width="100%" id="mnuAppsTable">
        <tr>
            <td height="20" bgcolor="#AAAAAA">
                <br>&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td height="20" bgcolor="#AAAAAA" align="right">
                <form METHOD="get" style="margin: 0px">
                    <?php print($lblStatus); ?>
                    <INPUT TYPE="submit" NAME="g_END" VALUE="Logout">
                </form>
            </td>
        </tr>
    </table>
</div>
<table width="100%">
    <tr>
        <td style="width: 20px;">
            <button id="mnuAppsButton">&gt;&gt;</button>
        </td>
        <td style="width: 20px;">
            <a href="AGNGERGSA.php?g_END=1">&gt;&gt;</a>
        </td>
        <td>
            AGRINEXO SCF - Seasonal Climate Forecast Modeler [<?php print($lblStatus); ?>]
        </td>

    </tr>
</table>
<div id="tabstrip">
    <ul>
        <li><a href='#a'>Seasonal Climate Forecasts</a></li>
        <li><a href='#b'>Model Evaluation</a></li>
        <li><a href='#c'>Model Management</a></li>
    </ul>

</div>

<script>
    $(document).ready(function () {
        var tabStrip = null;
        tabStrip = $("#tabstrip").tabs();

        var expandContentDivs = function (divs) {
            var visibleDiv = divs.filter(":visible");
            divs.height(tabStripElement.innerHeight()
                - tabStripElement.children(".k-tabstrip-items").outerHeight()
                - parseFloat(visibleDiv.css("padding-top"))
                - parseFloat(visibleDiv.css("padding-bottom"))
                - parseFloat(visibleDiv.css("border-top-width"))
                - parseFloat(visibleDiv.css("border-bottom-width"))
                - parseFloat(visibleDiv.css("margin-bottom")));
            // all of the above padding/margin/border calculations can be replaced by a single hard-coded number for improved performance
        }

        function mainTab() {
            tabStrip.append(
                '<div id="a" style="height: 100%;"><iframe id="AGNSCFRGRCFRAME" src="AGNSCFRGRC.php" style="align: center; border:1px solid #c5c5c5; width:99%;height:99%;"></iframe></div>'
            );
        };
        function skillTab() {
            tabStrip.append(
                '<div id="b" style="height: 100%;"><iframe id="AGNSCFRGLCFRAME" src="AGNSCFRGLC.php" style="align: center; border:1px solid #c5c5c5; width:99%;height:99%;"></iframe></div>'
            );
            //tabStrip.select(tabStrip.items().length-1);
            //resizeAll();
        };
        mainTab();
        skillTab();
    });
</script>
</body>
</html>