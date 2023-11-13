<?php
include("AGNGERGSX.php");
include("AGNSCFGSD.php");

if ($_GET['map'] == "map") {

    $map = "";
    $QDFD = "select acndlk.xxx,acndlk.rfr ,acndlk.dsc, dws, minx, miny  from acndlk;";
    $rsDFD = pg_query($db, $QDFD);

    while ($rowDFD = pg_fetch_assoc($rsDFD)) {
        $curcoordinates = "";
        $curcoordinates .= '[' . ($rowDFD['minx'] - 0.125) . ',' . ($rowDFD['miny'] - 0.125) . ']';
        $curcoordinates .= ',[' . ($rowDFD['minx'] - 0.125) . ',' . ($rowDFD['miny'] + 0.125) . ']';
        $curcoordinates .= ',[' . ($rowDFD['minx'] + 0.125) . ',' . ($rowDFD['miny'] + 0.125) . ']';
        $curcoordinates .= ',[' . ($rowDFD['minx'] + 0.125) . ',' . ($rowDFD['miny'] - 0.125) . ']';
        $curcoordinates .= ',[' . ($rowDFD['minx'] - 0.125) . ',' . ($rowDFD['miny'] - 0.125) . ']';
        if ($map != "") $map .= ',';
        $map .=
        '{"type":"Feature",
        "id":"' . $rowDFD['rfr'] . 'm",
        "properties":
        {"name":"' . $rowDFD['dsc'] . '",
        "dws":"' . $rowDFD['dws'] . '",
        "users":' . $rowDFD['xxx'] . '}
        ,"geometry":
        {"type":"Point",
        "coordinates":[' . $rowDFD['minx'] .','. $rowDFD['miny'].']}
        }';
    }
    $map =
        '{"type":"FeatureCollection",
        "features":[' . $map;
    $map .=
        ']}';
    print($map);
    exit;
}

if ($_GET['map'] == "mapg") {

    $map = "";
    $QDFD = "select distinct csvdgm.xxx,csvdgm.rfr, round(minx/1)*1 AS minx, round(miny/1)*1 AS miny ".
            "From acndlk INNER JOIN csvdgm ON acndlk.dws=csvdgm.rfr;";
    $rsDFD = pg_query($db, $QDFD);

    while ($rowDFD = pg_fetch_assoc($rsDFD)) {
        $curcoordinates = "";
        $curcoordinates .= '[' . ($rowDFD['minx'] - 0.5) . ',' . ($rowDFD['miny'] - 0.5) . ']';
        $curcoordinates .= ',[' . ($rowDFD['minx'] - 0.5) . ',' . ($rowDFD['miny'] + 0.5) . ']';
        $curcoordinates .= ',[' . ($rowDFD['minx'] + 0.5) . ',' . ($rowDFD['miny'] + 0.5) . ']';
        $curcoordinates .= ',[' . ($rowDFD['minx'] + 0.5) . ',' . ($rowDFD['miny'] - 0.5) . ']';
        $curcoordinates .= ',[' . ($rowDFD['minx'] - 0.5) . ',' . ($rowDFD['miny'] - 0.5) . ']';
        if ($map != "") $map .= ',';
        $map .=
            '{"type":"Feature",
        "id":"' . $rowDFD['rfr'] . '",
        "properties":
        {"name":"' . $rowDFD['rfr'] . '",
        "users":' . $rowDFD['xxx'] . '}
        ,"geometry":
        {"type":"Polygon",
        "coordinates":[[' . $curcoordinates . ']]}
        }';
    }
    $map =
        '{"type":"FeatureCollection",
        "features":[' . $map;
    $map .=
        ']}';
    print($map);
    exit;
}

