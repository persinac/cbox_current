<?php require_once('Connections/cboxConn.php'); ?>
<?php
session_start();
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}
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
else if ($_SESSION['MM_Admin'] == "1") {$link = "Admin_home_page.php";} // COMMENT 

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>WOD</title>
	<!-- Bootstrap core CSS and Custom CSS-->
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">
 	<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
	<link href="dist/css/user_wod_page.css" rel="stylesheet">
	
    <link href="dist/css/jquery.datepick.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="dist/css/jquery.qtip.css" />

	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
	
	<link href="dist/css/wod_display.css" rel="stylesheet">
	<link href="dist/css/navbar_extended.css" rel="stylesheet">
	
</head>

<body>
<div id="container">
	
	<nav class="navbar navbar-default nav_extension_color nav_extension_position" role="navigation">
		<div class="container-fluid">
			<div>
			  <ul id="main_nav_bar" class="nav navbar-nav nav_extension"> 
				<li id="home" ><?php echo "<a href='$link' >"; ?>HOME</a></li> 
				<li id="compare"><a href="user_compare_page.php" >COMPARE</a></li> 
				<li id="wod" class="dropdown active">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">WOD <span class="caret"></span></a>
					<ul id="dd_nav_bar" class="dropdown-menu" role="menu">
						<li id="daily"><a href="#" >Daily WOD</a></li>
						<li id="calendar"><a href="#" >Calendar</a></li>
					</ul>
				</li>
				<li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li> 
				<li id="account" ><a href="user_information.php" >ACCOUNT</a></li>
				<li id="logout" ><a href="#" >LOGOUT</a></li>
			  </ul> 
			</div>
		</div>
	</nav>
	
	<div id="image_div">
		<img src="images/wod_page/WOD_PAGE_CFAPP2.jpg" width="1224" height="792" alt=""/>
	</div>
	<div id="data_container">
		
		<div id="box_loc">
			<h3 id="h_box_loc"></h3>
		</div>
		
		<div id="wod_div"> 
			<p id="temp_para"></p>
		</div>
		
		<div id="post_wod_div">
			<p id="temp_pwod"></p>
		</div>
		
		<div id="strength_div">
			<p id="temp_strength"></p>
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
		
		<!-- Strength pop up -->
		<div id="strength_modal" class="modal" style="display:none; ">
			<div class="modal-header">
			  <a class="close" data-dismiss="modal">×</a>
			  <h3>Add Strength</h3>
			</div>
			<div class="modal-body">
				<!-- Grab number of rows and place that many into here -->  
				<form method="POST" id="add_strength_form" class="add_strength">
					<div id="add_strength_row">
					<h4>Strength</h4>
						Set 1 Weight: <input type="text" name="strength_weight_0" class="strength_weight" id="strength_weight_0" /> lbs Reps Completed: <input type="text" name="strength_reps_0" class="strength_reps" id="strength_reps_0" /> 
						<p></p>   
					</div> <!-- END OF new_strength -->
				 </form> 
			</div>
			<div class="modal-footer">
			  <button class="btn btn-success" onclick="addRow(this.form);" type="button" id="add_strength">Add Set</button>
			  <button class="btn btn-success" id="submit_strength">Submit</button>
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
				<button class="btn btn-primary" data-toggle="modal" name="custom_wod" id="custom_wod" type="button">Add Custom WOD</button>
				</p><p>
				<button class="btn btn-primary" data-toggle="modal" name="strength" id="strength" type="button">Add Strength</button>
				</p><p>
				<button class="btn btn-default" name="post_wod" id="post_wod" type="button">Add Post WOD</button>
				</p><p>
				<button class="btn btn-primary" name="rest" id="rest" type="button">Rest Day</button>
			</p>
		</div>
		
	</div> <!-- END data_container -->
    
    <div id="newRFTWOD" title="Add new WOD" class="new_modals" style="display:none;">
		<div id="newRFTContent">
			<form method="POST" id="add_rft_wod_form" class="add_wod">
                <div id="rft_time_row">
                    Time: <input type="text" name="rft_time" class="rft_time" id="rft_time" placeholder="00:00:00"/> 
                    <p></p>   
                </div> <!-- END OF new_wod -->
             </form> 
		</div>
		<button class="btn btn-success" id="submit_rft">Submit</button>
	</div>
    
    
    <div id="newAMRAPWOD" title="Add new WOD" class="new_modals" style="display:none;">
		<div id="newAMRAPContent">
			<form method="POST" id="add_amrap_wod_form" class="add_wod">
                <div id="add_wod_row">
                <h4>WOD</h4>
                    Reps: <input type="text" name="amrap_reps" class="amrap_reps" id="amrap_reps"/> 
                    <p></p>   
                </div> <!-- END OF new_wod -->
             </form> 
		</div>
		<button class="btn btn-success" id="submit_amrap">Submit</button>
	</div>
    
	<div id="newMixedWOD" title="Add new WOD" class="new_modals" style="display:none;">
		<div id="newPartContent">
		
		</div>
		<button class="btn btn-success" id="submitMixedScore">Submit</button>
	</div>
	
	<div id="listOfCustomWODs" title="Custom WODs" class="new_modals" style="display:none;">
		<div id="newCustomWODContent">
			<div id="list_of_cust_wods">
				<h4>List of Custom WODs</h4>
				<table width="250" rules="cols" id="tbl_wod_list">
				<tr id="wod_list_headers"> 
					<th width="75" height="25">Date</th>
					<th width="100">WOD Type</th>
					<th width="75">Score</th>					
				</tr>
				<tbody class="tbl_body_wod_list" id="tbl_body_wod_list">
				</tbody>
				</table>
				<button class="btn btn-primary" id="but_newCustomWod" onclick="openNewCustomWodModal();">New Custom WOD</button>
			</div>
			<div id="custom_wod_description"> 
			</div>
			<div id="custom_wod_score">
				Score: <input type="text" name="custom_score" class="custom_score" id="custom_score"/>  
				</br><button class="btn btn-success" id="submit_custom_wod_score" onclick="submitScoreForCustomWOD();">Submit</button>
			</div>
		</div>
	</div>
		
		<div id="newModal" class="new_modals" style="display:none;">
			<div id="newModal_content">
			</div>
		</div>
		
		<div id="strengthModal" class="new_modals" style="display:none;">
			<div id="strengthModal_content">
				<form id="str_form">
				<div id="str_main_mvmnt"> Movement: <input type="text" name="str_in_mvmnt" id="str_in_mvmnt"/> </div><p></p>
				<div id="str_date"> Date: <input type="text" name="date" class="datepicker" id="datepicker"/>  </div><p></p>
				<div id="strRepsWeight">
				<p id="1">
					Set <span id="set_num">1</span>: <input type="text" name="strReps_1" id="strReps_1" placeholder="Reps"/> @ <input type="text" name="strWeight_1" id="strWeight_1" placeholder="Weight/Percentage"/> <select name="userStrWeightSelector_1" id="userStrWeightSelector_1"><option value="lbs">lbs</option><option value="kg">kg</option><option value="per">%</option></select>
				</p>
				
				</div>
				</form>
				<div id="str_button_container">
					<button class="btn btn-success" id="strength_add_set">Add Set</button>
					<button class="btn btn-success" id="strength_rem_set">Remove Set</button>
					<button class="btn btn-success" id="strength_submit">Submit</button>
				</div>
			</div>
		</div>
		
		<div id="newCustomWOD" class="new_modals" style="display:none;">
			<div id="newCustomWOD_content">
				<form id="cus_wod_form">
				<div id="cus_wod_type_div"> Type of WOD: <input type="text" name="cus_wod_type" id="cus_wod_type"/> </div><p></p>
				<div id="cus_wod_date"> Date: <input type="text" name="date" class="datepicker" id="c_datepicker"/>  </div><p></p>
				<div>
					<textarea id="cus_wod_description" name="cus_wod_description">
					</textarea>
				</div>
				Score: <input type="text" name="cus_wod_score" id="cus_wod_score" placeholder="You can fill this in later..."/>
				</form>
				<div id="cus_wod_button_container">
					<button class="btn btn-success" id="submit_custom_wod" onclick="submitCustomWod();">Submit</button>
				</div>
			</div>
		</div>
