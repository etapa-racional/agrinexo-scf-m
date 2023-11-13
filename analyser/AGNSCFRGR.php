<?php
include("AGNGERGSX.php");
include("AGNSCFGSD.php");

if ($_GET['map'] == "mim") {

    $data = array(
        'items' => array()
    );

        $QDFD = "select acndlk.xxx, acndlk.rfr ,acndlk.dsc, acndlk.dws, " .
            "acndlk.minx, acndlk.miny, acndlk.maxx, acndlk.maxy, acndlk.arf " .
            "FROM acndlk WHERE ORDER BY acndlk.rfr;";
        $rsDFD = pg_query($db, $QDFD);



    while ($rowDFD = pg_fetch_assoc($rsDFD)) {

        $QSTT = "SELECT csvdcb.stt as status FROM csvdcb WHERE csvdcb.rfr='" .$rowDFD['rfr'] ."' AND csvdcb.stt IS NOT TRUE ".
                "UNION ".
                "SELECT csvdgm.stt as status FROM csvdgm WHERE csvdgm.rfr='" .$rowDFD['dws'] ."' AND csvdgm.stt IS NOT TRUE ".
                "UNION ".
                "SELECT csvdfc.stn as status FROM csvdfc WHERE csvdfc.rfr='" .$rowDFD['dws'] ."' AND csvdfc.stn IS NOT TRUE ".
                "UNION ".
                "SELECT csvddf.stx as status FROM csvddf WHERE csvddf.rfr='" .$rowDFD['rfr'] ."' AND csvddf.stx IS NOT TRUE ";
        varLog($QSTT);
        $rsSTT = pg_query($db, $QSTT);
        $datastatus="Operational";
        while ($rowSTT = pg_fetch_assoc($rsSTT)) {
                $datastatus="In preparation (estimated time 30s)";
        }

        $row = array(
            'id' => $rowDFD['xxx'],
            'fid' => $rowDFD['rfr'],
            'dsc' => $rowDFD['dsc'],
            'rfr' => '<div><p><strong>' . $rowDFD['dsc'] . '</strong></p><p>' .
                'Location Id: ' . $rowDFD['rfr'] . '<p></div>',
            'rfw' =>
                '<p>Grid Location Id: ' . $rowDFD['dws'] .  '</p>'.
                '<p>Data Status: '.$datastatus.'</p>',
            'dti' => $curdti,
            'dtu' => $curdtu,
            'dws' => $rowDFD['dws'],
            'ndv' => $curndv,
            'minx' => $rowDFD['minx'],
            'miny' => $rowDFD['miny'],
            'maxx' => $rowDFD['maxx'],
            'maxy' => $rowDFD['maxy'],
            'img' => $curimg,
            'lir' => $curlir,
            'tsk' => ''
        );
        $data['items'][] = $row;
    }
    header('Content-Type: application/json; charset=utf8');
    echo json_encode($data);
    exit;
}

?>