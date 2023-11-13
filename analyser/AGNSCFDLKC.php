<!doctype html>
<html lang="pt">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <title>Parcelas</title>
        <script src="rger/js/jquery.min.js"></script>
        <script src="rger/js/jszip.min.js"></script>
        <link rel="stylesheet" href="rger/sta/style.css" media="screen">
        <link rel="stylesheet" href="rger/sta/estilo.css" media="screen">
        <link rel="stylesheet" href="rger/leaflet/leaflet.css" />
        <script src="rger/leaflet/leaflet.js"></script>
        <link rel="stylesheet" href="rger/leaflet/leaflet-geoman.css" />
        <script src="rger/leaflet/leaflet-geoman.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    </head>
    <body style="min-height: 50px;">
        <div style="max-width: 100%; width: 100%; margin: 0 auto; background-color: #FFFFFF">
            <div id="editRecordDialog" style="max-width: 100%;">
                <div id="editForm" style="margin: auto; width: 800px; max-width: 100%; max-height: 100%; ">
                    <input type="hidden" name="edit_record_dest_xxx" id="edit_record_dest_xxx" value="0">
                    <input type="hidden" name="edit_record_orig_xxx" id="edit_record_orig_xxx">

                    <div style="vertical-align: top; padding: 2px;">
                        <div style="vertical-align: top; display: inline-block; width: 150px; text-align: left">
                            <label for="edit_record_DSC">Name</label></div>
                        <div style="display: inline-block; width: 100%; max-width: 400px;">
                            <input type="text" maxlength="50" class="k-textbox" name="edit_record_DSC" id="edit_record_DSC" style="width: 100%; max-width: 350px;"/>
                        </div>
                    </div>

                </div>
            </div>
            <div align="Center">
                <hr>
                <button id="editOk" jsId="editOk" type="button">Save</button>
                <button id="editCancel" jsId="editCancel" type="button">Cancel</button>
            </div>
        </div>
        <div id="okDialog" title="Title" draggable="false" style="display: none;">
            <p id="okDialogMessage" style="margin-top: 5px">Mensagem</p>
        </div>
        <script>
            $(document).ready(function () {

                $("#edit_record_dest_xxx").prop("disabled", true);

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
                    //$('#okDialog').title("Alert");
                    $("#okDialogMessage").text(msg);
                    $('#okDialog').dialog("close");;
                }

                function onRefresh() {
                    parent.dialog.dialog("close");
                }

                function onCompleteCUD(jqXHR, textStatus) {
                    if (jqXHR.status === 200) {
                        onRefresh();
                    } else {
                        var msg = 'Requested operation not completed.';
                        msg = msg + ' [' + jqXHR.status + "-" + textStatus + ": " + jqXHR.responseText + ']';
                        alertmsg(msg);
                    }
                }

                var ptxdataSource=[];

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
                        url: "../app/AGNSCFDLK.php",
                        data: postSTR,
                        type: 'POST',
                        contentType: "application/xml",
                        dataType: "text/xml",
                        complete: onCompleteCUD
                    })
                }

                function editCancel(e) {
                    parent.dialog.dialog("close");
                }
                $("#editOk").on( "click", function() {
                    editOk();
                } );

                $("#editCancel").on( "click", function() {
                    editCancel();
                } );

                function lpolygonClose() {
                    e=parent.curmkr;
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

                }
            });
        </script>
    </body>
</html>