if (isset($_GET['minlat']) and isset($_GET['minlon']) and isset($_GET['maxlat']) and isset($_GET['maxlon']) and $p_AUT == 1) {
    for ($ptsy = $_GET['minlat']; $ptsy <= $_GET['maxlat']; $ptsy = $ptsy + 1) {
        for ($ptsx = $_GET['minlon']; $ptsx <= $_GET['maxlon']; $ptsx = $ptsx + 1) {
            $lkkey = "";
            $rtfc = 0.5;
            if ($ptsy >= 0) {
                $lkkey .= 'LTN' . str_pad((round(($ptsy + $rtfc) / 1) - $rtfc) * 1 * 1000, 5, "0", STR_PAD_LEFT);
                $lat=round(($ptsy + $rtfc) / 1) - $rtfc;
            } else {
                $lkkey .= 'LTS' . str_pad((round(($ptsy + $rtfc) / -1) - $rtfc) * 1 * 1000, 5, "0", STR_PAD_LEFT);
                $lat=-(round(($ptsy + $rtfc) / -1) - $rtfc);
            }
            if ($ptsx >= 0) {
                $lkkey .= 'LNE' . str_pad((round(($ptsx + $rtfc) / 1) - $rtfc) * 1 * 1000, 6, "0", STR_PAD_LEFT);
                $lon=round(($ptsx + $rtfc) / 1) - $rtfc;
            } else {
                $lkkey .= 'LNO' . str_pad((round(($ptsx + $rtfc) / -1) - $rtfc) * 1 * 1000, 6, "0", STR_PAD_LEFT);
                $lon=-(round(($ptsx + $rtfc) / -1) - $rtfc);
            }
            pg_query($db, "INSERT INTO csvdgm(rfr, lat, lon) VALUES ('$lkkey'," . $lat. "," . $lon . ");");
        }
    }
}

function rptS20ACNDLK($PARAM)
{
    $xml = simplexml_load_string($PARAM);
    $Query = "select acndlk.xxx,acndlk.rfr ,acndlk.dsc  from acndlk " .
        " ;";
    $RowName = "ACNDLK";
    $Return = xmlResult($RowName, $Query);
    return $Return;
}

if ($ACTION == "S20ACNDLK-RETACNDLK") {
    print(rptS20ACNDLK($PARAM));
    exit;
}

function rptS20ACNDLKP($PARAM)
{
    $Query = "SELECT xxx,rfr,dsc, DATE(dti) AS dti," .
        "dsg,kci,kcm ,kce,dri,drd,drm,drl,rdi,rdm,awc,iws " .
        "FROM acndlk WHERE xxx=" . $PARAM .
        " ;";
    $RowName = "ACNDLK";
    $Return = xmlResult($RowName, $Query);
    return $Return;
}

if ($ACTION == "S20ACNDLK-RETACNDLKP") {
    print(rptS20ACNDLKP($PARAM));
    exit;
}

