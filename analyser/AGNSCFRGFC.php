<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <script src="rger/js/jquery.min.js"></script>
    <script src="rger/js/jszip.min.js"></script>
    <link rel="stylesheet" href="rger/sta/style.css" media="screen">
    <link rel="stylesheet" href="rger/sta/estilo.css" media="screen">
    <link rel="stylesheet" href="rger/leaflet/leaflet.css"/>
    <script src="rger/leaflet/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
</head>
<body>
<style>
    #chart-fc {

        margin: 0;
        padding: 0;
        border: 0;
        height: calc(35% - 45px); /* DO NOT USE !important for setting the Grid height! */
        min-height: 200px;
    }

    #chart-rf {

        margin: 0;
        padding: 0;
        border: 0;
        height: calc(35% - 45px); /* DO NOT USE !important for setting the Grid height! */
        min-height: 200px;
    }

    #chart-an {

        margin: 0;
        padding: 0;
        border: 0;
        height: calc(30% - 45px); /* DO NOT USE !important for setting the Grid height! */
        min-height: 200px;
    }

</style>
<div style="max-width: 100%; width: 100%; height: 100%; margin: 0 auto;">
    <table align="center" width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
            <td>
                <button id="mnuParams">&gt;&gt;</button>
            </td>
            <td>Grid Location Id</td>
            <td><input id="param_DET" readonly style="width: 100%; max-width: 170px;"/></td>
            </td></tr>
        <tr>
        <tr>
            <td></td>
            <td>Forecast Source (A)AGRINEXO / (E)CMWF / (N)CEP / Ensemble (B) A+N / Ensemble (C) E+B</td>
            <td><input id="param_FSR" readonly style="width: 100%; max-width: 110px;"/>
            </td>
            </td>
            <td></td>
            <td></td>
            </td>
        </tr>
    </table>
    <div id="chart-fc"></div>
    <div id="chart-rf"></div>
    <div id="chart-an"></div>
</div>

<div id="ParamsDialog" style="display: none; width: 100%; height: 100%;">
    <form id="ParamsForm" method="post" style="width: 100%; height: 100%;">
        <table cellspacing="4" cellpadding="4" style="width: 100%;">
            <tr>
                <td>
                    <div style="display: inline-block;"><label for="edit_param_FSR">
                            Forecast Source (A)AGRINEXO / (E)CMWF / (N)CEP/ Ensemble (B) A+N / Ensemble (C) E+B</label>
                    </div>
                    <input type="text" name="edit_param_FSR" id="edit_param_FSR"
                           style="width: 100%; max-width: 110px;"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <button id="paramsOk" type="button">OK</button>
                    <button id="paramsCancel" type="button">Cancel</button>
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="okDialog" title="Info" draggable="false" style="display: none;">
    <p id="okDialogMessage" style="margin-top: 5px">Mensagem</p>
