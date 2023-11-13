<?php
include("AGNGERGSX.php");
include("AGNSCFGSD.php");

if ($_GET['grf'] == "a") {

    $DET = $_GET['det'];
    $FSR = $_GET['fsr'];
    $Query =
        "SELECT EXTRACT(Month FROM csvmgm.tms) as tms, round(avg(csvmgm.tmd)::numeric,1) as tp0, " .
        "round(avg(csvmgm.prc)::numeric,0) as vw0, " .
        "round(avg(csvmgm.tmx)::numeric,1) as tmx, round(avg(csvmgm.tmn)::numeric,1) as tmn " .
        " FROM csvmgm INNER join csvdgm on csvmgm.mmm=csvdgm.xxx " .
        "WHERE " .
        "csvdgm.rfr='" . pfq($DET) . "' ";
    $Query .= " AND csvmgm.tms>='1993-01-01 00:00:00' ";
    $Query .= " AND csvmgm.tms<='2016-12-31 00:00:00' ";
    $Query .= "GROUP BY EXTRACT(Month FROM csvmgm.tms) ORDER BY tms";
    $RowName = "SENMET";
    varLog($Query);
    $ReturnRF = xmlResult($RowName, $Query);
    $xmlRF = simplexml_load_string($ReturnRF);
    $jmax = substr_count($ReturnRF, "<SENMET>");

    $Query =
        "SELECT EXTRACT(Month FROM csvmfc.tms) as tms, round((csvmfc.tmd)::numeric,1) as tp0, " .
        "round((csvmfc.prc)::numeric,0) as vw0, " .
        "round((csvmfc.tmx)::numeric,1) as tmx, round((csvmfc.tmn)::numeric,1) as tmn " .
        " FROM csvmfc INNER join csvdfc on csvmfc.mmm=csvdfc.xxx " .
        "WHERE " .
        "csvdfc.rfr='" . pfq($DET) . "' AND csvmfc.ftm=(SELECT MAX(csvmfc.ftm) FROM csvmfc WHERE csvmfc.mmm=csvdfc.xxx) " .
        "AND csvmfc.fsr='" . pfq($FSR) . "' ";
    $Query .= "ORDER BY csvmfc.tms";
    $RowName = "SENMET";
    varLog($Query);
    $Return = xmlResult($RowName, $Query);
    if (strpos($Return, "<SENMETS/>") != false) {
        throw new Exception('No data available with given parameters. Check parameters or try again later.');
    }
    $xmlANM = simplexml_load_string($Return);
    $imax = substr_count($Return, "<SENMET>");

    $data = array(
        'TP0' => array(),
        'PRC' => array(),
        'TMX' => array(),
        'TMN' => array()
    );

    for ($i = 0; $i < $imax; $i++) {
        for ($j = 0; $j < $jmax; $j++) {
            varLog($xmlANM->SENMET[$i]->TMS->asXML());
            varLog($xmlRF->SENMET[$j]->TMS->asXML());
            if ($xmlANM->SENMET[$i]->TMS->asXML() == $xmlRF->SENMET[$j]->TMS->asXML()) {
                $it=$xmlANM->SENMET[$i]->TMS*1;
                $row = array(
                    'x' => $it,
                    'y' => $xmlANM->SENMET[$i]->TP0 + $xmlRF->SENMET[$j]->TP0
                );
                $row['y']=round($row['y'],1);
                $data['TP0'][] = $row;
                $row = array(
                    'x' => $it,
                    'y' => $xmlANM->SENMET[$i]->VW0 + $xmlRF->SENMET[$j]->VW0
                );
                $row['y']=round($row['y'],1);
                $data['PRC'][] = $row;
                $row = array(
                    'x' => $it,
                    'y' => $xmlANM->SENMET[$i]->TMX + $xmlRF->SENMET[$j]->TMX
                );
                $row['y']=round($row['y'],1);
                $data['TMX'][] = $row;
                $row = array(
                    'x' => $it,
                    'y' => $xmlANM->SENMET[$i]->TMN + $xmlRF->SENMET[$j]->TMN
                );
                $row['y']=round($row['y'],1);
                $data['TMN'][] = $row;
            }
        }
    }
    header('Content-Type: application/json; charset=utf8');
    echo json_encode($data);
    exit;
}


