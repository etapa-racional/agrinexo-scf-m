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
    $QDFD = "select distinct csvdgm.xxx, dws, round(minx*2)/2 AS minx, round(miny*2)/2 AS miny ".
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
        "dws":"' . $rowDFD['dws'] . '",
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
    $ptsx = $ptsx / $ptscount;
    $ptsy = $ptsy / $ptscount;

    $lkkey = "";
    varLog($ptsx);
    varLog(str_pad((intval($ptsx * -10)), 3, "0", STR_PAD_LEFT));
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

    $srm = $xml->ACNDLK;
    $srm->RFR = $lkkeydws;
    $srm->DWS = $lkkeydws;
    $srm->ARF = $curARF;
    $srm->MINX = $lon;
    $srm->MINY = $lat;
    $mmm = xmlInsert($srm);
}

If ($ACTION == "S20ACNDLK-DELACNDLK") {
    $Query = "DELETE FROM acndlk WHERE xxx='$PARAM';";
    pg_query($db, $Query);
    exit;
}
?>