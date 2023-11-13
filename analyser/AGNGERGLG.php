<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$d_SVK="TYSYHBR";
$p_SPI = time();
setcookie("AGNSPI", "$p_SPI",$p_SPI+1200);
$p_SPA = md5($p_SPI.$d_SVK);
setcookie("AGNSPA", "$p_SPA", $p_SPI+1200);
function showlogin_a()
{
	global $p_SPA;
	print("<FORM ACTION=\"AGNSCFGSI.php\" METHOD=\"post\">\n");
	print("<INPUT TYPE=\"hidden\" NAME=\"ACTION\" VALUE=\"Cancelar\">");
	print("<TABLE BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"5\" ALIGN=\"CENTER\">\n");
	print("<TR><TD WIDTH=\"200\" ALIGN=\"RIGHT\"><B>Username</B></TD>");
	print("<TD WIDTH=\"400\"><INPUT TYPE=\"text\" NAME=\"preg_XXX\" SIZE=\"40\" MAXLENGTH\"64\" VALUE=\"\"></TD></TR>\n");
	print("<TR><TD WIDTH=\"200\" ALIGN=\"RIGHT\"><B>Password</B></TD>");
	print("<TD WIDTH=\"400\" ><INPUT TYPE=\"password\" NAME=\"g_PSS\" SIZE=\"20\" MAXLENGTH\"64\" VALUE=\"\"></TD></TR>\n");
	print("<TR><TD COLSPAN=\"2\" WIDTH=\"600\" ALIGN=\"CENTER\">\n");
	print("<img src=\"AGNGERGIM.php?m_TEXT=$p_SPA\">");
	print("</TD></TR>\n");
	print("<TR><TD WIDTH=\"200\" ALIGN=\"RIGHT\"><B>Verification Code</B></TD>");
	print("<TD WIDTH=\"400\" ><INPUT TYPE=\"text\" NAME=\"g_PVF\" SIZE=\"20\" MAXLENGTH\"64\" VALUE=\"\"></TD></TR>\n");
	print("<TR><TD COLSPAN=\"2\" WIDTH=\"600\" ALIGN=\"CENTER\">\n");
	print("<INPUT TYPE=\"submit\" NAME=\"Inserir\" VALUE=\"Login\">");
	print("</TD></TR>\n");
	print("</TABLE>\n");
	print("</FORM>\n");
	
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="rger/sta/style.css" media="screen">
    <link rel="stylesheet" href="rger/sta/estilo.css" media="screen">
<title>AGRINEXO SCF - Login</title>
</head>

<body style="padding-top: 40px; background-color: #AAAAAA;">
	<table align="center" width="700" border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
		<tr>
            <td><br><p style="text-align: center; font-weight: bold; font-size: 12pt">AGRINEXO SCF - Seasonal Climate Forecast Modeler<br><br>Login</p></td>
		</tr>
		<tr>
			<td >
			<hr>
							<?php  showlogin_a();?>
			</td>
		</tr>
        <tr>
            <td>

            </td>
		</tr>
        <?php include("AGNGERGFT.php");?>
	</table>
</body>
</html>