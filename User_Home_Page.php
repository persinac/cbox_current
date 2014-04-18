<?php
	session_start();
	
	if(!(isset($_SESSION['MM_Username'])))
	{
		header("Location: Error401UnauthorizedAccess.php");
	}
	
?>

<html>
<head>
<title>Home Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Custom styles for this template -->
    <link href="dist/css/home_page_css.css" rel="stylesheet">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- Save for Web Slices (Home Page_CFAPP.psd) -->
<div id="div_01">
<table id="Table_01" width="1224" height="792" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td rowspan="3">
			<img src="images/home_page/images/Home-Page_CFAPP_01.gif" width="1" height="455" alt=""></td>
		<td colspan="3">
			<img src="images/home_page/images/Home-Page_CFAPP_02.gif" width="1223" height="108" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="images/home_page/images/Home-Page_CFAPP_03.gif" width="1223" height="346" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="images/home_page/images/Home-Page_CFAPP_04.gif" width="826" height="1" alt=""></td>
		<td rowspan="2">
			<img src="images/home_page/images/home_page_compare.jpg" width="397" height="283" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
        <a href="User_progress_page.php">
			<img src="images/home_page/images/home_page_progress.gif" width="407" height="282" alt=""></a></td>
		<td>
        <a href="user_wod_page.php">
			<img src="images/home_page/images/home_page_wod.gif" width="420" height="282" alt=""></a></td>
	</tr>
	<tr>
		<td>
			<img src="images/home_page/images/home_page_progress-08.gif" width="1" height="8" alt=""></td>
		<td rowspan="2">
			<img src="images/home_page/images/Home-Page_CFAPP_09.gif" width="406" height="55" alt=""></td>
		<td colspan="2" rowspan="2">
			<img src="images/home_page/images/Home-Page_CFAPP_10.gif" width="817" height="55" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/home_page/images/Home-Page_CFAPP_11.gif" width="1" height="47" alt=""></td>
	</tr>
</table>
</div>
<!-- End Save for Web Slices -->
</body>
</html>