if ($ACTION == "S20ACNDLK-INSACNDLK") {
    $xml = simplexml_load_string($PARAM);
    $ptscount = 0;
    $ptsx = 0;
    $ptsy = 0;
    $strpolig = "";
    foreach ($xml->detail->SENMOPS->children() as $child) {
        $ptsx += $child->PTX;
        $ptsy += $child->PTY;
        if ($strpolig != "") $strpolig .= ",";
        $strpolig .= $child->PTX . " " . $child->PTY;
        $ptscount++;
    }
    if ($ptscount < 3) {
        throw new Exception('Location not defined.');
    }
    $ptsx = $ptsx / $ptscount;
    $ptsy = $ptsy / $ptscount;

    $lkkey = "";
    varLog($ptsx);
    varLog(str_pad((intval($ptsx * -10)), 3, "0", STR_PAD_LEFT));
    if ($ptsy >= 0) {
        $lkkey .= 'LTN' . str_pad((round($ptsy / 0.25) * 0.25 * 1000), 5, "0", STR_PAD_LEFT);
    } else {
        $lkkey .= 'LTS' . str_pad((round($ptsy / -0.25) * 0.25 * 1000), 5, "0", STR_PAD_LEFT);
    }
    if ($ptsx >= 0) {
        $lkkey .= 'LNE' . str_pad((round($ptsx / 0.25) * 0.25 * 1000), 6, "0", STR_PAD_LEFT);
    } else {
        $lkkey .= 'LNO' . str_pad((round($ptsx / -0.25) * 0.25 * 1000), 6, "0", STR_PAD_LEFT);
    }
    $lkkeybase = $lkkey;
    $sqlcmd = "INSERT INTO csvdcb(rfr, lat, lon) VALUES ('$lkkey'," .
        (round($ptsy / 0.25) * 0.25) . "," . (round($ptsx / 0.25) * 0.25) . ");";
    varLog($sqlcmd);
    pg_query($db, $sqlcmd);
    $sqlcmd = "INSERT INTO csvddf(rfr, lat, lon) VALUES ('$lkkey'," .
        (round($ptsy / 0.25) * 0.25) . "," . (round($ptsx / 0.25) * 0.25) . ");";
    varLog($sqlcmd);
    pg_query($db, $sqlcmd);
    $lkkey = "";
    $rtfc = 0.5;
    if ($ptsy >= 0) {
        $lat=round($ptsy + $rtfc) - $rtfc;
        $lkkey .= 'LTN' . str_pad($lat* 1000, 5, "0", STR_PAD_LEFT);

    } else {
        $lat=round($ptsy + $rtfc) - $rtfc;
        $lkkey .= 'LTS' . str_pad($lat* -1000, 5, "0", STR_PAD_LEFT);
    }
    if ($ptsx >= 0) {
        $lon=round($ptsx + $rtfc) - $rtfc;
        $lkkey .= 'LNE' . str_pad($lon * 1000, 6, "0", STR_PAD_LEFT);

    } else {
        $lon=round($ptsx + $rtfc) - $rtfc;
        $lkkey .= 'LNO' . str_pad($lon * -1000, 6, "0", STR_PAD_LEFT);
    }
    pg_query($db, "INSERT INTO csvdgm(rfr, lat, lon) VALUES ('$lkkey'," .
        $lat . "," . $lon . ");");
    pg_query($db, "INSERT INTO csvdfc(rfr, lat, lon) VALUES ('$lkkey'," .
        $lat . "," . $lon . ");");
    $lkkeydws=$lkkey;
/*
    $lkkey = "";
    $ptsy = $ptsy + 1;
    if ($ptsy >= 0) {
        $lkkey .= 'LTN' . str_pad((round(($ptsy + $rtfc) / 1) - $rtfc) * 1 * 1000, 5, "0", STR_PAD_LEFT);
        $lat=round(($ptsy + $rtfc) / 1) - $rtfc;
    } else {
        $lkkey .= 'LTS' . str_pad((round(($ptsy + $rtfc) / -1) - $rtfc) * 1 * 1000, 5, "0", STR_PAD_LEFT);
        $lat=-(round(($ptsy + $rtfc) / -1) - $rtfc);
    }
    if ($ptsx >= 0) {
        $lkkey .= 'LNE' . str_pad((round(($ptsx + $rtfc) / 1) - $rtfc) * 1 * 1000, 6, "0", STR_PAD_LEFT);
        $lon=round(($ptsx + $rtfc) / 1) - $rtfc;
    } else {
        $lkkey .= 'LNO' . str_pad((round(($ptsx + $rtfc) / -1) - $rtfc) * 1 * 1000, 6, "0", STR_PAD_LEFT);
        $lon=-(round(($ptsx + $rtfc) / -1) - $rtfc);
    }
    pg_query($db, "INSERT INTO csvdgm(rfr, lat, lon) VALUES ('$lkkey'," .
        $lat. "," . $lon . ");");
    pg_query($db, "INSERT INTO csvdfc(rfr, lat, lon) VALUES ('$lkkey'," .
        $lat . "," .$lon. ");");
    $ptsy = $ptsy - 1;
    $ptsx = $ptsx + 1;
    $lkkey = "";
    if ($ptsy >= 0) {
        $lkkey .= 'LTN' . str_pad((round(($ptsy + $rtfc) / 1) - $rtfc) * 1 * 1000, 5, "0", STR_PAD_LEFT);
        $lat=round(($ptsy + $rtfc) / 1) - $rtfc;
    } else {
        $lkkey .= 'LTS' . str_pad((round(($ptsy + $rtfc) / -1) - $rtfc) * 1 * 1000, 5, "0", STR_PAD_LEFT);
        $lat=-(round(($ptsy + $rtfc) / -1) - $rtfc);
    }
    if ($ptsx >= 0) {
        $lkkey .= 'LNE' . str_pad((round(($ptsx + $rtfc) / 1) - $rtfc) * 1 * 1000, 6, "0", STR_PAD_LEFT);
        $lon=round(($ptsx + $rtfc) / 1) - $rtfc;
    } else {
        $lkkey .= 'LNO' . str_pad((round(($ptsx + $rtfc) / -1) - $rtfc) * 1 * 1000, 6, "0", STR_PAD_LEFT);
        $lon=-(round(($ptsx + $rtfc) / -1) - $rtfc);
    }
    pg_query($db, "INSERT INTO csvdgm(rfr, lat, lon) VALUES ('$lkkey'," .
        $lat. "," . $lon . ");");
    pg_query($db, "INSERT INTO csvdfc(rfr, lat, lon) VALUES ('$lkkey'," .
        $lat. "," . $lon. ");");
    $ptsx = $ptsx - 1;
*/
    $sqlcmd = "INSERT INTO csvddf(rfr, dws, lat, lon) VALUES ('$lkkeybase','$lkkeydws'," .
        (round($ptsy / 0.25) * 0.25) . "," . (round($ptsx / 0.25) * 0.25) . ");";
    varLog($sqlcmd);
    pg_query($db, $sqlcmd);

    //pg_query($db, "BEGIN");
    $srm = $xml->ACNDLK;
    $srm->RFR = $lkkeybase;
    $srm->DWS = $lkkeydws;
    $srm->ARF = $curARF;
    $srm->MINX = $ptsx;
    $srm->MINY = $ptsy;
    $mmm = xmlInsert($srm);
    updateMIP($mmm);
}

