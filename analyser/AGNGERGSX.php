<?php
//error_reporting(E_PARSE);

// extract($_REQUEST, EXTR_SKIP);
function customException($exception)
{
    $emsg = $exception->getMessage();
    header("HTTP/1.0 500");
    print  $emsg;
    die();
}

//set_exception_handler('customException');

function customError($errno, $errstr)
{
    if ($errno < 8) {
        $emsg = "[$errno] $errstr";
        header("HTTP/1.0 500");
        print  $emsg;
        die();
    }
}

function pfa($value)
{
    return addslashes($value);
}

//set_error_handler("customError");

date_default_timezone_set("Europe/Lisbon");

//throw new Exception('Uncaught Exception occurred');

$g_SEXP = 3600*24;
$d_SVK = "TYSYHBR";

$servername = getenv(strtoupper(getenv("DATABASE_SERVICE_NAME"))."_SERVICE_HOST");
$g_DBN =  getenv("DATABASE_NAME");
$username = getenv("DATABASE_USER");
$password = getenv("DATABASE_PASSWORD");
$db = pg_pconnect("host=". $servername ." dbname=". $g_DBN ." user=".$username ." password=".$password);
?>