</div>
<script>
    $(document).ready(function () {

        $("#edit_record_xxx").prop("disabled", true);

        $("#ParamsDialog").dialog({
            autoOpen : false, modal : true, show : "blind", hide : "blind"
        });

        function onParams(e) {
            $("#ParamsDialog").dialog("open");
            $("#edit_param_FSR").val($("#param_FSR").val());
        }

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

        $('#okDialog').dialog({
            autoOpen : false, modal : true, show : "blind", hide : "blind"
        });

        function alertmsg(msg) {
            $("#okDialogMessage").text(msg);
            $('#okDialog'),dialog("open");
        }

        function onCompleteR(jqXHR, textStatus) {
            if (jqXHR.status === 200) {
                if (textStatus === "parsererror") {
                    msg = "Sem Sessão Iniciada.";
                    alertmsg(msg);
                }
            } else {
                var msg = 'Não foi possível realizar a operação solicitada.';
                msg = msg + ' [' + jqXHR.status + "-" + textStatus + ": " + jqXHR.responseText + ']';
                alertmsg(msg);
            }
        }


        var curMMM = 0;

        function paramsOk(e) {
            $("#param_FSR").val($("#edit_param_FSR").val());
            $("#toolbar").show();
            createChart();
            $("#ParamsDialog").dialog("close");
        }

        function paramsCancel(e) {
            $("#ParamsDialog").dialog("close");
        }

        function createChart() {

            var options = {
                series: [],
                chart: {
                    height: 250,
                    type: 'line',
                },
                dataLabels: {
                    enabled: false
                },
                title: {
                    text: 'Forecast',
                },
                noData: {
                    text: 'Loading...'
                },
                xaxis: {
                    type: 'category',
                    tickPlacement: 'on',
                    labels: {
                        rotate: -45,
                        rotateAlways: true
                    }
                },
                yaxis: [
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        title: {
                            text: 'TME,TMX and TMI (ºC)'
                        }
                    },
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        show: false
                    },
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        show: false
                    },
                    {
                        opposite: true,
                        seriesName: 'PRC - Average Precipitation',
                        title: {
                            text: 'PRC (mm/month)'
                        }
                    }
                ]
            };

            var chart = new ApexCharts(document.querySelector("#chart-fc"), options);
            chart.render();


            $.getJSON('AGNSCFRGF.php?grf=a&det=' + escapeHtml($("#param_DET").val()) + "&fsr=" + escapeHtml($("#param_FSR").val()), function (response) {
                chart.updateSeries([
                    {
                        type: 'line',
                        name: 'TME - Average Mean Temperature',
                        data: response.TP0
                    }, {
                        type: 'line',
                        name: 'TMX - Average Maximum Temperature',
                        data: response.TMX
                    }, {
                        type: 'line',
                        name: 'TMI - Average Minimum Temperature',
                        data: response.TMN
                    }, {
                        type: 'column',
                        name: 'PRC - Average Precipitation',
                        data: response.PRC
                    }
                ]);
            });

            var optionsrf = {
                series: [],
                chart: {
                    height: 250,
                    type: 'line',
                },
                dataLabels: {
                    enabled: false
                },
                title: {
                    text: 'Referential [1993-2016]',
                },
                noData: {
                    text: 'Loading...'
                },
                xaxis: {
                    type: 'category',
                    tickPlacement: 'on',
                    labels: {
                        rotate: -45,
                        rotateAlways: true
                    }
                },
                yaxis: [
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        title: {
                            text: 'TME,TMX and TMI (ºC)'
                        }
                    },
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        show: false
                    },
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        show: false
                    },
                    {
                        opposite: true,
                        seriesName: 'PRC - Average Precipitation',
                        title: {
                            text: 'PRC (mm/month)'
                        }
                    }
                ]
            };

            var chartrf = new ApexCharts(document.querySelector("#chart-rf"), optionsrf);
            chartrf.render();


            $.getJSON('AGNSCFRGF.php?grf=rf&det=' + escapeHtml($("#param_DET").val()) + "&fsr=" + escapeHtml($("#param_FSR").val()), function (response) {
                chartrf.updateSeries([
                    {
                        type: 'line',
                        name: 'TME - Average Mean Temperature',
                        data: response.TP0
                    }, {
                        type: 'line',
                        name: 'TMX - Average Maximum Temperature',
                        data: response.TMX
                    }, {
                        type: 'line',
                        name: 'TMI - Average Minimum Temperature',
                        data: response.TMN
                    }, {
                        type: 'column',
                        name: 'PRC - Average Precipitation',
                        data: response.PRC
                    }
                ]);
            });

            var optionsan = {
                series: [],
                chart: {
                    height: 250,
                    type: 'line',
                },
                dataLabels: {
                    enabled: false
                },
                title: {
                    text: 'Forecasted Anomalies'
                },
                noData: {
                    text: 'Loading...'
                },
                xaxis: {
                    type: 'category',
                    tickPlacement: 'on',
                    labels: {
                        rotate: -45,
                        rotateAlways: true
                    }
                },
                yaxis: [
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        title: {
                            text: 'TME,TMX and TMI (ºC)'
                        }
                    },
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        show: false
                    },
                    {
                        seriesName: 'TME - Average Mean Temperature',
                        show: false
                    },
                    {
                        opposite: true,
                        seriesName: 'PRC - Average Precipitation',
                        title: {
                            text: 'PRC (mm/month)'
                        }
                    }
                ]
            };

            var chartan = new ApexCharts(document.querySelector("#chart-an"), optionsan);
            chartan.render();


            $.getJSON('AGNSCFRGF.php?grf=an&det=' + escapeHtml($("#param_DET").val()) + "&fsr=" + escapeHtml($("#param_FSR").val()), function (response) {
                chartan.updateSeries([
                    {
                        type: 'line',
                        name: 'TME - Average Mean Temperature',
                        data: response.TP0
                    }, {
                        type: 'line',
                        name: 'TMX - Average Maximum Temperature',
                        data: response.TMX
                    }, {
                        type: 'line',
                        name: 'TMI - Average Minimum Temperature',
                        data: response.TMN
                    }, {
                        type: 'column',
                        name: 'PRC - Average Precipitation',
                        data: response.PRC
                    }
                ]);
            });
        }

        $( "#mnuParams" ).on( "click", function() {
            onParams();
        } );
        $( "#paramsOk" ).on( "click", function() {
            paramsOk();
        } );
        $( "#paramsCancel" ).on( "click", function() {
            paramsCancel();
        } );

        function directCallOK() {
            var today = new Date();
            var todaydate = today.getFullYear();
            var pastday = new Date(today)
            pastday.setDate(pastday.getDate() - 10958);
            var pastdaydate = pastday.getFullYear();
            $("#param_DFD").val(parent.curagf.fid);
            $("#param_DET").val(parent.curagf.dws);
            $("#param_FSR").val("N");
            curMMM = parent.curagf.xxx;
            lat = parent.curagf.miny;
            lon = parent.curagf.minx;
            createChart();
        }

        directCallOK();
    });
</script>
</body>
</html>