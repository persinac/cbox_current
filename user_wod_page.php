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
########
#REMOVE ME
########
if (!(isset($_SESSION['MM_UserID']))) {
  $colname_getUserBenchmarks = 1;
  $_SESSION['MM_Username']= "persinac";
}

	#if(!(isset($_SESSION['MM_Username'])))
	#{
	#	header("Location: Error401UnauthorizedAccess.php");
	#}

mysql_select_db($database_cboxConn, $cboxConn);

###
# Defualt view is Crossfit->Foundamental benchmarks
###
#$movement_id = "cft";
#$query_getUserWoD = "";
#$getUserWoD = mysql_query($query_getUserWoD, $cboxConn) or die(mysql_error());
#$rows = mysql_fetch_assoc($getUserBenchmarks);
#$totalRows_getUserBenchmarks = mysql_num_rows($getUserWoD);



	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<!-- Bootstrap core CSS and Custom CSS-->

<link href="dist/css/bootstrap.min.css" rel="stylesheet"> 
<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
<link href="dist/css/user_wod_page.css" rel="stylesheet">
<style>
	p#wod_descrip {text-indent:15px;}
</style>
</head>

<body>
<div id="container">
<div id="navbar_main">
  <ul id="navbar_main_ul"> 
	<li id="home" ><?php echo "<a href='$link' >"; ?>HOME</a></li> 
	<li id="compare"><a href="#" >COMPARE</a></li> 
	<li id="wod" class="active"><a href="#" >WOD</a></li> 
	<li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li> 
	<li id="account" ><a href="#" >ACCOUNT</a></li> 
  </ul> 
</div>

<div id="image_div">
	<img src="images/wod_page/WOD_PAGE_CFAPP2.jpg" width="1224" height="792" alt=""/>
</div>
<div id="data_container">
	
	<div id="box_loc">
		<h3 id="h_box_loc">box location and logo</h3>
    </div>
    
    <div id="wod_div"> 
    	<p id="temp_para">wod stuff goes here</p>
    </div>
    
    <div id="post_wod_div">
    	<p>post wod stuff</p>
    </div>
    
    <div id="strength_div">
    	<p>strength stuff</p>
    </div>
	
	<div id="add_custom_modal" class="modal" style="display:none; ">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Add WOD</h3>
        </div>
        <div class="modal-body">
        	<!-- Grab number of rows and place that many into here -->  
            <form method="POST" id="add_custom_wod_form" class="add_wod">
                <div id="add_wod_row">
                <h4>WOD</h4>
                    Movement: <input type="text" name="inter_movement[]" class="inter_movement" id="inter_movement_0"/> 
                    Weight (leave blank if bodyweight): <input type="text" name="inter_weight[]" class="inter_weight" id="inter_weight_0"/> 
                    Reps: <input type="text" name="inter_reps[]" class="inter_reps" id="inter_reps_0"/>
                    <p></p>   
                </div> <!-- END OF new_wod -->
             </form> 
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" id="load">Load RX Data</button>
          <button class="btn btn-success" id="submit">submit</button>
          <a href="#" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
	
	<!-- Rounds for time pop up -->
	<div id="rft_modal" class="modal" style="display:none; ">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Add WOD</h3>
        </div>
        <div class="modal-body">
        	<!-- Grab number of rows and place that many into here -->  
            <form method="POST" id="add_rft_wod_form" class="add_wod">
                <div id="add_wod_row">
                <h4>WOD</h4>
                    Time: <input type="text" name="rft_time" class="rft_time" id="rft_time" placeholder="00:00:00"/> 
                    <p></p>   
                </div> <!-- END OF new_wod -->
             </form> 
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" id="submit_rft">Submit</button>
          <a href="#" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
    
    <!-- AMRAP/Tabata pop up -->
    <div id="amrap_modal" class="modal" style="display:none; ">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Add WOD</h3>
        </div>
        <div class="modal-body">
        	<!-- Grab number of rows and place that many into here -->  
            <form method="POST" id="add_amrap_wod_form" class="add_wod">
                <div id="add_wod_row">
                <h4>WOD</h4>
                    Reps: <input type="text" name="amrap_reps" class="amrap_reps" id="amrap_reps" placeholder="total reps accumulated"/> 
                    <p></p>   
                </div> <!-- END OF new_wod -->
             </form> 
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" id="submit_amrap">Submit</button>
          <a href="#" class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
	
	
	<div class="btn-group" id="level_selector">
	  <button type="button" class="btn btn-default" id="lvl_rx">RX</button>
	  <button type="button" class="btn btn-default" id="lvl_inter">Intermediate</button>
	  <button type="button" class="btn btn-default" id="lvl_nov">Novice</button>
	</div>
	
	<div id="button_holder">
		<p>
			<button class="btn btn-primary" data-toggle="modal" name="wod_" id="wod_" type="button">Add WOD</button>
			</p><p>
			<a data-toggle="modal" href="#add_custom_modal" class="btn btn-default" name="wod_custom" id="wod_custom">Add Scaled WOD</a>
			</p><p>
			<button class="btn btn-primary" name="strength" id="strength" type="button">Add Strength</button>
			</p><p>
			<button class="btn btn-default" name="post_wod" id="post_wod" type="button">Add Post WOD</button>
			</p><p>
			<button class="btn btn-primary" name="rest" id="rest" type="button">Rest Day</button>
		</p>
	</div>