if ($_GET['grf'] == "rf") {

    $DET = $_GET['det'];
    $FSR = $_GET['fsr'];

    $Query =
        "SELECT EXTRACT(Month FROM csvmgm.tms) as tms, round(avg(csvmgm.tmd)::numeric,1) as tp0, " .
        "round(avg(csvmgm.prc)::numeric,0) as vw0, " .
        "round(avg(csvmgm.tmx)::numeric,1) as tmx, round(avg(csvmgm.tmn)::numeric,1) as tmn " .
        " FROM csvmgm INNER join csvdgm on csvmgm.mmm=csvdgm.xxx " .
        "WHERE " .
        "csvdgm.rfr='" . pfq($DET) . "' ";
    $Query .= " AND csvmgm.tms>='1993-01-01 00:00:00' ";
    $Query .= " AND csvmgm.tms<='2016-12-31 00:00:00' ";
    $Query .= "GROUP BY EXTRACT(Month FROM csvmgm.tms) ORDER BY tms";
    $RowName = "SENMET";
    varLog($Query);
    $ReturnRF = xmlResult($RowName, $Query);
    $xmlRF = simplexml_load_string($ReturnRF);
    $jmax = substr_count($ReturnRF, "<SENMET>");

    $Query =
    $Query =
        "SELECT EXTRACT(Month FROM csvmfc.tms) as tms, round((csvmfc.tmd)::numeric,1) as tp0, " .
        "round((csvmfc.prc)::numeric,0) as vw0, " .
        "round((csvmfc.tmx)::numeric,1) as tmx, round((csvmfc.tmn)::numeric,1) as tmn " .
        " FROM csvmfc INNER join csvdfc on csvmfc.mmm=csvdfc.xxx " .
        "WHERE " .
        "csvdfc.rfr='" . pfq($DET) . "' AND csvmfc.ftm=(SELECT MAX(csvmfc.ftm) FROM csvmfc WHERE csvmfc.mmm=csvdfc.xxx) " .
        "AND csvmfc.fsr='" . pfq($FSR) . "' ";
    $Query .= "ORDER BY csvmfc.tms";
    $RowName = "SENMET";
    varLog($Query);
    $Return = xmlResult($RowName, $Query);
    if (strpos($Return, "<SENMETS/>") != false) {
        throw new Exception('No data available with given parameters. Check parameters or try again later.');
    }
    $xmlANM = simplexml_load_string($Return);
    $imax = substr_count($Return, "<SENMET>");

    $data = array(
        'TP0' => array(),
        'PRC' => array(),
        'TMX' => array(),
        'TMN' => array()
    );

    for ($i = 0; $i < $imax; $i++) {
        for ($j = 0; $j < $jmax; $j++) {
            varLog($xmlANM->SENMET[$i]->TMS->asXML());
            varLog($xmlRF->SENMET[$j]->TMS->asXML());
            if ($xmlANM->SENMET[$i]->TMS->asXML() == $xmlRF->SENMET[$j]->TMS->asXML()) {
                $it=$xmlANM->SENMET[$i]->TMS*1;
                $row = array(
                    'x' => $it,
                    'y' => 1 * $xmlRF->SENMET[$j]->TP0,
                );
                $data['TP0'][] = $row;
                $row = array(
                    'x' => $it,
                    'y' => 1 * $xmlRF->SENMET[$j]->VW0
                );
                $data['PRC'][] = $row;
                $row = array(
                    'x' => $it,
                    'y' => 1 * $xmlRF->SENMET[$j]->TMX
                );
                $data['TMX'][] = $row;
                $row = array(
                    'x' => $it,
                    'y' => 1 * $xmlRF->SENMET[$j]->TMN
                );
                $data['TMN'][] = $row;
            }
        }
    }
    header('Content-Type: application/json; charset=utf8');
    echo json_encode($data);
    exit;
}

if ($_GET['grf'] == "an") {

    $DET = $_GET['det'];
    $FSR = $_GET['fsr'];

    $Query =
        "SELECT EXTRACT(Month FROM csvmfc.tms) as tms, round((csvmfc.tmd)::numeric,1) as tp0, " .
        "round((csvmfc.prc)::numeric,0) as vw0, " .
        "round((csvmfc.tmx)::numeric,1) as tmx, round((csvmfc.tmn)::numeric,1) as tmn " .
        " FROM csvmfc INNER join csvdfc on csvmfc.mmm=csvdfc.xxx " .
        "WHERE " .
        "csvdfc.rfr='" . pfq($DET) . "' AND csvmfc.ftm=(SELECT MAX(csvmfc.ftm) FROM csvmfc WHERE csvmfc.mmm=csvdfc.xxx) " .
        "AND csvmfc.fsr='" . pfq($FSR) . "' ";
    $Query .= "ORDER BY csvmfc.tms";
    $RowName = "SENMET";
    varLog($Query);
    $Return = xmlResult($RowName, $Query);
    if (strpos($Return, "<SENMETS/>") != false) {
        throw new Exception('No data available with given parameters. Check parameters or try again later.');
    }

    $xmlANM = simplexml_load_string($Return);
    $imax = substr_count($Return, "<SENMET>");

    $data = array(
        'TP0' => array(),
        'PRC' => array(),
        'TMX' => array(),
        'TMN' => array()
    );

    for ($i = 0; $i < $imax; $i++) {
        $it=$xmlANM->SENMET[$i]->TMS*1;
        $row = array(
            'x' => $it,
            'y' => 1 * $xmlANM->SENMET[$i]->TP0
        );
        $data['TP0'][] = $row;
        $row = array(
            'x' => $it,
            'y' => 1 * $xmlANM->SENMET[$i]->VW0
        );
        $data['PRC'][] = $row;
        $row = array(
            'x' => $it,
            'y' => 1 * $xmlANM->SENMET[$i]->TMX
        );
        $data['TMX'][] = $row;
        $row = array(
            'x' => $it,
            'y' => 1 * $xmlANM->SENMET[$i]->TMN
        );
        $data['TMN'][] = $row;
    }
    header('Content-Type: application/json; charset=utf8');
    echo json_encode($data);
    exit;
}
?>