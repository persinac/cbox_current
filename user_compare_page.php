<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_getUserBenchmarks = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserBenchmarks = $_SESSION['MM_UserID'];
}
$link = "index.html";
if ($_SESSION['MM_Admin'] == "0") {$link = "User_Home_Page.php";} //Default Blank 
else if ($_SESSION['MM_Admin'] == "1") {$link = "Admin_home_page.php";;} // COMMENT 
	
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>COMPARE</title>
    <!-- Bootstrap core CSS and Custom CSS-->
    <link href="dist/css/user_compare_page.css" rel="stylesheet">
    <link href="dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="dist/css/jquery.datepick.css" rel="stylesheet">
    
</head>

<body>

<div id="my_container">

<div id="navbar_main">
  <ul id="navbar_main_ul"> 
    <li id="home" ><?php echo "<a href='$link' >"; ?>HOME</a></li> 
    <li id="compare" class="active"><a href="#" >COMPARE</a></li> 
    <li id="wod"><a href="#" >WOD</a></li> 
    <li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li> 
    <li id="account" ><a href="#" >ACCOUNT</a></li> 
  </ul> 
</div>


    <div id="image_div">
        <img src="images/compare_page/COMPARE_PAGE_CFAPP2.jpg" width="1224" height="792" alt=""/>
    </div>
    
    <div id="my_data_container">
        <div id="what_to_compare">
            <h4>Compare by: </h4>
            <form id="what_to_compare_form">
                Box <input type="radio" name="area_to_compare" class="radio_butts" value="box">
                Region <input type="radio" name="area_to_compare" class="radio_butts" value="reg">
                Country <input type="radio" name="area_to_compare" class="radio_butts" value="cou"> <br><br>
                <select id="wod_type_selector" name="wod_type_selector" class="selector">
          <option value="RFT">WODS</option>
          <option value="AMRAP">Core Lifts</option>
          <option value="TABATA">Olympic</option>
          <option value="GIRLS">Powerlifting</option>
          <option value="HERO">Girls</option>
          <option value="HERO">Heroes</option>
          <option value="HERO">Open</option>
          <option value="HERO">Regionals</option>
          <option value="HERO">Games</option>
		</select><br>
                Date: <input type="text" name="date" class="datepicker" id="datepicker"/> 
            </form>
        </div> <!-- END WHAT_TO_COMPARE -->
        
        <div id="wod_list">
        	<table width="538" rules="cols" id="tbl_wod_list">
            	<tr>
                	<th width="90" height="25">Date</th>
                    <th width="92">Type of WoD</th>
                    <th width="58">Level</th>
                    <th width="220">Description</th>
                    <th width="34">Place</th>
                </tr>
                <tbody class="tbl_body_wod_list">
                </tbody>
        	</table>
        </div> <!-- END WOD_LIST -->
        
    </div> <!-- END DATA_CONTAINER -->

</div> <!-- END CONTAINER -->

<!-- JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/jquery.plugin.min.js"></script>
<script src="dist/js/jquery.datepick.min.js"></script>

<script>
/*
* Once the document is  loaded...
*
*/
$(document).ready(function() {
	//event.preventDefault();
	alert("READY!!!");
});

$("#navbar_main_ul li").click(function() {
		//event.preventDefault();
		var id = jQuery(this).attr("id");
		if(id=="logout" || id=="LOGOUT") {
			alert("LOGGING OUT");
			window.location.replace("http://cboxbeta.com/login_bootstrap.php");
		}
	});	

/*
* Set up the datepicker object
*
*/
$(function() {
	//event.preventDefault();
    $("#datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
  });
  
  var today = new Date();
function getCurrentDate()
{
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	
	if(dd<10) {
		dd='0'+dd;
	} 
	
	if(mm<10) {
		mm='0'+mm;
	} 
	today = yyyy+'-'+mm+'-'+dd;
	//alert("today");
}
  
</script>

</body>
</html>