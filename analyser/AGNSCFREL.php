<?php
include("AGNGERGSX.php");
include("AGNSCFGSD.php");

if ($_GET["ACTION"]=="X"){

    $DET=$_GET["DET"];

    $Query = "SELECT csvdgm.rfr as src, 'TME' AS rfr, csvmfc.fsr as fsr, MAX(csvmfc.tms) AS tgt," .
        "MAX(csvmfc.ftm) AS ori," .
        "ROUND(AVG(csvrgm.tmd+csvmfc.tmd)::numeric,2) AS mfc, " .
        "ROUND(AVG(csvmgm.tmd)::numeric,2) AS vfr," .
        "ROUND(AVG(ABS(csvrgm.tmd+csvmfc.tmd-csvmgm.tmd))::numeric,2) AS efc, " .
        "ROUND(CORR(csvmfc.tmd,csvmgm.tmd-csvrgm.tmd)::numeric,2) AS pfc, " .
        "ROUND(AVG(ABS(csvrgm.tmd-csvmgm.tmd))::numeric,2) AS enm" .
        "  FROM " .
        "csvdfc INNER JOIN csvdgm ON csvdfc.rfr=csvdgm.rfr " .
        "INNER JOIN csvmgm ON csvdgm.xxx=csvmgm.mmm " .
        "INNER JOIN csvrgm ON csvdgm.xxx=csvrgm.mmm " .
        "INNER JOIN csvmfc ON csvdfc.xxx=csvmfc.mmm " .
        "WHERE EXTRACT(Month From csvmfc.tms)=EXTRACT(Month From csvmgm.tms) AND EXTRACT(Year From csvmfc.tms)=EXTRACT(Year From csvmgm.tms) " .
        "AND EXTRACT(Month From csvmfc.tms)=EXTRACT(Month From csvrgm.tms) AND " .
        "csvdgm.rfr='" . pfq($DET) . "' " .
        "GROUP BY csvdgm.rfr, csvmfc.fsr ";

    $Query .= " UNION ";

    $Query .= "SELECT csvdgm.rfr as src, 'TMX' AS rfr, csvmfc.fsr as fsr, MAX(csvmfc.tms) AS tgt, " .
        "MAX(csvmfc.ftm) AS ori, " .
        "ROUND(AVG(csvrgm.tmx+csvmfc.tmx)::numeric,2) AS mfc, " .
        "ROUND(AVG(csvmgm.tmx)::numeric,2) AS vfr," .
        "ROUND(AVG(ABS(csvrgm.tmx+csvmfc.tmx-csvmgm.tmx))::numeric,2) AS efc, " .
        "ROUND(CORR(csvmfc.tmx,csvmgm.tmx-csvrgm.tmx)::numeric,2) AS pfc, " .
        "ROUND(AVG(ABS(csvrgm.tmx-csvmgm.tmx))::numeric,2) AS enm" .
        "  FROM " .
        "csvdfc INNER JOIN csvdgm ON csvdfc.rfr=csvdgm.rfr " .
        "INNER JOIN csvmgm ON csvdgm.xxx=csvmgm.mmm " .
        "INNER JOIN csvrgm ON csvdgm.xxx=csvrgm.mmm " .
        "INNER JOIN csvmfc ON csvdfc.xxx=csvmfc.mmm " .
        "WHERE EXTRACT(Month From csvmfc.tms)=EXTRACT(Month From csvmgm.tms) AND EXTRACT(Year From csvmfc.tms)=EXTRACT(Year From csvmgm.tms) " .
        "AND EXTRACT(Month From csvmfc.tms)=EXTRACT(Month From csvrgm.tms) AND " .
        "csvdgm.rfr='" . pfq($DET) . "' " .
        "GROUP BY csvdgm.rfr, csvmfc.fsr ";

    $Query .= " UNION ";

    $Query .= "SELECT csvdgm.rfr as src, 'TMI' AS rfr, csvmfc.fsr as fsr, MAX(csvmfc.tms) AS tgt, " .
        "MAX(csvmfc.ftm) AS ori, " .
        "ROUND(AVG(csvrgm.tmn+csvmfc.tmn)::numeric,2) AS mfc, " .
        "ROUND(AVG(csvmgm.tmn)::numeric,2) AS vfr," .
        "ROUND(AVG(ABS(csvrgm.tmn+csvmfc.tmn-csvmgm.tmn))::numeric,2) AS efc, " .
        "ROUND(CORR(csvmfc.tmn,csvmgm.tmn-csvrgm.tmn)::numeric,2) AS pfc, " .
        "ROUND(AVG(ABS(csvrgm.tmn-csvmgm.tmn))::numeric,2) AS enm" .
        "  FROM " .
        "csvdfc INNER JOIN csvdgm ON csvdfc.rfr=csvdgm.rfr " .
        "INNER JOIN csvmgm ON csvdgm.xxx=csvmgm.mmm " .
        "INNER JOIN csvrgm ON csvdgm.xxx=csvrgm.mmm " .
        "INNER JOIN csvmfc ON csvdfc.xxx=csvmfc.mmm " .
        "WHERE EXTRACT(Month From csvmfc.tms)=EXTRACT(Month From csvmgm.tms) AND EXTRACT(Year From csvmfc.tms)=EXTRACT(Year From csvmgm.tms) " .
        "AND EXTRACT(Month From csvmfc.tms)=EXTRACT(Month From csvrgm.tms) AND " .
        "csvdgm.rfr='" . pfq($DET) . "' " .
        "GROUP BY csvdgm.rfr, csvmfc.fsr ";

    $Query .= " UNION ";

    $Query .= "SELECT csvdgm.rfr as src, 'PRC' AS rfr, csvmfc.fsr as fsr, MAX(csvmfc.tms) AS tgt, " .
        "MAX(csvmfc.ftm) AS ori, " .
        "ROUND(AVG(csvrgm.prc+csvmfc.prc)::numeric,2) AS mfc, " .
        "ROUND(AVG(csvmgm.prc)::numeric,2) AS vfr," .
        "ROUND(AVG(ABS(csvrgm.prc+csvmfc.prc-csvmgm.prc))::numeric,2) AS efc, " .
        "ROUND(CORR(csvmfc.prc,csvmgm.prc-csvrgm.prc)::numeric,2) AS pfc, " .
        "ROUND(AVG(ABS(csvrgm.prc-csvmgm.prc))::numeric,2) AS enm" .
        "  FROM " .
        "csvdfc INNER JOIN csvdgm ON csvdfc.rfr=csvdgm.rfr " .
        "INNER JOIN csvmgm ON csvdgm.xxx=csvmgm.mmm " .
        "INNER JOIN csvrgm ON csvdgm.xxx=csvrgm.mmm " .
        "INNER JOIN csvmfc ON csvdfc.xxx=csvmfc.mmm " .
        "WHERE EXTRACT(Month From csvmfc.tms)=EXTRACT(Month From csvmgm.tms) AND EXTRACT(Year From csvmfc.tms)=EXTRACT(Year From csvmgm.tms) " .
        "AND EXTRACT(Month From csvmfc.tms)=EXTRACT(Month From csvrgm.tms) AND " .
        "csvdgm.rfr='" . pfq($DET) . "' " .
        "GROUP BY csvdgm.rfr, csvmfc.fsr ";
    $Query .= " ORDER BY rfr,fsr;";

    varLog($Query);
    $rsDFD = pg_query($db, $Query);

    $data = array(
        'items' => array()
    );

    while ($row = pg_fetch_assoc($rsDFD)) {
        $data['items'][] = $row;
    }
    header('Content-Type: application/json; charset=utf8');
    echo json_encode($data['items']);
    die;
}
?>