function updateMIP($mmm)
{
    global $db;
    $QMFD = "SELECT * FROM acndlk WHERE xxx=" . $mmm . ";";
    varLog($QMFD);
    $rsMFD = pg_query($db, $QMFD);
    while ($rowMFD = pg_fetch_assoc($rsMFD)) {


        $Query = "DELETE from acnmip WHERE mmm=$mmm;";
        pg_query($db, $Query);
        $inSQL="";

        $curdti = $rowMFD['dti'];
        $curkci = $rowMFD['kci'];
        $curdri = $rowMFD['dri'];
        $curdrd = $rowMFD['drd'];
        $curdrm = $rowMFD['drm'];
        $curdrl = $rowMFD['drl'];
        $currdi = $rowMFD['rdi'];
        $currdm = $rowMFD['rdm'];
        $curawc = $rowMFD['awc'];

        $kca = $curkci;
        $twa = $currdi * 1000 * $curawc;
        $twb = ($currdm - $currdi) * 1000 * $curawc;
        for ($i = 0; $i < $curdri; $i++) {
            $dta = date_add(date_create($curdti), date_interval_create_from_date_string("$i days"));
            if ($inSQL!="") $inSQL .= ",";
            $inSQL .= '(' . $mmm .
                ',\'' . date_format($dta, "Y-m-d") . '\',' . $kca . ',0,' . $twa . ',' . $twb . ')';
        }

        $inckc = ($rowMFD['kcm'] - $rowMFD['kci']) / $curdrd;
        $incrd = ($rowMFD['rdm'] - $rowMFD['rdi']) / $curdrd;
        for ($i = $curdri; $i < $curdri + $curdrd; $i++) {
            $dta = date_add(date_create($curdti), date_interval_create_from_date_string("$i days"));
            $kca = $kca + $inckc;
            $twa = $twa + $incrd * 1000 * $curawc;
            $twb = $twb - $incrd * 1000 * $curawc;
            if ($inSQL!="") $inSQL .= ",";
            $inSQL .= '(' . $mmm .
                ',\'' . date_format($dta, "Y-m-d") . '\',' . $kca . ',' . $inckc . ',' . $twa . ',' . $twb . ')';
        }

        $twb = 0;
        for ($i = $curdri + $curdrd; $i < $curdri + $curdrd + $curdrm; $i++) {
            $dta = date_add(date_create($curdti), date_interval_create_from_date_string("$i days"));
            if ($inSQL!="") $inSQL .= ",";
            $inSQL .= '(' . $mmm .
                ',\'' . date_format($dta, "Y-m-d") . '\',' . $kca . ',0,' . $twa . ',' . $twb . ')';
        }

        $inckc = ($rowMFD['kce'] - $rowMFD['kcm']) / $curdrl;
        for ($i = $curdri + $curdrd + $curdrm; $i < $curdri + $curdrd + $curdrm + $curdrl; $i++) {
            $dta = date_add(date_create($curdti), date_interval_create_from_date_string("$i days"));
            $kca = $kca + $inckc;
            if ($inSQL!="") $inSQL .= ",";
            $inSQL .= '(' . $mmm .
                ',\'' . date_format($dta, "Y-m-d") . '\',' . $kca . ',' . $inckc . ',' . $twa . ',' . $twb . ')';
        }
        $inSQL="INSERT INTO acnmip (mmm,dta,kca,kdi,twa,twb) VALUES " . $inSQL .";";
        varLog($inSQL);
        pg_query($db, $inSQL);
    }
}

If ($ACTION == "S20ACNDLK-UPDACNDLK") {
    $xml = simplexml_load_string($PARAM);
    xmlUpdate($xml->ACNDLK);
    $mmm = $xml->ACNDLK->XXX;
    updateMIP($mmm);
}


If ($ACTION == "S20ACNDLK-DELACNDLK") {
    $Query = "DELETE FROM acndlk WHERE xxx='$PARAM';";
    pg_query($db, $Query);
    exit;
}
?>