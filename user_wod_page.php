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
<title>WOD</title>
	<!-- Bootstrap core CSS and Custom CSS-->
	<link href="dist/css/user_wod_page.css" rel="stylesheet">
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">
 	<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="dist/css/jquery.qtip.css" />
    <link href="dist/css/jquery.datepick.css" rel="stylesheet">
	
	<!-- Calendar stuff -->
	<link href='dist/fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='dist/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
	
	<link href="dist/css/wod_display.css" rel="stylesheet">

</head>

<body>
<div id="container">

	<div id="navbar_main">
	  <ul id="navbar_main_ul"> 
		<li id="home" ><?php echo "<a href='$link' >"; ?>HOME</a></li> 
		<li id="compare"><a href="user_compare_page.php" >COMPARE</a></li> 
		<li id="wod" class="active"><a href="#" >WOD</a></li> 
		<li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li> 
		<li id="account" ><a href="#" >ACCOUNT</a></li> 
		<li id="logout" ><a href="#" >LOGOUT</a></li>
	  </ul> 
	</div>

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
	
	<div id="newCustomWOD" title="Custom WODs" class="new_modals" style="display:none;">
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
			</div>
			<div id="custom_wod_description"> WOD DESCRIPTION WILL EVENTUALLY LOAD HERE
			</div>
			<div id="custom_wod_score">
				Score: <input type="text" name="custom_score" class="custom_score" id="custom_score"/>  
				</br><button class="btn btn-success" id="submit_custom_wod">Submit</button>
			</div>
		</div>
		
		<!--<button class="btn btn-success" id="submit_custom_wod">Submit</button>-->
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

var unformattedWOD = "";


$("#navbar_main_ul li").click(function() {
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
		//console.log(wod_description + " " + wod_name + " "+wod_type+" "+level_perf+" " );
		if(wod_type == "rft" || wod_type == "RFT") {
			type_of_wod = "RFT";
			//generate time dropdown function
			openNewRFTModal();
			generateRFTModalDropDown();
		} else if (wod_type == "amrap" || wod_type == "AMRAP") {
			type_of_wod = "AMRAP";
			openNewAMRAPModal();
		} else if (wod_type == "mixed" || wod_type == "MIXED") {
			openNewPartModal();
		}
		//alert(wod_description + " " + wod_name + " "+wod_type+" "+level_perf+" " );
	});
});

