<?php
session_start();

if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}
?>
<html>
<head>
<title>Admin Home Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="dist/css/home_page_css.css" rel="stylesheet">
</head>
<body>

<div id="div_01">

<table id="Table_01" width="1225" height="793" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td rowspan="2">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_01.gif" width="1" height="455" alt=""></td>
		<td colspan="5">
			<img src="images/home_page/admin_images/Home-Page_CFAPP_admin_02.gif" width="1223" height="108" alt="">
			<ul id="navcontainer">
				<li id="acct" ><a href="user_information.php">Account</a></li>
				<li id="u_profiles" ><a href="profiles.php">Profiles</a></li>
				<li id="prof"><a href="#my_profile">My Profile</a></li>
				<li id="logout"><a href="#logout">Logout</a></li>
			</ul>
		</td>
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

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>

<script>

$("#navcontainer li").click(function() {
		//event.preventDefault();
		var id = jQuery(this).attr("id");
		if(id=="logout" || id=="LOGOUT") {
			alert("LOGGING OUT");
			console.log("logging out...");
		
			$.ajax(
			{ 
				url: "cbox_logout.php", //the script to call to get data  
				success: function(response) //on recieve of reply
				{
					console.log("logged out...");
					window.location.replace("http://cboxbeta.com/login_bootstrap.php");
				} 
			});
		}
	});

</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50665970-1', 'compete-box.com');
  ga('send', 'pageview');

</script>
</body>
</html>