<?php
	//Used to prevent unauthorized users just typing in the URL to access
	//the site
	session_start();
	
	if(!(isset($_SESSION['MM_Username'])))
	{
		header("Location: Error401UnauthorizedAccess.php");
	}
	
?>
<html>
<head>
<title>Home Page_CFAPP_admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="dist/css/home_page_css.css" rel="stylesheet">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- Save for Web Slices (Home Page_CFAPP_admin.psd) -->
<div id="div_01">
<table id="Table_01" width="1225" height="793" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td rowspan="2">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_01.gif" width="1" height="455" alt=""></td>
		<td colspan="5">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_02.gif" width="1223" height="108" alt=""></td>
		<td>
			<img src="images/home_page/admin_images/spacer.gif" width="1" height="108" alt=""></td>
	</tr>
	<tr>
		<td colspan="5">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_03.gif" width="1223" height="347" alt=""></td>
		<td>
			<img src="images/home_page/admin_images/spacer.gif" width="1" height="347" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
        <a href="User_progress_page.php">
			<img src="images/home_page/admin_images/home_page_progress.gif" width="306" height="282" alt=""></a></td>
		<td colspan="2">
        <a href="user_wod_page.php">
			<img src="images/home_page/admin_images/home_page_wod.gif" width="306" height="282" alt=""></a></td>
		<td rowspan="2">
		<a href="user_compare_page.php">
			<img src="images/home_page/admin_images/home_page_compare.jpg" width="306" height="283" alt=""></td>
		<td>
        <a href="admin_page.php">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_07.gif" width="306" height="282" alt=""></a></td>
		<td>
			<img src="images/home_page/admin_images/spacer.gif" width="1" height="282" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_08.gif" width="1" height="55" alt=""></td>
		<td colspan="2" rowspan="2">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_09.gif" width="406" height="55" alt=""></td>
		<td rowspan="2">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_10.gif" width="205" height="55" alt=""></td>
		<td rowspan="2">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_11.gif" width="306" height="55" alt=""></td>
		<td>
			<img src="images/home_page/admin_images/spacer.gif" width="1" height="1" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_12.gif" width="306" height="54" alt=""></td>
		<td>
			<img src="images/home_page/admin_images/spacer.gif" width="1" height="54" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="1" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="305" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="101" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="205" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="306" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="306" height="1" alt=""></td>
		<td></td>
	</tr>
</table>
</div>
<!-- End Save for Web Slices -->
</body>
</html>