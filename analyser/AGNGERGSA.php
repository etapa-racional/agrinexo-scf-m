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





$preg_XXX = pg_escape_string($db,$_POST['preg_XXX']);
$g_PSS = pg_escape_string($db,$_POST['g_PSS']);
$g_PVF = $_POST['g_PVF'];
$g_END = $_GET['g_END'];

$initSQL='
CREATE TABLE IF NOT EXISTS gerdut
(
    xxx serial NOT NULL,
    unm varchar(50) NOT NULL,
    dsc varchar(50) NOT NULL,
    psw varchar(20) NOT NULL,
    pin integer NOT NULL,
    CONSTRAINT gerdut_pkey PRIMARY KEY (xxx),
    CONSTRAINT gerdut_unm UNIQUE (unm)
);
INSERT INTO gerdut (xxx,unm,dsc,psw,pin) VALUES (1,'. $preg_XXX .','. $preg_XXX .','.$_POST['g_PSS'].',0);';
$result = pg_query($db, $initSQL);

if ($g_END != "") {
    $p_AUT = " ";
    $p_SSI = " ";
    $p_SSA = " ";
    setcookie("AGNUSR", "$p_AUT", time() - $g_SEXP);
    setcookie("AGNSTM", "$p_SSI", time() - $g_SEXP);
    setcookie("AGNSSA", "$p_SSA", time() - $g_SEXP);
    header("Location: AGNGERGLG.php");
    exit;
} else {
    if ($preg_XXX != "" and $g_PSS != "") {
        $p_SPI = $_COOKIE["AGNSPI"];
        $p_SPA = $_COOKIE["AGNSPA"];
        $m_TEXT = md5($p_SPI . $d_SVK);
        $prePASS = str_replace("0", "1", substr(md5($m_TEXT . "$d_SVK"), 5, 5));
        if (($g_PVF == $prePASS) and (($p_SPI - 1200) < time())) {
            $Query = "SELECT xxx, psw FROM gerdut WHERE unm='$preg_XXX'";
            $result = pg_query($db, $Query);
            $numRows = pg_num_rows($result);
            if ($numRows > 0) {
                $d_PSS = pg_result($result, 0, "psw");
                if ($g_PSS == $d_PSS) {
                    $p_AUT = pg_result($result, 0, "xxx");
                    $p_SSI = time();
                    $p_SSA = md5($p_SSI . $d_SVK . $g_DBN);
                    setcookie("AGNUSR", "$p_AUT", time()+$g_SEXP);
                    setcookie("AGNSTM", "$p_SSI", time()+$g_SEXP);
                    setcookie("AGNSSA", "$p_SSA", time()+$g_SEXP);
                    header("Location: AGNSCFGSI.php");
                }
            }
        }
    } else {
        if (isset($_COOKIE["AGNUSR"]) & isset($_COOKIE["AGNSTM"]) & isset($_COOKIE["AGNSSA"])) {
            $p_AUT = 1 * $_COOKIE["AGNUSR"];
            $p_SSI = $_COOKIE["AGNSTM"];
            $p_SSA = $_COOKIE["AGNSSA"];
        }
    }
}

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
        setcookie("AGNUSR", "$p_AUT", time()+$g_SEXP);
        setcookie("AGNSTM", "$p_SSI", time()+$g_SEXP);
        setcookie("AGNSSA", "$p_SSA", time()+$g_SEXP);
    }
} else {
    header("Location: AGNGERGLG.php");
    die;
}
?>
