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
########
#REMOVE ME
########
if (!(isset($_SESSION['MM_UserID']))) {
  $colname_getUserBenchmarks = 1;
  $_SESSION['MM_Username']= "persinac";
}
mysql_select_db($database_cboxConn, $cboxConn);

###
# Defualt view is Crossfit->Foundamental benchmarks
###
$movement_id = "cft";
$query_getUserCFBenchmarks = "select bs.mvmnt_id, bs.weight, bs.date_achieved from benchmarks bs join (select user_id, mvmnt_id, max(weight) weight from benchmarks 
	group by user_id, mvmnt_id) bb on bs.mvmnt_id = bb.mvmnt_id AND bs.weight = bb.weight
WHERE bs.user_id = $colname_getUserBenchmarks AND bs.mvmnt_id LIKE '{$movement_id}%' 
ORDER BY bs.mvmnt_id ";
$getUserCFBenchmarks = mysql_query($query_getUserCFBenchmarks, $cboxConn) or die(mysql_error());
#$rows = mysql_fetch_assoc($getUserBenchmarks);
$totalRows_getUserBenchmarks = mysql_num_rows($getUserCFBenchmarks);


	if(!(isset($_SESSION['MM_Username'])))
	{
		header("Location: Error401UnauthorizedAccess.php");
	}
	
?>
<?php
function nameOfMovement($movementID)
{ 
 	$value = "Unknown Movement"; 
	if ($movementID == "cft_01") { 
	  $value = "Back Squat"; 
	} 
	else if ($movementID == "cft_02") { 
	  $value = "Front Squat"; 
	} 
	else if ($movementID == "cft_03") { 
	  $value = "OverHead Squat"; 
	}
	else if ($movementID == "cft_04") { 
	  $value = "Deadlift"; 
	}
	else if ($movementID == "cft_05") { 
	  $value = "SDLHP"; 
	}
	else if ($movementID == "cft_06") { 
	  $value = "Power Clean"; 
	}
	else if ($movementID == "cft_07") { 
	  $value = "OverHead Press"; 
	}
	else if ($movementID == "cft_08") { 
	  $value = "Push Press"; 
	}
	else if ($movementID == "cft_09") { 
	  $value = "Push Jerk"; 
	}
  return $value; 
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	<!-- Custom styles for this template -->
    <link href="dist/css/user_progress_page.css" rel="stylesheet">
</head>


<body>
<div id="container">

<div id="navbar_main"> 
  <ul id="navbar_main_ul"> 
	<li id="home" ><a href="#" >HOME</a></li> 
	<li id="compare"><a href="#" >COMPARE</a></li> 
	<li id="wod"><a href="#" >WOD</a></li> 
	<li id="progress" class="active"><a href="#" >PROGRESS</a></li> 
	<li id="account" ><a href="#" >ACCOUNT</a></li> 
  </ul> 
</div>

<div id="navbar_sub"> 
  <ul id="navbar_sub_ul"> 
	<li id="cft" class="active"><a href="#" >CROSSFIT</a></li> 
	<li id="oly"><a href="#" >OLYMPIC</a></li> 
	<li id="pwr"><a href="#" >POWERLIFTING</a></li> 
	<li id="wod" ><a href="#" >WODs</a></li> 
	<li id="mis" ><a href="#" >MISC</a></li> 
  </ul> 
</div>

<div id="image_div">
<img src="images/PROGRESS_PAGE_CFAPP_template.jpg" width="1224" height="792" alt=""/>
</div>
<div id="data_container">
	<div id="cft_foundamentals_sec1_div">
	<table class="cft_foundamentals_sec1">

		<tr class="sec1">
		</tr>

	</table>
    </div>
    <div id="cft_foundamentals_sec2_div">
    <table class="cft_foundamentals_sec2">
    
		<tr class="sec2">
		<td></td>
		<td></td>
		</tr>
      
		<tr class="sec2">
		<td></td>
		<td></td>
		</tr>
      
	</table>
    </div>
    <div id="cft_foundamentals_sec3_div">
    <table class="cft_foundamentals_sec3">
   
		<tr class="sec3">
		<td></td>
		<td></td>
		</tr>
       
		<tr class="sec3">
		<td></td>
		<td></td>
		</tr>
      
	</table>
    </div>

</div>
</div>

	<!-- JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript">
	var lastClickedID = "";
	var firstTime = 0;
	function adjustText(clicked_id){
		
		growText(clicked_id); 
	}
	function growText(clicked_id) { 
		alert("Clicked_id: " + document.getElementById(clicked_id));
		var text = document.getElementById(clicked_id);
		text.style.fontSize = "22px";
		
		if(firstTime == 0) {
			firstTime = 1;
		}
		else {
			shrinkText(lastClickedID);
		}
		lastClickedID = document.getElementById(clicked_id);
	}
	function shrinkText(lastClicked_id) {
		var text = document.getElementById(lastClicked_id.id);
		text.style.fontSize = "16px";  
	}
	</script>
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script id="source" language="javascript" type="text/javascript">

	$(document).ready(function() {
		//$("#navbar_sub_ul li").click(function(event) {
		$("#navbar_sub_ul li").click(function() {
			var id = jQuery(this).attr("id");
			alert(id);
			getData(id);
    	});	
	});

  function getData(movement_id) 
  {
    //-----------------------------------------------------------------------
    // 2) Send a http request with AJAX http://api.jquery.com/jQuery.ajax/
    //-----------------------------------------------------------------------
    $.ajax({ 
	  type:"POST",                                     
      url: "php_json_test.php",                  //the script to call to get data          
      data: { dataString: movement_id },                 //you can insert url argumnets here to pass to api.php
      dataType: "json",                //data format      
      success: function(response)          //on recieve of reply
      {
		  //alert(movement_id);
		loadData(response);
      } 
    });
  } 
  
  function loadData(data) //
  {
	  alert("Data: " + data);
	  var html_sec1 = "";
	  var html_sec2 = "";
	  var html_sec3 = "";
        var m = "m_"; 
		var w = "w_";             //get id
        var vname;           //get name
		for(var i = 0; i < data.length; i++) {
			//eventually want a function to convert 
			//mvmnt_id into user friendly movement
			if(i <= 2) {
                    html_sec1 += "<td>"+
						data[i].mvmnt_id+"</td><td>"+data[i].weight+"</td>";
			}
        }
		alert("HTML: " + html_sec1);
        //--------------------------------------------------------------------
        // 3) Update html content
        //--------------------------------------------------------------------
        $('.sec1').html(html); //Set output element html
		$('#cft_test').html(html);
        //recommend reading up on jquery selectors they are awesome 
        // http://api.jquery.com/category/selectors/
  }

  </script>
    
</body>
</html>
<?php
mysql_free_result($getUserCFBenchmarks);
?>
