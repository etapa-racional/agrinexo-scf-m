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

$g_SEXP = 3600 * 24;

require("AGNGERCFG.php");
$db = pg_connect("host=" . $servername . " dbname=" . $g_DBN . " user=" . $username . " password=" . $password);

if (isset($_COOKIE["AGNUSR"]) & isset($_COOKIE["AGNSTM"]) & isset($_COOKIE["AGNSSA"])) {
    $p_AUT = 1 * $_COOKIE["AGNUSR"];
    $p_SSI = $_COOKIE["AGNSTM"];
    $p_SSA = $_COOKIE["AGNSSA"];
    if ($p_AUT != "" & $p_SSI != "" & $p_SSA != "") {
        $Query = "SELECT unm, psw FROM gerdut WHERE xxx='$p_AUT' ";
        $result = pg_query($db, $Query);
        $numRows = pg_num_rows($result);
        if ($numRows > 0) {
            $d_PSS = pg_result($result, 0, "psw");
            $g_UNM = pg_result($result, 0, "unm");
        } else {
            setcookie("AGNUSR", " ", time() - $g_SEXP);
            setcookie("AGNSTM", " ", time() - $g_SEXP);
            setcookie("AGNSSA", " ", time() - $g_SEXP);
            header("Location: AGNGERGLG.php");
            die;
        }
        if ($p_SSA != md5($p_SSI . $d_SVK . $g_DBN)) {
            setcookie("AGNUSR", " ", time() - $g_SEXP);
            setcookie("AGNSTM", " ", time() - $g_SEXP);
            setcookie("AGNSSA", " ", time() - $g_SEXP);
            header("Location: AGNGERGLG.php");
            die;
        }
        if (($p_SSA == md5($p_SSI . $d_SVK . $g_DBN)) and (($p_SSI + 600) < time())) {
            $p_SSI = time();
            $p_SSA = md5($p_SSI . $d_SVK . $g_DBN);
            setcookie("AGNUSR", "$p_AUT", time() + $g_SEXP);
            setcookie("AGNSTM", "$p_SSI", time() + $g_SEXP);
            setcookie("AGNSSA", "$p_SSA", time() + $g_SEXP);
        }
    }
}

?>