</div> <!-- END data_container -->
</div> <!-- END div_container -->


<!-- JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="dist/js/jquery.plugin.js"></script> 
<script type="text/javascript" src="dist/js/jquery.datepick.js"></script>

<script id="source" language="javascript" type="text/javascript">
$(document).ready(function() {
	//load everything
	//first get the current date
	getCurrentDate();
	getBoxName();
	//get wod
	getWOD("rx");
	//get postWod
	//get strength
});

var wod_description ="";
var wod_name = "";
var wod_type = "";
var level_perf = "";
var time = "";

$(function(){
	$("button#wod_").click(function() {
		var modalHeight = "";
		var modalWidth = "";
		//alert(wod_description + " " + wod_name + " "+wod_type+" "+level_perf+" " );
		if(wod_type == "rft" || wod_type == "RFT") {
			modalHeight = ($(this).height() / 2 ) + 'in !important';
			modalWidth = ($(this).width() / 2 ) + 'in !important';
			$('#rft_modal').css('margin-top', modalHeight);
			$('#rft_modal').css('margin-left', modalWidth);
			$('#rft_modal').modal('show');
		} else if (wod_type == "amrap" || wod_type == "AMRAP") {
			modalHeight = ($(this).height() / 2 ) + 'in !important';
			modalWidth = ($(this).width() / 2 ) + 'in !important';
			$('#amrap_modal').css('margin-top', modalHeight);
			$('#amrap_modal').css('margin-left', modalWidth);
			$('#amrap_modal').modal('show');
		}
		//alert(wod_description + " " + wod_name + " "+wod_type+" "+level_perf+" " );
	});
});

$(function() {
	$("button#lvl_rx").click(function() {
    	//alert("CLICKEDDDDD!!!!!");
		//add wod as-is
		getWOD("rx")
    });
});

$(function() {
	$("button#lvl_inter").click(function() {
    	//alert("CLICKEDDDDD!!!!!");
		//add wod
		getWOD("intermediate")
    });
});

$(function() {
	$("button#lvl_nov").click(function() {
    	//alert("CLICKEDDDDD!!!!!");
		//add wod
		getWOD("nov")
    });
});

$(function() {
	$("button#submit_rft").click(function() {
		var datastring = $('#add_rft_wod_form').serializeArray();
		var pwod_id = "";
		var strID = "";
		var actualTime = "00:15:09";
		var time_comp = "";
		var rounds_compl = 1;
		//alert("Variables: " +today+", "+actualTime);
		//alert("Variables: " +wod_description);
		//alert("Variables: " +level_perf);
		//alert("Variables: " +rounds_compl);
		//alert("Variables: " +time_comp);
		//alert("Variables: " +pwod_id);
		//alert("Variables: " +strID);
		
		//alert("datastring: " + datastring.value);
		$.each(datastring, function(i, field)
		{
			alert("DATA: " + field.name + " : " + field.value);
			actualTime = field.value;
		});
		var send = false;
		alert("actualtime: " + actualTime);
		if(send==true) {
			$.ajax({
				type: "POST",
				url: "addUserWOD.php",
				data: { 
					"wod_id": today, //build this based on box id and date - I think I've got this variable in PHP
					"wod_descrip" : "", 
					"level_perf" : level_perf,
					"rounds_compl": rounds_compl,
					"time" : time_comp, //this needs to equal nothing - mistype in backend - will remove later
					"pwod_id" : pwod_id, //same as wod_id
					"strength_id" : strID, //same as wod_id
					"actualTime" : actualTime
					}, 
				success: function(msg)
				{
					alert(msg);
					//loadWODData(msg, level_performed);
				}
			});
		}
    });
});

