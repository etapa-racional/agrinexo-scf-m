<?php
$modelerAPI="http://agrinexo-scf-m-etapa-racional-dev.apps.sandbox-m2.ll9k.p1.openshiftapps.com/runCommandUPD";
$p_AUT="0"; //Enables unauthenticated users to request new forecasts. Set to "" in production.
$logpath="/var/opt/analyser/";
$servername = getenv(strtoupper(getenv("DATABASE_SERVICE_NAME"))."_SERVICE_HOST");
$g_DBN =  getenv("DATABASE_NAME");
$username = getenv("DATABASE_USER");
$password = getenv("DATABASE_PASSWORD");
?>