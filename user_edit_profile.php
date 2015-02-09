<?php
	session_start();
	
	if(!(isset($_SESSION['MM_Username'])))
	{
		header("Location: Error401UnauthorizedAccess.php");
	}
	
	$link = "index.html";
	if ($_SESSION['MM_Admin'] == "0") {$link = "User_Home_Page.php";} //Default Blank 
	else if ($_SESSION['MM_Admin'] == "1") {$link = "Admin_home_page.php";} // COMMENT 
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/assets/kb.ico">

    <title>Profiles</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="dist/css/first_page_css.css" rel="stylesheet">
	<link href="dist/css/profiles.css" rel="stylesheet">
  </head>
<!-- NAVBAR
================================================== -->
  <body>
    <div class="navbar-wrapper">
      <div class="container">

        <div class="navbar navbar-inverse navbar-static-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="profiles.php">Profiles</a>
            </div>
            <div class="navbar-collapse collapse">
              <ul id="main_nav" class="nav navbar-nav">
                <li id="comp_info"><?php echo "<a href='$link' >"; ?>Home</a></li>
                <li id="comp_info" ><a href="user_information.php">Account</a></li>
                <li id="competitors" class="active"><a href="#my_profile">My Profile</a></li>
                <li id="logout"><a href="#logout">Logout</a></li>
              </ul>
            </div>
          </div>
        </div>

      </div>
    </div>
	
    <div class="container marketing">
		<div class="row featurette">
			<div id="content" class="col-md-7">
				
				<h2 class="featurette-heading" id="heading"></h2>
				<div id="dyn_content">
				
				</div>
			</div>
		</div>
	  
      <footer>
        <p>&copy; 2014 CBox, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>
	</div><!-- /.container -->
	

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	
	<script src="dist/js/edit_user_profile.js"></script>
	
	<!-- Required for full calendar -->
	<script src='dist/lib/moment.min.js'></script>
	<script src="dist/jq_ui/js/jquery-1.10.2.js"></script>
	<script src="dist/jq_ui/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script src='dist/fullcalendar/fullcalendar.min.js'></script>

	<!-- Required for date picker -->
	<script src="dist/js/jquery.plugin.min.js"></script>
	<script src="dist/js/jquery.datepick.min.js"></script> 

	<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>

	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
		
<script>
	$(document).ready(function() {
		getUserInfo();
	});
	
	var files;
 
	// Add events
	$('input[type=file]').on('change', prepareUpload);
	 
	// Grab the files and set them to our variable
	function prepareUpload(event)
	{
		console.log("allo");
	  files = event.target.files;
	  console.log(files);
	}
	
	
	$("#main_nav").on("click", "li", function() {
		var toParse = $(this).find('a').attr('href');
		console.log(toParse);
		if(toParse == "#logout") {
			$.ajax(
			{ 
				url: "/CRUD/general/cbox_logout.php", 
				success: function(response) 
				{
					console.log("logged out...");
					window.location.replace("http://cboxbeta.com/login_bootstrap.php");
				} 
			});
		} 
	});
	
function getUserInfo() {
	$.ajax({
		type: "GET",
		url: "/CRUD/profiles/getUserInformation.php",
		dataType: "html",
		success: function(response) { 
			//console.log(response);
			$("#dyn_content").html(response);
		}
	});
}
</script>
		
		
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  //TEST
  ga('create', 'UA-50665970-1', 'cboxbeta.com');
  //LIVE
  //ga('create', 'UA-50665970-2', 'compete-box.com');
  ga('send', 'pageview');

</script>
	
  </body>
</html>