$(function() {
	$("button#submit_amrap").click(function() {
		var datastring = $('#add_amrap_wod_form').serializeArray();
		var pwod_id = "";
		var strID = "";
		var actualTime = "00:15:09";
		var time_comp = "";
		var rounds_compl = 1;
		
		//alert("datastring: " + datastring.value);
		$.each(datastring, function(i, field)
		{
			alert("DATA: " + field.name + " : " + field.value);
			rounds_compl = field.value;
		});
		alert("rounds_compl: " + rounds_compl);
		var send = false;
		if(send==true) {
			$.ajax({
				type: "POST",
				url: "addUserWOD.php",
				data: { 
					"wod_id": today, //build this based on box id and date - I think I've got this variable in PHP
					"wod_descrip" : "", 
					"level_perf" : level_perf,
					"rounds_compl": rounds_compl,
					"time" : time_comp, //this needs to equal nothing - mistype in backend - will remove later
					"pwod_id" : pwod_id, //same as wod_id
					"strength_id" : strID, //same as wod_id
					"actualTime" : actualTime
					}, 
				success: function(msg)
				{
					alert(msg);
					//loadWODData(msg, level_performed);
				}
			});
		}
    });
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

function getBoxName()
{
	var boxName = "";
	//alert("junior");
	//alert(currentDate);
	
	$.ajax({                                     
		  url: "getUserBoxInfo.php", //the script to call to get data             
		  success: function(response) //on recieve of reply
		  {
			  //alert(response);
			loadUserBoxInfo(response);
		  } 
		});
	
}

function getWOD(level_performed)
{
	$("#temp_para").empty();
	//alert("level_performed: " + level_performed);
	level_perf = level_performed;
	$.ajax({
		type: "POST",
		url: "getCurrentWOD.php",
		data: { "datastring" : today, "lvl_perf": level_performed}, 
		dataType: "json",
		success: function(msg)
		{
			//alert(msg);
			loadWODData(msg, level_performed);
		}
	});
}

function loadUserBoxInfo(data)
{
	//alert(data);
	var currentDate = today;
	//alert("DATA: " + data + ", today: " + currentDate);
	$('#box_loc').empty();
	
	var html = "";
	html += '<h1>'+data+'</h1><h3>'+currentDate+'</h3>';
	$('#box_loc').html(html);
	
}

function loadWODData(data, level_performed)
{
	var html_sec1 = "";
	var sec1_classID = "cftwod_sec1_data";   
	var wodname = "";
	var type_of_wod = "";
	var descrip = "";
	var descripLength = 0;
	
	
		wodname = data[0].name_of_wod;
		type_of_wod = data[0].type_of_wod;
		if(level_performed == "rx") {
			descrip = data[0].rx_descrip;
		} else if (level_performed == "intermediate") {
			descrip = data[0].inter_descrip;
		} else {
			descrip = data[0].nov_descrip;
		}
		
		if(wodname != "-") {
			html_sec1 += "<h3>"+wodname+"</h3>";
		}
		html_sec1 +="<p>Type of WOD: "+type_of_wod+"</p>";
		html_sec1 +="<p>Description: </p>";
		//alert(descrip);
		$.map( descrip.split(','), function( n ) {
		  //alert(n.length);
		  descripLength += n.length;
		  //alert(descripLength);
		  var colon = n.indexOf(":");
		  //if(){}
		  //alert("index of colon = "+colon);
		  if(colon > 0) {
			  ////alert("index of colon = "+colon +", n.substring(0,colon) "+n.substring(0,colon+1));
			  html_sec1 +="<p id=\"wod_descrip\">"+n.substring(0,colon+1)+"</p>";
			  //alert("n.substring(colon+2) "+n.substring(colon+2));
			  html_sec1 +="<p id=\"wod_descrip\">"+n.substring(colon+2)+"</p>";
			} else {
		  html_sec1 +="<p id=\"wod_descrip\">"+n+"</p>";
			}
		});
		
	wod_type = type_of_wod;
	wod_description = descrip;
	//alert("type of wod: " + type_of_wod + ", wod_type: " + wod_type);
	//Update html content
	if(descripLength > 150){//alert(">150"); 
	$('#wod_div').addClass("long_description");}
	$('#wod_div').html(html_sec1);
}
</script>

</body>
</html>
