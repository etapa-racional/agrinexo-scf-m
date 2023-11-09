<?php
phpinfo();

$servername = getenv(strtoupper(getenv("DATABASE_SERVICE_NAME"))."_SERVICE_HOST");
$database =  getenv("DATABASE_NAME");
$username = getenv("DATABASE_USER");
$password = getenv("DATABASE_PASSWORD");
echo "<p>$servername $database $username</p>";
$db = pg_pconnect("host=". $servername ." dbname=". $database ." user=".$username ." password=".$password);
$qr = "SELECT * FROM sampletb;";
$rs = pg_query($db, $qr);
while ($rw = pg_fetch_assoc($rs)) {
        echo "<p>".$rw['xxx']."</p>";
}
?>