$(function(){
	$("button#strength").click(function() {
		var modalHeight = "";
		var modalWidth = "";
		modalHeight = '50% !important';
		modalWidth = '50% !important';
		$('#strength_modal').css('margin-top', modalHeight);
		$('#strength_modal').css('margin-left', modalWidth);
		$('#strength_modal').modal('show');
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
		//alert("datastring: " + datastring.value);
		$.each(datastring, function(i, field)
		{
			name = field.name;
			console.log("DATA: " + name + " : " + field.value);
			//console.log(name.indexOf("rft_time_"));
			if(name.indexOf("rft_time_") > -1) {
				actualTime += field.value + ":";
				console.log("actual time:" + actualTime);
			}
		});
		var send = true;
		//alert("actualtime: " + actualTime);
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

$(function() {
	$("button#submit_strength").click(function() {
		var datastring = $('#add_strength_form').serializeArray();
		var str_id = "";
		var strID = "";
		var values = "";
		var send = true;
		//alert("datastring: " + datastring.value);
		
		$('.strength_weight').each(function(i, item) {
			var weight =  $('#strength_weight_'+i+'').val();
			alert("Weight at: " + i + " = " + weight)
			var characterReg = /^[0-9]*$/;
			if(!characterReg.test(weight)) {
				send = false;
				alert("Doesn't like weight value at " + i);
				$('#strength_weight_'+i+'').addClass("big_input_wod_error");
			} else if (weight.length == 0) {
				$('#strength_weight_'+i+'').addClass("big_input_wod_error");
				send = false;
				alert("Doesn't like weight length at: " + i);
			}
		});
	
		$('.strength_reps').each(function(i, item) {
			var reps =  $('#strength_reps_'+i+'').val();
			var characterReg = /^[0-9\[\]]*$/;
			if(!characterReg.test(reps)) {
				send = false;
				alert("Doesn't like reps");
				$('#strength_reps_'+i+'').addClass("big_input_wod_error ");
			} else if (reps.length == 0) {
				$('#strength_reps_'+i+'').addClass("big_input_wod_error");
				send = false;
				alert("Doesn't like reps");
			}
		});
		var counter = 0;
		var weight_value = "";
		$.each(datastring, function(i, field)
		{
			if(counter == 0) {
				if(i==0) {
					weight_value += "" + field.value;
					counter++;
				} else {
					weight_value += "-" + field.value;
					counter++;
				}
			} else if(counter == 1) {
				weight_value += "[" + field.value + "]" ;
				counter = 0;
			}
			//alert("DATA: " + field.name + " : " + field.value);
			
		});
		
		//send = false;
		if(send==true) {
			alert("weight_val: " + weight_value);
			$.ajax({
				type: "POST",
				url: "addUserStrength.php",
				data: { "strength_id": today,
					"strength_val" : weight_value
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
	var data = $("#newMixedWOD_form").serializeArray();
	var hours = "";
	var min = "";
	var sec = "";
	var score = 0;
	var tempScore = 0;
	var mixed_score = "";
	var mixed_final_score = "";
	$.each(data, function(i, field) {
		console.log("DATA: " + field.name + " : " + field.value)
		if(field.value.indexOf(":") > -1 && field.value.length < 6) {
			min = parseInt(field.value.substring(0, field.value.indexOf(":")));
			sec = parseInt(field.value.substring(field.value.indexOf(":") + 1));
			console.log(min+":"+sec)
			min = min*60
			score = score + min + sec;
			tempScore = min + sec;
		} else if(field.value.indexOf(":") > -1 && field.value.length < 8 && field.value.length > 5) {
			console.log("HH:MM:SS");
		} else {
			score = score + parseInt(field.value);
			tempScore = parseInt(field.value);
		}
		console.log("i: " + i + "data.length: " + data.length + " tempscore: " + tempScore + " score: " + score);
		//last element, add final score to string
		if(i == (data.length-1)) {
			mixed_final_score = mixed_score + "S" + i +"_" +tempScore +",Final_"+score;
		} else {
			//more elements to go
			mixed_score += "S" + i +"_"+tempScore+",";	
		}
		console.log("Mixed score: "+ mixed_score +" mixed final: " + mixed_final_score);
	});
	console.log(score);
	submitMixedWOD(mixed_final_score);
});

$("#custom_wod").click( function() {
	openCustomWODModal();
	getCustomWODs();
});

$( "#list_of_cust_wods" ).on("click", ".date_link", function() {
		var date = document.getElementById($(this).attr("id")).text;
		var t_custom_id = $(this).attr("id");
		//console.log($(this).attr("id") + ", VALUE: " + document.getElementById($(this).attr("id")).text);
		//parse the date - the value - and put the box_id in front of it
		//then grab all the athletes that completed that wod
		//and put the data into a table in the right side
		var result = date.replace(/-/g, "");
		//console.log("Result: "+result);
		getSelectedCustomWOD(t_custom_id);
		return false;
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
	var formattedWOD = "<form id=\"newMixedWOD_form\">";
	var tempString = "";
	var tempString2 = "";
	var inputString = 'Score: <input type="text" name="mixed_score" class="mixed_score" id="mixed_score"/> ';
	if(unformattedWOD.length > 0) {
		tempString = unformattedWOD;
		while(tempString.indexOf(">") > -1) {
			tempString2 = tempString.substring(0, tempString.indexOf(">"));
			if(tempString2.indexOf("then") > -1) {
				formattedWOD += "<p>" +inputString+ "</p>";
			}
			formattedWOD += "<p>"+tempString2+"</p>";
			tempString = tempString.substring(tempString.indexOf(">")+1);
			console.log("T1: " + tempString + "\nT2: " + tempString2 + "\nForWOD: " + formattedWOD);
		}
		formattedWOD += "<p>" +inputString+ "</p>";
	}
	
	formattedWOD += "</form>";
	console.log(formattedWOD)
	$( "#newMixedWOD" ).dialog();
	$("#newPartContent").html(formattedWOD);
	$('.mixed_score').qtip({ 
		content: 'Enter your time if RFT, or enter your total reps if AMRAP'
	});
}

function openCustomWODModal() {
	$( "#newCustomWOD" ).dialog({
      height: 400,
	  width: 700,
      modal: true
    });
	
	$( "#newCustomWOD" ).dialog();
	
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
 
function getSelectedCustomWOD(c_id) {

	console.log("get custom wod data on: " + c_id);
	var t_id = c_id;
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

</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50665970-1', 'cboxbeta.com');
  ga('send', 'pageview');

</script>

</body>
</html>