</div> <!-- END div_container -->


<!-- JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<!-- These must be in THIS ORDER! DO NOT FUCK WITH -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script src="dist/js/bootstrap.min.js"></script>


<!-- Required for full calendar -->
<script src='dist/lib/moment.min.js'></script>
<script src="dist/jq_ui/js/jquery-1.10.2.js"></script>
<script src="dist/jq_ui/js/jquery-ui-1.10.4.custom.min.js"></script>

<!-- Required for date picker -->
<script src="dist/js/jquery.plugin.min.js"></script>
<script src="dist/js/jquery.datepick.min.js"></script> 

<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>

<script id="source" language="javascript" type="text/javascript">
$(document).ready(function() {
	getCurrentDate();
	getBoxName();
	getWOD("rx");
	getStrength();
});
$(function() {
    $("#datepicker").datepick(
		{dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true
	});
	$("#c_datepicker").datepick(
		{dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true
	});
});
var unformattedWOD = "";


$("#main_nav_bar li").click(function() {
		var id = jQuery(this).attr("id");
		if(id=="logout" || id=="LOGOUT") {
			alert("LOGGING OUT");
			console.log("logging out...");
		
			$.ajax(
			{ 
				url: "/CRUD/general/cbox_logout.php", //the script to call to get data  
				success: function(response) //on recieve of reply
				{
					console.log("logged out...");
					window.location.replace("http://cboxbeta.com/login_bootstrap.php");
				} 
			});
		}
	});

