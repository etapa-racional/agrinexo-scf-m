<?php
$request = file_get_contents("php://input");
if (isset($_POST['XMP'])){
	$request=urldecode($_POST['XMP']);
}
if (isset($_GET['XMP'])){
	$request=$_GET['XMP'];	
}
varLog($request);
$rxml =  simplexml_load_string($request);

if ($rxml->HACT!="") {
	$ACTION=$rxml->HACT;
	if($rxml->HPRM!=""){
		if ($rxml->HPRM->XXX!="") {
			$PARAM=$rxml->HPRM->XXX;
		} else {
			$PARAM=$rxml->HPRM->asXML();
		}
	}
}

function pfq($value){
    global $db;
    $value=strip_tags($value);
    return pg_escape_string($db,$value);
}

function pfx($value){
	return str_replace("&", "&amp;",(string) $value);
}

function varLog($fcontent){

	$fcontent ="[" . time() . "] " . $fcontent . "\n";
	$pubfiledir="../extras/";
	$pubfiletitle="log.txt";
	$filename = $pubfiledir . $pubfiletitle;
	if (!$handle = fopen($filename, "ab"))
	{
		echo "Cannot open file ($filename)";
		exit;
	}
	if (fwrite($handle, $fcontent) === FALSE) {
		echo "Cannot write to file ($filename)";
		exit;
	}
	fclose($handle);

}

function xmlResult($RowName,$Query)   {
	global $db;
	error_log($Query);
	$rs = pg_query($db, $Query);
	$coln = pg_num_fields($rs);
	$xmlt = new SimpleXMLElement("<".$RowName."S></".$RowName."S>");
	while ($row =  pg_fetch_row($rs))
	{
		$xmlr = $xmlt->addChild($RowName);
		for ($i = 0; $i < $coln; $i++) {
			$fNAME=pg_field_name($rs,$i);
			$fNAME=strtoupper($fNAME);
			$fVALUE = pfx($row[$i]);
			$xmlr->addChild($fNAME,$fVALUE);
		}
	}
	return $xmlt->asXML();
}

function xmlInsert($xml) {
	global $db;
	global $STORK;
	$Flds="";
	$Vals="";
	foreach($xml->children() as $child){
		if($child->getName()!="XXX" and $child!="null")
		{
			if ($Flds!="") $Flds .=",";
			if ($Vals!="") $Vals .=",";
			$Flds .= $child->getName();
			if ($child!=""){
				$Vals .= "'" . pfq($child) . "'";
			} else {
				$Vals .= "NULL";
			}
		}
	}
	$Query  = "INSERT INTO " . strtolower($xml->getName()) . " ";
	$Query  .= "(" . strtolower($Flds) . ") ";
	$Query  .= "VALUES (" . $Vals . ") RETURNING xxx;";
	varLog($Query);
	$insert_result=pg_query($db, $Query);
    $insert_row = pg_fetch_row($insert_result);
    $insert_xxx = $insert_row[0];
    return $insert_xxx;
}

function xmlUpdate($xml) {
	global $db;
	global $STORK;
	$Flds="";
	$WIV=100;
	$Crit="";
	foreach($xml->children() as $child){
		if($child->getName()=="XXX") {
			$Crit="xxx=$child";
		}
		elseif ($child!="null"){
			if ($Flds!="") $Flds .=",";
			if ($child!=""){
				$Flds .= strtolower($child->getName()) . "='" . pfq($child) . "'";
			} else {
				$Flds .= strtolower($child->getName()) . "=NULL";
			}

		}
	}
	$Query   = "UPDATE " . strtolower($xml->getName()) . " ";
	$Query  .= "SET " . $Flds . " ";
	$Query  .= "WHERE " . $Crit . ";";
	varLog($Query);
	pg_query($db, $Query);
}

?>