$("#dd_nav_bar li").click(function() {
	var id = jQuery(this).attr("id");
	if(id=="calendar") {
		window.open("http://cboxbeta.com/challenge.php#uid="+<?echo $_SESSION['MM_UserID'];?>+"");
	}
});

var wod_description ="";
var wod_name = "";
var wod_type = "";
var level_perf = "";
var time = "";
var type_of_wod = "";
var amrap_time = "";
var rft_rounds = "";

$(function(){
	$("button#wod_").click(function() {
		var modalHeight = "";
		var modalWidth = "";
		if(wod_type == "rft" || wod_type == "RFT") {
			type_of_wod = "RFT";
			openNewRFTModal();
			generateRFTModalDropDown();
		} else if (wod_type == "amrap" || wod_type == "AMRAP") {
			type_of_wod = "AMRAP";
			openNewAMRAPModal();
		} else if (wod_type == "mixed" || wod_type == "MIXED") {
			openNewPartModal();
		}
	});
});

$(function(){
	$("button#strength").click(function() {
		openStrengthModal();
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
		getWOD("intermediate")
    });
});

$(function() {
	$("button#lvl_nov").click(function() {
		//add wod
		getWOD("nov")
    });
});

$(function() {
	$("button#submit_rft").click(function() {
		var datastring = $('#add_rft_wod_form').serializeArray();
		var pwod_id = "";
		var strID = "";
		var actualTime = "";
		var time_comp = ""; 
		var rounds_compl = 1;
		
		var name = "";
		$.each(datastring, function(i, field)
		{
			name = field.name;
			console.log("DATA: " + name + " : " + field.value);
			if(name.indexOf("rft_time_") > -1) {
				actualTime += field.value + ":";
				console.log("actual time:" + actualTime);
			}
		});
		var send = true;
		// CRUD/wod/
		if(send==true) {
			$.ajax({
				type: "POST",
				url: "addUserWOD.php",
				data: { 
					"wod_id": today, //build this based on box id and date - I've got this variable in PHP
					"wod_descrip" : "", 
					"level_perf" : level_perf.toUpperCase(),
					"rounds_compl": rft_rounds,
					"time" : time_comp, //this needs to equal nothing - mistype in backend - will remove later
					"pwod_id" : pwod_id, //same as wod_id
					"strength_id" : strID, //same as wod_id
					"actualTime" : actualTime,
					"wod_type" : "RFT"
					}, 
				success: function(msg)
				{
					console.log(msg);
					alert("Entered WoD successfully!");
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
			console.log("DATA: " + field.name + " : " + field.value);
			rounds_compl = field.value;
		});
		//alert("rounds_compl: " + rounds_compl);
		var send = true;
		if(send==true) {
			$.ajax({
				type: "POST",
				url: "addUserWOD.php",
				data: { 
					"wod_id": today, //build this based on box id and date - I think I've got this variable in PHP
					"wod_descrip" : "", 
					"level_perf" : level_perf.toUpperCase(),
					"rounds_compl": rounds_compl,
					"time" : time_comp, //this needs to equal nothing - mistype in backend - will remove later
					"pwod_id" : pwod_id, //same as wod_id
					"strength_id" : strID, //same as wod_id
					"actualTime" : amrap_time,
					"wod_type" : "AMRAP"
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

$("#submitMixedScore").click(function() {
	var mixed_final_score = "";
	
	for(var i = 0; i < score_array.length; i++) {
		mixed_final_score += score_array[i];
	}
	
	console.log(mixed_final_score);
	submitMixedWOD(mixed_final_score);
});

$("#custom_wod").click( function() {
	openCustomWODModal();
	getCustomWODs();
});

$( "#list_of_cust_wods" ).on("click", ".date_link", function() {
		var date = document.getElementById($(this).attr("id")).text;
		var t_custom_id = $(this).attr("id");
		var result = date.replace(/-/g, "");
		getSelectedCustomWOD(t_custom_id);
		return false;
  });

$("#strength_add_set").click(function () {
	var max = 0;
	var elements = $("#strRepsWeight").find("p");
	$.each(elements, function(i, field) { 
		console.log("DATA: " + $(this).attr("id"));
		if($(this).attr("id") > max) {
			max = $(this).attr("id");
		}
	});
	console.log(max);
	addRowToStrength(parseInt(max)+1);
});

$("#strength_rem_set").click(function () {
	var max = 0;
	var elements = $("#strRepsWeight").find("p");
	$.each(elements, function(i, field) { 
		console.log("DATA: " + $(this).attr("id"));
		if($(this).attr("id") > max) {
			max = $(this).attr("id");
		}
	});
	console.log(max);
	$("p#"+max+"").remove();
});


$("#strength_submit").click(function() {
	var strength_string = "";
	var rep_count = true;
	var max = 0;
	var elements = $("#strRepsWeight").find("p");
	$.each(elements, function(i, field) { 
		console.log("DATA: " + $(this).attr("id"));
		if($(this).attr("id") > max) {
			max = $(this).attr("id");
		}
	});

	var strength_data = $("#str_form").serializeArray();
	var selected = $("#strengthModal_content" ).find("select");
	var send = true;
	$.each(selected, function(i ,field) {
		//console.log("DATA 2: " + field.name + " "+field.value);
		strength_data.push({name:field.name, value:field.value});
	});
	$.each(strength_data, function(i ,field) {
		console.log("DATA: " + field.name + " "+field.value);
		if(field.name.indexOf("Weight_") > -1) {
			console.log("Weight at: " + i + " = " + field.value)
			var characterReg = /^[0-9]*$/;
			if(!characterReg.test(field.value)) {
				send = false;
				alert("Doesn't like weight value at " + i);
				$('#'+field.name+'').addClass("big_input_wod_error");
			} else if (field.value.length == 0) {
				$('#'+field.name+'').addClass("big_input_wod_error");
				send = false;
				alert("Doesn't like weight length at: " + i);
			} else {
				//remove error class from previous errors
				$('#'+field.name+'').removeClass("big_input_wod_error");
			}
		} else if(field.name.indexOf("Reps_") > -1) {
			console.log("Reps at: " + i + " = " + field.value)
			var characterReg = /^[0-9]*$/;
			if(!characterReg.test(field.value)) {
				send = false;
				alert("Invalid rep value!");
				$('#'+field.name+'').addClass("big_input_wod_error");
			} else if (field.value.length == 0) {
				$('#'+field.name+'').addClass("big_input_wod_error");
				send = false;
				alert("Invalid length!");
			} else {
				//remove error class from previous errors
				$('#'+field.name+'').removeClass("big_input_wod_error");
			}
		} else if(field.name.indexOf("mvmnt") > -1) {
			var characterReg = /^[a-zA-Z_ ]*$/;
			if(!characterReg.test(field.value)) {
				send = false;
				alert("Doesn't like value at " + i);
				$('#'+field.name+'').addClass("big_input_wod_error");
			} else if (field.value.length == 0) {
				$('#'+field.name+'').addClass("big_input_wod_error");
				send = false;
				alert("Doesn't like length at: " + i);
			} else {
				//remove error class from previous errors
				$('#'+field.name+'').removeClass("big_input_wod_error");
			}
		}
	});
	//strength_data.push({name:"id", value:today});
	if(send==true) {		
		$.ajax({
			type: "POST",
			url: "addUserStrength.php",
			data:  strength_data,
			dataType: "text",
			success: function(msg)
			{
				console.log(msg);
			}
		});
	}
});

 
var runningTotal = "";
var score_array = new Array();
function calculateMixedTotal() {
	var data = $("#newMixedWOD_form").serializeArray();
	var t_val = 0;
	var temp_total = 0;
	var counter = 0;
	var resetTempTotal = false;
	var label = "A";
	var forTime = "";
	$.each(data, function(i, field) {
		console.log("DATA: " + field.name +":"+field.value);
		t_val = parseInt(field.value);
		if(field.name.indexOf("final") > -1) {
			console.log("final score input - not needed here")
		} else {
			if(field.name == "rft_time_0") {
				console.log("ParseInt hours: "+t_val);
				if(t_val > 0) {
					if(t_val > 9) {
						forTime += field.value+":";
					} else {
						forTime += field.value.substring(1,2)+":";
					}
				}
				temp_total = temp_total +(3600*t_val);
				resetTempTotal = false;
				console.log("forTime hours: "+forTime);
			} else if(field.name == "rft_time_1") {
				console.log("ParseInt minutes: "+t_val);
				if(t_val > 0) {
					if(t_val > 9) {
						forTime += field.value;
					} else {
						forTime += field.value.substring(1,2);
					}
				}
				temp_total = temp_total +(60*t_val);
				resetTempTotal = false;
				console.log("forTime minutes: "+forTime);
			} else if(field.name == "rft_time_2") {
				console.log("ParseInt seconds: "+t_val);
				if(t_val > 0) {
					forTime += ":"+field.value;
				}
				temp_total = temp_total + t_val;
				resetTempTotal = true;
				label = "R";
				console.log("forTime seconds: "+forTime);
			} else {
				console.log("Else...: "+t_val);
				temp_total = temp_total + t_val;
				resetTempTotal = true;
				label = "A";
			}
			
			if(resetTempTotal == true) {
				if(label == "R") { 
					runningTotal += forTime+"/";
					console.log("Temp score: "+label+counter+"_" + forTime);
				} else {
					runningTotal += temp_total+"/";
					console.log("Temp score: "+label+counter+"_" + temp_total);
					
				}
				score_array.push(label + counter +"_"+temp_total+",");
				temp_total = 0;
				counter++;
				forTime = "";
			}
		}
		
	});
	runningTotal = runningTotal.substring(0, (runningTotal.length-1));
	console.log("Calculated total: Final_" + runningTotal);
	score_array.push("Final_" + runningTotal);
	$("#mixed_score").val(runningTotal);
	runningTotal = 0;
	
	for(var h = 0; h < score_array.length; h++) {
		console.log(score_array[h]);
	}
	
}

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
	
	// CRUD/general/
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
			console.log(msg);
			loadWODData(msg, level_performed);
		}
	});
}

function getStrength()
{
	$("#temp_strength").empty();
	$.ajax({
		type: "POST",
		url: "getCurrentStrength.php",
		data: { "datastring" : today}, 
		dataType: "json",
		success: function(msg)
		{
			loadStrengthData(msg);
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
	var descrip = "";
	var descripLength = 0;
	
	if(data.length > 0) {
		wodname = data[0].name_of_wod;
		type_of_wod = data[0].type_of_wod;
		if(level_performed == "rx") {
			descrip = data[0].rx_descrip;
		} else if (level_performed == "intermediate") {
			descrip = data[0].inter_descrip;
		} else {
			descrip = data[0].nov_descrip;
		}
		amrap_time = data[0].time;
		rft_rounds = data[0].rounds;
		if(wodname != "-") {
			html_sec1 += "<h3>"+wodname+"</h3>";
		}
		html_sec1 +="<p>Type of WOD: "+type_of_wod+"</p>";
		html_sec1 +="<p>Description: </p>";
		unformattedWOD = descrip;
		$.map( descrip.split(','), function( n ) {
		  descripLength += n.length;
		  var colon = n.indexOf(";");
		  //if(){}
		  //alert("index of colon = "+colon);
		  if(colon > 0) {
			  //alert("index of colon = "+colon +", n.substring(0,colon) "+n.substring(0,colon));
			  html_sec1 +="<p id=\"wod_descrip\">"+n.substring(0,colon)+"</p>";
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
}

function loadStrengthData(data)
{
	var html_sec1 = "";
	var sec1_classID = "cftwod_sec1_data";   
	var movement = "";
	var descrip = "";
	var specialInstructions = "";
	var descripLength = 0;
	
	movement = data[0].movement;
	descrip = data[0].descrip;
	specialInstructions = data[0].special_instructions;
	
	html_sec1 +="<p>Strength: "+movement+"</p>";
	html_sec1 +="<p>Description: "+descrip+"</p>";
	html_sec1 +="<p>Special Instructions: "+specialInstructions+"</p>";
	
	
	//Update html content
	if(descrip.length > 150 || specialInstructions.length > 150){//alert(">150"); 
		$('#strength_div').addClass("long_description");}
	$('#strength_div').html(html_sec1);
}

/*
* Called from the  new_wod_form in the input div
* Creates a new <p> which holds the new rows of input
* Increments rowNum by 1 each time the button is pressed,
* and uses rowNum to id the paragraph and input text fields
*
*/
var rowNum = 0;
function addRow(frm) {
	rowNum++;
	console.log("Strength RowNum ADDED ROW: "+rowNum);
	var row = '<p id="str_rowNum'+rowNum+'"> Set '+(rowNum+1)+' Weight: <input type="text" name="strength_weight_'+rowNum+'" class="strength_weight" id="strength_weight_'+rowNum+'" /> lbs Reps Completed: <input type="text" name="strength_reps_'+rowNum+'" class="strength_" id="strength_reps_'+rowNum+'" />  <input type="button" value="Remove" id="removebutton" onclick="removeRow('+rowNum+');"></p>';
	$('#add_strength_row').append(row);
}

function removeRow(rnum) {
	var counter = 0;
	var rowc  = 0;

	$('#str_rowNum'+rnum).remove();
	
	if(rowNum > 0) {
		rowNum--;
		console.log("Strength RowNum REMOVED ROW: "+rowNum);
	}
}

function generateRFTModalDropDown() {
	console.log("generating dropdown options...");
	var str = "";
	str = "Time: <select size=\"1\" name=\"rft_time_0\" class=\"rft_compl_time\" id=\"rft_time_0\">";
	//for loop to produce 00-24
	for(var i = 0; i < 24; i++) {
		if(i < 10) {
			str += "<option value=\"0"+i+"\">0"+i+"</option>";
		} else {
			str += "<option value=\""+i+"\">"+i+"</option>";
		}	
	}
	str +="</select>";
	str += " : <select size=\"1\" name=\"rft_time_1\" class=\"rft_compl_time\" id=\"rft_time_1\">";
	//for loop to produce 00-59
	for(var i = 0; i < 60; i++) {
		if(i < 10) {
			str += "<option value=\"0"+i+"\">0"+i+"</option>";
		} else {
			str += "<option value=\""+i+"\">"+i+"</option>";
		}	
	}
	str +="</select>";
	str += " : <select size=\"1\" name=\"rft_time_2\" class=\"rft_compl_time\" id=\"rft_time_2\">";
	//for loop to produce 00-59
	for(var i = 0; i < 60; i++) {
		if(i < 10) {
			str += "<option value=\"0"+i+"\">0"+i+"</option>";
		} else {
			str += "<option value=\""+i+"\">"+i+"</option>";
		}	
	}
	str +="</select>";
	console.log("emptying row and adding options");
	$('#rft_time_row').empty();
	$('#rft_time_row').html(str);
}


function generateRFTTimeSelector() {
	console.log("generating RFT dropdown...");
	var str = "";
	str = "Time: <select size=\"1\" name=\"rft_time_0\" class=\"mixed_score_rft\" id=\"rft_time_0\">";
	//for loop to produce 00-24
	for(var i = 0; i < 24; i++) {
		if(i < 10) {
			str += "<option value=\"0"+i+"\">0"+i+"</option>";
		} else {
			str += "<option value=\""+i+"\">"+i+"</option>";
		}	
	}
	str +="</select>";
	str += " : <select size=\"1\" name=\"rft_time_1\" class=\"mixed_score_rft\" id=\"rft_time_1\">";
	//for loop to produce 00-59
	for(var i = 0; i < 60; i++) {
		if(i < 10) {
			str += "<option value=\"0"+i+"\">0"+i+"</option>";
		} else {
			str += "<option value=\""+i+"\">"+i+"</option>";
		}	
	}
	str +="</select>";
	str += " : <select size=\"1\" name=\"rft_time_2\" class=\"mixed_score_rft\" id=\"rft_time_2\">";
	//for loop to produce 00-59
	for(var i = 0; i < 60; i++) {
		if(i < 10) {
			str += "<option value=\"0"+i+"\">0"+i+"</option>";
		} else {
			str += "<option value=\""+i+"\">"+i+"</option>";
		}	
	}
	str +="</select>";
	console.log("emptying row and adding options");
	return str;
}

function openNewAMRAPModal() {
    $( "#newAMRAPWOD" ).dialog({
      height: 400,
	  width: 600,
      modal: true
    });

	$( "#newAMRAPWOD" ).dialog();
	$('.amrap_reps').qtip({ 
		content: 'Total reps accumulated'
	});
}

function openNewRFTModal() {
    $( "#newRFTWOD" ).dialog({
      height: 400,
	  width: 600,
      modal: true
    });

	$( "#newRFTWOD" ).dialog();
}

function openNewPartModal() {
    $( "#newMixedWOD" ).dialog({
      height: 400,
	  width: 600,
      modal: true
    });
	var count = 1;
	var formattedWOD = "<form id=\"newMixedWOD_form\">";
	var tempString = "";
	var tempString2 = "";
	var inputString = 'Final Score: <input type="text" name="final_mixed_score" class="mixed_score" id="mixed_score"/> ';
	var inputArray = new Array();
	if(unformattedWOD.length > 0) {
		tempString = unformattedWOD;
		while(tempString.indexOf(">") > -1) {
			tempString2 = tempString.substring(0, tempString.indexOf(">"));
			if(tempString2.indexOf("Then") > -1) {
				//formattedWOD += "<p>" +idnputString+ "</p>";
			}
			formattedWOD += "<p>"+tempString2+"</p>";
			tempString = tempString.substring(tempString.indexOf(">")+1);
			console.log("T1: " + tempString + "\nT2: " + tempString2 + "\n\nFormatted WOD: " + formattedWOD +"\n\n");
			if(tempString2.indexOf("RFT") > -1 ||
				tempString2.indexOf("rft") > -1 ||
				tempString2.indexOf("round for time") > -1 ||
				tempString2.indexOf("rounds for time") > -1) {
				
					console.log("Add an RFT input to inputArray");
					inputArray.push('<p>RFT Score '+count+': '+generateRFTTimeSelector()+'</p>');
					count++;
					
			} else if (tempString2.indexOf("AMRAP") > -1 ||
				tempString2.indexOf("amrap") > -1 ||
				tempString2.indexOf("minutes of:") > -1) {
				
				console.log("Add an AMRAP input to inputArray");
				inputArray.push('<p>AMRAP Score '+count+': <input type="text" name="amrap_mixed_score" class="mixed_score" id="amrap_mixed_score"/></p> ');
				count++;
			}
		}
		for(var i = 0; i < count-1; i++) {
			console.log("\n"+inputArray[i]+"\n");
			formattedWOD += inputArray[i];
		}	
		//formattedWOD += '<button onclick="calculateMixedTotal()">Calculate Total Score</button>';
		formattedWOD+='<button class="btn btn-success" onclick="calculateMixedTotal();" type="button" id="calc_total_score">Calculate Score</button><p></p>';
		formattedWOD += "<p>" +inputString+ "</p>";
		
		while(count > 0) {
			inputArray.pop();
			count--;
		}
	}
	
	formattedWOD += "</form>";
	console.log("\nFINAL: "+formattedWOD)
	$( "#newMixedWOD" ).dialog();
	$("#newPartContent").html(formattedWOD);
	$('.mixed_score').qtip({ 
		content: 'Enter your time if RFT, or enter your total reps if AMRAP'
	});
}

function openCustomWODModal() {
	$( "#listOfCustomWODs" ).dialog({
      height: 400,
	  width: 700,
      modal: true
    });
	
	$( "#listOfCustomWODs" ).dialog();
	
}

function getCustomWODs() {
	console.log("SEARCHING");
	//$('#custom_wod_description').empty();

	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getUserCustomWODs.php",         
	  dataType: "json",                //data format      
	  success: function(response) //on recieve of reply
	  {
		  console.log("response_wods: " + response);
		  loadWODList(response);
	  },
  	  error: function(error){
    		console.log('error loading wods!' + error);
  		}
	});
}

function submitMixedWOD(score_string) {
	var pwod_id = "";
	var strID = "";
	var actualTime = "00:15:09";
	var time_comp = "";
	var rounds_compl = 0;
	
	$.ajax({
		type: "POST",
		url: "addUserWOD.php",
		data: { 
			"wod_id": today, //build this based on box id and date - I think I've got this variable in PHP
			"wod_descrip" : "", 
			"level_perf" : level_perf.toUpperCase(),
			"rounds_compl": rounds_compl,
			"time" : time_comp, //this needs to equal nothing - mistype in backend - will remove later
			"pwod_id" : pwod_id, //same as wod_id
			"strength_id" : strID, //same as wod_id
			"actualTime" : amrap_time,
			"wod_type" : "MIXED",
			"mixed_score" : score_string
			}, 
		success: function(msg)
		{
			console.log(msg);
			//loadWODData(msg, level_performed);
			if(msg.indexOf("Entered") > -1) {
				$("#newMixedWOD").dialog("close");
				openModal("Success", "Successfully enetered WOD data!", "", 400,300);
			}
		}
	});
	
}


function loadWODList(data_wods) {
	var t_data = data_wods;
	
	var html_sec1 = "";
	var sec1_classID = "wod_sec1_data"; 
	var date_link_id = "date_link_";
	var dow = "";
	var name;
	var score = "";
	var custom_id = "";
	var descrip = "";
	var time = "";
	/*console.log("loadPastWODS PRE-FOR LOOP");
	console.log("DATA: " + data_wods);
	console.log("t_DATA: " + t_data);*/
	for(var i = 0; i < data_wods.length; i++) {
		console.log("data[i].dateofwod: " +data_wods[i].date_of_wod);
		console.log("data[i].type_of_wod: " + data_wods[i].type_of_wod);
		console.log("data[i].description: " + data_wods[i].description);
		console.log("data[i].wod_id: " + data_wods[i].wod_id);
		console.log("data[i].score: " + data_wods[i].score);
		
		tow = data_wods[i].type_of_wod;		
		dow = data_wods[i].date_of_wod;
		
		descrip = data_wods[i].description;
		//main_description = descrip;
		
		score =  data_wods[i].score;
		custom_id = data_wods[i].wod_id;
		date_link_id = "date_link_"+i;
		console.log("date link id: " + date_link_id);
		//header_wod = "Past WODs";

		html_sec1 += "<tr class="+sec1_classID+">";
		//html_sec1 += "<td>"+dow+"</td>";
		html_sec1 += "<td><div class=\"tdDivBox\" id=\"tdDivBox\"><a class=\"date_link\" id=\""+custom_id+"\" href=\"#\">"+dow+"</a></div></td>";
		html_sec1 +="<td class=\"customwod_descrip\">"+tow+"</td>";
		html_sec1 += "<td class=\"customwod_score\">"+score+"</td>";
		html_sec1 += "</tr>";

	}
	//Update html content
	$('.tbl_body_wod_list').empty();
	$('.tbl_body_wod_list').html(html_sec1);
	header_wod = "";
}

var complete_customwod_id = "";
function getSelectedCustomWOD(c_id) {

	console.log("get custom wod data on: " + c_id);
	var t_id = c_id;
	complete_customwod_id = t_id;
	var gender = "";
	var level = "";
	/*
	$('#avg_score').empty();
	$('#wod_actual_description').empty();
	$('#tbl_body_leaderboard').empty();
	$('#chart_div').empty();
	var comma_index = date.indexOf(",");
	var temp_str = "";
	var t_date = "";
	
	if(comma_index > -1) {
		//console.log("I have more than just a date");
		date = data.substring(0, comma_index);
		temp_str = data.substring(comma_index+1);
		//console.log("temp_string: " + temp_str);
		comma_index = temp_str.indexOf(",");
		gender = temp_str.substring(0, comma_index);
		temp_str = temp_str.substring(comma_index);
		//console.log("temp_string: " + temp_str);
		level = temp_str.substring(comma_index);
		
	} else {
		t_id = c_id;
	}
	*/
	
	console.log("date: " + t_id + " gender: "+gender+" level: "+level);
	//pass this data to php file
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getCustomWODStats.php",         
	  data: { "date" : t_id }, //insert arguments here to $_POST
	  dataType: "json",  //data format      
	  success: function(response) //on receive of reply
	  {
		console.log("get wod and score: " + response);
		loadCustomWODData(response);
	  },
  	  error: function(error){
    		console.log('error receiving custom wod!' + error);
  		}
	});

}

function loadCustomWODData(response) {
	$("#custom_wod_description").empty();
	
	var tow = "";
	var dow = "";
	var description = "";
	var score = "";
	var now = "";
	
	now = response[0].name_of_wod;
	tow = response[0].type_of_wod;	
	dow = response[0].date_of_wod;	
	description = response[0].description;
	score = response[0].score;
	
	var html = "";
	
	html += "<p id=\"date_of_custom_wod\"> Date: " + dow +"</p>";
	html += "<p id=\"custom_wod_attributes\"> Name: " + now +"    Type of WOD: "+tow+"</p>";
	html += "<p id=\"description_of_custom_wod\"> Description: </br>" + description +"</p>";
	html += "<p id=\"score_of_custom_wod\"> Old Score: " + score +"</p>";
	
	$("#custom_wod_description").html(html);
}


function openModal(title, info, shouldFormat, height, width) {
    
	var opt_height = (typeof height === "undefined") ? 400 : height;
	var opt_width = (typeof width === "undefined") ? 300 : width;
	console.log(info);
    $( "#newModal" ).dialog({
      height: opt_height,
	  width: opt_width,
      modal: true
    });
	
	$( "#newModal" ).dialog('option', 'title', title);
	$('#newModal_content').html(info);
}

function openStrengthModal() {
	$( "#strengthModal" ).dialog({
      height: 500,
	  width: 700,
      modal: true
    });

	$( "#strengthModal" ).dialog();
	$("#strRepsWeight").empty();
	addRowToStrength(1);
}

function addRowToStrength(maxRowNum) {
	var html = "";
	
	html = '<p id="'+maxRowNum+'">';
	html += 'Set <span id="set_num">'+maxRowNum+'</span>: ';
	html += '<input type="text" name="strReps_'+maxRowNum+'" id="strReps_'+maxRowNum+'" placeholder="Reps"/> @';
	html += '<input type="text" name="strWeight_'+maxRowNum+'" id="strWeight_'+maxRowNum+'" placeholder="Weight/Percentage"/>';
	html += '<select name="userStrWeightSelector_'+maxRowNum+'" id="userStrWeightSelector_'+maxRowNum+'">';
	html += '<option value="lbs">lbs</option><option value="kg">kg</option><option value="per">%</option>';
	html += '</select></p>';
	
	$("#strRepsWeight").append(html);
}

function openNewCustomWodModal() {
	$( "#newCustomWOD" ).dialog({
      height: 500,
	  width: 700,
      modal: true
    });
	
	$( "#newCustomWOD" ).dialog();
}

/*
 * Submit custom WOD for to database. 
 * Requires:
 * 		- Textarea filled in
 *		- Date of wod
 *		- Type of wod
 * Optional:
 * 		- Score
 *
 */
function submitCustomWod() {
	console.log("SUBMITTING CUSTOM WOD");
	var data = $("#cus_wod_form").serializeArray();
	$.each(data, function(i, field) {
		//console.log(field.name + " : "+field.value);
		if(field.name.indexOf("date") > -1) {
			field.value = field.value.replace(/-/g, "");
		}
		if(field.name.indexOf("description") > -1) {
			var patt1 = /\n/;
			var begin_p = "<p>";
			var end_p = "</p>";
			var remove_new_lines = field.value.replace(/\n/g, "</p><p>");
			var result = begin_p.concat(remove_new_lines.concat(end_p));
			console.log(result);
			field.value = result;
		}
	});
	
	$.each(data, function(i, field) {
		console.log(field.name + " : "+field.value);
	});
	
	$.ajax({
		type: "POST",
		url: "/CRUD/wod/insertNewCustomWod.php",
		data: data,
		dataType: "text",
		success: function(response) {
			console.log(response);
		}
	});
	
	
}

function submitScoreForCustomWOD() { 
	var score = $("#custom_score").val();
	console.log("SUBMITTING SCORE OF " + score + " FOR: " + complete_customwod_id);
	var cus_wod_id = complete_customwod_id;
	
	$.ajax({
		type: "POST",
		url: "/CRUD/wod/updateCustomWODScore.php",
		data: { "score" : score, "cus_wod_id": cus_wod_id },
		dataType: "text",
		success: function(response) {
			console.log(response);
		}
	});
	
}

</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  /* TEST SERVER */
  ga('create', 'UA-50665970-1', 'cboxbeta.com');
  /* LIVE SERVER */
  //ga('create', 'UA-50665970-2', 'compete-box.com');
  ga('send', 'pageview');

</script>

</body>
</html>
