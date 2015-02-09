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



?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Administrator</title>

<!-- Bootstrap core CSS -->
	<link href="dist/css/admin_page.css" rel="stylesheet">
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
 	<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="dist/css/jquery.qtip.css" />
    <link href="dist/css/jquery.datepick.css" rel="stylesheet">
	
	<!-- Calendar stuff -->
	<link href='dist/fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='dist/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link href="dist/css/bryce_main.css" rel="stylesheet">
	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
	
	<link href="dist/css/wod_display.css" rel="stylesheet">

</head>

<body>

<div id="div_container">
    <div id="navbar_main">
        <ul id="navbar_main_ul"> 
            <li id="home" ><a href="Admin_home_page.php" >HOME</a></li> 
            <li id="compare"><a href="user_compare_page.php" >COMPARE</a></li> 
            <li id="wod" ><a href="user_wod_page.php" >WOD</a></li> 
            <li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li>
            <li id="admin" class="active"><a href="#" >ADMIN</a></li>
            <li id="account" ><a href="user_information.php" >ACCOUNT</a></li>
            <li id="logout" ><a href="#" >LOGOUT</a></li>
        </ul> 
    </div>

    <hr class="featurette-divider">
    	<div id="calendar_holder">
            <div id="button_container">
            <p><a onclick="openWODModal()" class="btn btn-primary btn-large" id="new_wod_button" class="buttons_in_but_container">New WOD</a></p>
            <p><a onclick="openStrengthModal()" class="btn btn-primary btn-large" id="new_strength_button" class="buttons_in_but_container">New Strength</a></p>
            <p><a onclick="openPostWODModal()" class="btn btn-primary btn-large" id="new_postwod_button" class="buttons_in_but_container">New Post WOD</a></p>
			<p><a onclick="manageSubscription()" class="btn btn-primary btn-large" id="sub_manager_button" class="buttons_in_but_container">Manage Subscription</a></p>
        </div>
        <div id="calendar"></div>
        <div id="eventContent" title="Event Details">
            <div id="eventInfo"></div>
        </div>
    </div>

	<!--------------------------- TEST -------------------------------------->
	
	<div id="wod_form_modal" title="New WOD" style="display:none;">
		<div id="new_wod_modal_container">
		<form method="POST" id="new_wod_form" class="new_wod">
		<p id="date_type_p">
			Type of WOD: <select id="wod_type_selector" name="wod_type_selector">
				  <option value="RFT">RFT</option>
				  <option value="AMRAP">AMRAP</option>
				  <option value="TABATA">TABATA</option>
				  <option value="GIRLS">GIRLS</option>
				  <option value="HERO">HEROES</option>
				</select>
			Date: <input type="text" name="date" class="datepicker" id="datepicker"/> 
			<div id="specific_to_wod"></div>
			<div>
			<p>
			Buy In: <input type="text" name="buy_in" class="extra_wod_stuff" id="buy_in" placeholder="optional"/>
			Cash Out: <input type="text" name="cash_out" class="extra_wod_stuff" id="cash_out" placeholder="optional"/>
			</p><p>
			Penalty: <input type="text" name="penalty" class="extra_wod_stuff" id="penalty" placeholder="Everytime you drop the bar..."/>
			Special: <input type="text" name="special" class="extra_wod_stuff" id="special" placeholder="Every minute on the minute..."/>
			</p>
			</div>
		</p>
			<div id="new_wod_row" class="new_wod_row">
				Movement: <input type="text" name="movement[]" class="movement" id="movement_0"/> 
				Weight: <input type="text" name="weight[]" class="weight" id="weight_0" placeholder="Guys/Girls"/> 
				Reps/Distance: <input type="text" name="reps[]" class="reps" id="reps_0" placeholder=""/>
				<p class="new_wod_p"></p>
			</div> <!-- END OF new_wod -->
			<input onclick="addRow('','','');" type="button" value="Add row" id="addRowBut"/>
            <input onclick="addRow('','','','add');" type="button" value="Add another part" id="addPartBut"/>
			<input onclick="submitWOD(this.form);" type="button" value="Submit WOD" id="submitWodBut"/>
		 </form>
         
		<p><a onclick="openScaledWODModal()" class="btn btn-primary btn-small">Set Scaled Movements</a></p>
		<p><a onclick="addToCustomWOD()" class="btn btn-primary btn-small">Add as Custom WOD</a></p>
		
		</div>
    </div>
	
	
	<div id="scaled_wod_form_modal" title="Scale the WOD" style="display:none;">
		<div id="scaled_wod_modal_container">
			<form method="POST" id="inter_new_wod_form" class="new_wod">
                <h4>Intermediate</h4>
				<div id="inter_new_wod_row">
                    Movement: <input type="text" name="inter_movement[]" class="inter_movement" id="inter_movement_0"/> 
                    Weight: <input type="text" name="inter_weight[]" class="inter_weight" id="inter_weight_0" placeholder="Guys/Girls"/> 
                    Reps/Distance: <input type="text" name="inter_reps[]" class="inter_reps" id="inter_reps_0"/>
                    <p></p>
                </div> <!-- END OF new_wod -->
             </form> 
             <hr class="featurette-divider"> 
             <form method="POST" id="nov_new_wod_form" class="new_wod">
				<h4>Novice</h4>
                <div id="novice_new_wod_row">
                    Movement: <input type="text" name="nov_movement[]" class="nov_movement" id="nov_movement_0"/> 
                    Weight: <input type="text" name="nov_weight[]" class="nov_weight" id="nov_weight_0" placeholder="Guys/Girls"/> 
                    Reps/Distance: <input type="text" name="nov_reps[]" class="nov_reps" id="nov_reps_0"/>
                    <p></p> 
                </div> <!-- END OF new_wod -->
             </form>
		<button class="btn btn-success" id="load">Load RX Data</button>
        <button class="btn btn-success" id="submit">Set Scaled Movements</button>
		</div>
	</div>
	
	<div id="strength_form_modal" title="New WOD" style="display:none;">
		<div id="new_strength">
        <form method="POST" id="new_strength_form" class="new_str">
            <p id="str_type_p">
                Date: <input type="text" name="date" class="datepicker" id="str_datepicker"/> 
                Movement: <input type="text" name="strength_mov" class="strength_mov" id="strength_mov_0"/> 
                Rep Scheme: <input type="text" name="str_reps" class="strength_reps" id="strength_reps" placeholder="5x5, 5-4-3-2-1, etc etc" />
                Weight Instructions: <select id="str_instruction_selector" name="str_instruction_selector">
                  <option value="PER">Percentage of 1RM</option>
                  <option value="AHAP">As Heavy as Possible</option>
                  <option value="ILES">Increase Load Each set</option>
                  <option value="LSS">Load Stays Same</option>
                </select>
                <div id="strength_instructions"></div>
                <div id="specific_to_strength">
                    Special Instructions: <input type="text" name="special_str" class="extra_str_stuff" id="strength_special_instructions" placeholder="..."/>
                </div>
            </p>
            <input onclick="submitStrength(this.form);" type="button" value="Submit Strength" id="submitStrengthButton" />
            </form>
    </div> <!-- END OF new_wod_container -->
    </div>
	
	<!--------------_________________________---------------->
	
	<div id="dialog-modal">
		<div id="workoutcontent"></div>
	  <p></p>
	</div>
    
    <div id="newPartModal" title="Add new Section" style="display:none;">
		<div id="newPartContent">
            <form id="submitPartToText_form">
            Rest for: <input type="text" name="rest_m" class="extra_wod_stuff" id="rest_m" placeholder="2 minutes..."/>
            Type of WOD: <select id="wod_type_selector_modal" name="wod_type_selector_modal">
            <option value="NONE"> - </option>
            <option value="RFT">RFT</option>
            <option value="AMRAP">AMRAP</option>
            <option value="TABATA">TABATA</option>
            </select>
            <div id="specific_to_wod_modal"></div>
            <div><p>
            Penalty: <input type="text" name="penalty_m" class="extra_wod_stuff" id="penalty_m" placeholder="Everytime you drop the bar..."/>
            Special: <input type="text" name="special_m" class="extra_wod_stuff" id="special_m" placeholder="Every minute on the minute..."/>
            </p></div>
            </form>
            <button class="btn btn-success" id="submitPartToText">Submit</button>
		</div>
	</div>
	
</div><!-- END OF DIV CONTAINER -->

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
<script src='dist/fullcalendar/fullcalendar.min.js'></script>

<!-- Required for date picker -->
<script src="dist/js/jquery.plugin.min.js"></script>
<script src="dist/js/jquery.datepick.min.js"></script> 

<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>

<script id="source" language="javascript" type="text/javascript">

var movementArray = new Array();
var weightArray = new Array();
var repArray = new Array();

var data_one;
var data_two;
var data_three;
var hasLoadedScaled = false;
var rowNum = 0;
var extra_rowNum = 0;
var max_count = 0;

var previousVal = "";
var currentVal = "";

var newWODType = new Array(); //used for new part(s)

var number_of_rounds = 0;
var girl_amrap_time = "";

/*
* Once the document is  loaded, grab the WoD information
*
*/
$(document).ready(function() {
	renderCalendar();
});

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
				window.location.replace("http://compete-box.com/login_bootstrap.php");
			} 
		});
	} else if (id=="compare" || id=="COMPARE") {
			window.location.replace("http://compete-box.com/user_compare_page.php");
		}
});	

/*
* Set up the datepicker object
*
*/
$(function() {
	//event.preventDefault();
    $("#datepicker").datepick(
		{dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true
	});
	$("#str_datepicker").datepick(
		{dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true
	});
 });
  
/*
* Catches when the user presses submit on the 
* scaled modal (known as the example_modal - needs to be renamed)
*
* Serializes the forms into separate arrays, and then
* concatenate to be processed later
*/
$(function() {
	//twitter bootstrap script
	$("button#submit").click(function(){
		
		//need to error check these values
		data_one = $('#inter_new_wod_form').serializeArray();
		data_two = $('#nov_new_wod_form').serializeArray();
		data_three = data_one.concat(data_two);
		
		$.each(data_three, function(i, field) {
		//alert("DATA: " +field.name + ":" + field.value + " ");
		});
		$('#workoutcontent').empty();
		openModal("Scaled data","Scaled movements have been set! <p><p><a onclick=\"closeScaledModal()\" class=\"btn btn-primary btn-small\">Ok!</a></p></p>");
		hasLoadedScaled = true;
	});
});

/*
* Catches typos in the forms
* 
* Movement inputs: only alphabetical characters
* Weight inputs: only numerical
* Rep inputs: only numerical
*
* Should eventually prevent user from submitting
*/
$( this ).focusout(function (event) {
	var id = event.target.id;
	var value = "";
	var mvmReg = /^[a-zA-Z\s]*$/;
	var weightReg = /^[0-9\/]*$/;
	var repsReg = /^[a-zA-Z0-9\s]*$/;
	/* RX Form */
	if ( id.indexOf("movement_") >= 0 )
	{
		value = $("#" + id).val();
		if(id == "movement_0") {
			currentVal = value
		}
		if(!mvmReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
    } else if( id.indexOf("weight_") >= 0 ) {
		value = $("#" + id).val();
		if(!weightReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
	} else if( id.indexOf("reps_") >= 0 ) {
		value = $("#" + id).val();
		console.log("reps ID: "+id+", value: "+ value);
		if(!repsReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
	}
	
	/* Intermediate Form */
	if ( id.indexOf("inter_movement_") >= 0 )
	{
		value = $("#" + id).val();
		if(!mvmReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
    } else if( id.indexOf("inter_weight_") >= 0 ) {
		value = $("#" + id).val();
		if(!weightReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
	} else if( id.indexOf("inter_reps_") >= 0 ) {
		value = $("#" + id).val();
		//alert("ID: "+id+", value: "+ value);
		if(!repsReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
	}
	
	/* Novice Form */
	if ( id.indexOf("nov_movement_") >= 0 )
	{
		value = $("#" + id).val();
		if(!mvmReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
    } else if( id.indexOf("nov_weight_") >= 0 ) {
		value = $("#" + id).val();
		if(!weightReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
	} else if( id.indexOf("nov_reps_") >= 0 ) {
		value = $("#" + id).val();
		//alert("ID: "+id+", value: "+ value);
		if(!repsReg.test(value))
		{
			$("#"+id).addClass("big_input_wod_error");
		} else {
			$("#"+id).removeClass("big_input_wod_error");
		}
	}
	
});

/*
* Load RX button clicked in example_modal
*
* Call the function addScaledRows
*/
$(function() {
	$("button#load").click(function() {
    	//alert("CLICKEDDDDD!!!!!");
		//add rows equal to RX
		addScaledRows();
		//load the data from RX into fresh rows
		loadRxIntoScale();
    });
});

/*
* WoD type Dropdown changes values
*
* Change the specifics of each type of wod 
* according to the dropdown selection
*
* Need to update: 
*	Finish girls
*	Add heroes
*   change girls' values
*/
$( "#wod_type_selector" ).change(function() {
    var str = "";
    $( "#wod_type_selector option:selected" ).each(function() 
	{
		for(var i = rowNum; i > 0; i--) {
			removeRow(i);
		}
		$('#specific_to_wod').empty();
		if($( this ).text() == "RFT"){
			$('#addRowBut').removeAttr('disabled');
			str = "Rounds: <input type=\"text\" name=\"num_of_rounds\" class=\"num_of_rounds\" id=\"num_of_rounds\" placeholder=\"5, 21-15-9, etc\"/>";
		} else if($( this ).text() == "AMRAP") {
			$('#addRowBut').removeAttr('disabled');
			str = "Time: <select size=\"1\" name=\"amrap_0\" class=\"num_of_rounds\" id=\"amrap_0\">";
			//for loop to produce 00-24
			for(var i = 0; i < 24; i++) {
				if(i < 10) {
					str += "<option value=\"0"+i+"\">0"+i+"</option>";
				} else {
					str += "<option value=\""+i+"\">"+i+"</option>";
				}	
			}
			str +="</select>";
			str += " : <select size=\"1\" name=\"amrap_1\" class=\"num_of_rounds\" id=\"amrap_1\">";
			//for loop to produce 00-59
			for(var i = 0; i < 60; i++) {
				if(i < 10) {
					str += "<option value=\"0"+i+"\">0"+i+"</option>";
				} else {
					str += "<option value=\""+i+"\">"+i+"</option>";
				}	
			}
			str +="</select>";
			str += " : <select size=\"1\" name=\"amrap_2\" class=\"num_of_rounds\" id=\"amrap_2\">";
			//for loop to produce 00-59
			for(var i = 0; i < 60; i++) {
				if(i < 10) {
					str += "<option value=\"0"+i+"\">0"+i+"</option>";
				} else {
					str += "<option value=\""+i+"\">"+i+"</option>";
				}	
			}
			str +="</select>";
		} else if($( this ).text() == "TABATA") {
			$('#addRowBut').removeAttr('disabled');
			str = "Number of Intervals: <input type=\"text\" name=\"num_of_rounds\" class=\"num_of_rounds\" id=\"num_of_rounds\"/>";
		} else if($( this ).text() == "GIRLS") {
			$('#addRowBut').attr('disabled','disabled');
			str = "Girls: <select id=\"girl_selector\" name=\"girl_selector\">";
			str +="<option value=\"grl_01\">Angie</option>";
			str += "<option value=\"grl_02\">Barbara</option>";
			str +="<option value=\"grl_03\">Chelsea</option>";
			str +="<option value=\"grl_04\">Cindy</option>";
			str +="<option value=\"grl_05\">Diane</option>";
			str +="<option value=\"grl_06\">Elizabeth</option>";
			str +="<option value=\"grl_07\">Fran</option>";
			str +="<option value=\"grl_08\">Grace</option>";
			str +="<option value=\"grl_09\">Helen</option>";
			str +="<option value=\"grl_10\">Isabel</option>";
			str +="<option value=\"grl_11\">Jackie</option>";
			str +="<option value=\"grl_12\">Karen</option>";
			str +="<option value=\"grl_13\">Linda</option>";
			str +="<option value=\"grl_14\">Mary</option>";
			str +="<option value=\"grl_15\">Nancy</option>";
			str +="<option value=\"grl_16\">Annie</option>";
			str +="<option value=\"grl_17\">Eva</option>";
			str +="<option value=\"grl_18\">Kelly</option>";
			str +="<option value=\"grl_19\">Lynne</option>";
			str +="<option value=\"grl_20\">Nicole</option>";
			str +="<option value=\"grl_21\">Amanda</option>";
			str +="</select>";
	
		}else if($( this ).text() == "HEROES") {
			$('#addRowBut').removeAttr('disabled');
			str = "";
		}
		//Update html content
		$('#specific_to_wod').append(str);
    });
  }).trigger( "change" );
  
$( "#str_instruction_selector" ).change(function() {
    var str = "";
    $( "#str_instruction_selector option:selected" ).each(function() 
	{
		$('#strength_instructions').empty();
		if($( this ).val() == "PER"){
		str = "Percentage of 1RM: <input type=\"text\" name=\"weight_instruction\" class=\"weight_instruction\" id=\"percent_of_onerm\"/>";
		} else if($( this ).val() == "AHAP") {
			str = "As heavy as possible <input type=\"hidden\" name=\"weight_instruction\" class=\"weight_instruction\" id=\"ahap\"/>";
		} else if($( this ).val() == "ILES") {
			str = "Increase load by: <input type=\"text\" name=\"weight_instruction\" class=\"weight_instruction\" id=\"iles\"/>lbs each set (optional instruction)";
		} else if($( this ).val() == "LSS") {
			str = "Load Stays the same <input type=\"hidden\" name=\"weight_instruction\" class=\"weight_instruction\" id=\"lss\"/>";
		}
		//Update html content
		$('#strength_instructions').append(str);
    });
  }).trigger( "change" );


$( "#specific_to_wod" ).on("change", "#girl_selector", function() {
	console.log($(this).val());
	var girl_data = new Array();
	var movement = "";
	var weight = "";
	var reps = "";
	var temp = "";
	var index = 0;
	var prev_index = 0;
	$('#movement_0').val('');
	$('#weight_0').val('');
	$('#reps_0').val('');
	$('.new_wod_p').empty();
	rowNum = 0;
	//function to get the instructions and movements/weight/reps
	//return an array with aforementioned values
	girl_data = theGirls($(this).val());
	$('#special').val(girl_data[0]);
	for(var i = 1; i < girl_data.length; i++) {
		console.log(girl_data[i]);	
		//add row function
		index = girl_data[i].indexOf(",");
		movement = girl_data[i].substring(0, index);
		temp = girl_data[i].substring(index + 1);
		
		//console.log("movement: " + movement + ", index: "+index+", temp: " + temp);
		
		index = temp.indexOf(",");
		weight = temp.substring(0, index);
		temp = temp.substring(index + 1);
		//console.log("weight: " + weight + ", index: "+index+", temp: " + temp);
		reps = temp;
		console.log("movement: " + movement + "  weight: "+weight+"  reps: "+reps+"  temp: " + temp);
		if(i == 1) {
			$('#movement_0').val(movement.trim());
			$('#weight_0').val(weight.trim());
			$('#reps_0').val(reps.trim());
		} else {
			addRow(movement.trim(), weight.trim(), reps.trim());
		}
		
	}
});

$( "#newPartContent" ).on("change", "#wod_type_selector_modal", function() {
	console.log("MODAL WOD SELECTOR CHANGED");
	$('#specific_to_wod_modal').empty();
	var str = "DEFAULT";
	console.log($(this).val());
	if($( this ).val() == "RFT"){
		str = "Rounds: <input type=\"text\" name=\"num_of_rounds\" class=\"num_of_rounds\" id=\"num_of_rounds\" placeholder=\"5, 21-15-9, etc\"/>";
	} else if($( this ).val() == "AMRAP") {
		str = "Time: <select size=\"1\" name=\"amrap_0\" class=\"num_of_rounds\" id=\"amrap_0\">";
		//for loop to produce 00-24
		for(var i = 0; i < 24; i++) {
			if(i < 10) {
				str += "<option value=\"0"+i+"\">0"+i+"</option>";
			} else {
				str += "<option value=\""+i+"\">"+i+"</option>";
			}	
		}
		str +="</select>";
		str += " : <select size=\"1\" name=\"amrap_1\" class=\"num_of_rounds\" id=\"amrap_1\">";
		//for loop to produce 00-59
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				str += "<option value=\"0"+i+"\">0"+i+"</option>";
			} else {
				str += "<option value=\""+i+"\">"+i+"</option>";
			}	
		}
		str +="</select>";
		str += " : <select size=\"1\" name=\"amrap_2\" class=\"num_of_rounds\" id=\"amrap_2\">";
		//for loop to produce 00-59
		for(var i = 0; i < 60; i++) {
			if(i < 10) {
				str += "<option value=\"0"+i+"\">0"+i+"</option>";
			} else {
				str += "<option value=\""+i+"\">"+i+"</option>";
			}	
		}
		str +="</select>";
	} else if($( this ).val() == "TABATA") {
		str = "Number of Intervals: <input type=\"text\" name=\"num_of_rounds\" class=\"num_of_rounds\" id=\"num_of_rounds\"/>";
	}
	console.log("STR: " + str);
	$('#specific_to_wod_modal').append(str);
});

$("#submitPartToText").click(function() {
	var tempArray = $('#submitPartToText_form').serializeArray();
	var tempString = "";
	
	var amrap_time = "";
	var rest_period = "";
	var type = "";
	var specific = "";
	var penalty = "";
	var special = "";
	
	$.each(tempArray, function(i, field){
		console.log("DATA TO STRING: " +field.name + ":" + field.value + " ");
		var name = field.name;
		if(name.indexOf("rest_m") > -1) {
			rest_period = field.value + " rest";
		} else if(name.indexOf("wod_type_") > -1) {
			type = field.value;
		} else if(name.indexOf("penalty_m") > -1) {
			penalty = field.value;
		} else if(name.indexOf("special_m") > -1) {
			special = field.value;
		} else {
			if(name.indexOf("amrap_0") > -1 ) {
				//alert("DATA: " +field.name + ":" + field.value + " ");
				specific += field.value;
			} else if(name.indexOf("amrap_1") > -1 || name.indexOf("amrap_2") > -1 ) {
				//alert("DATA: " +field.name + ":" + field.value + " ");
				specific += ":"+field.value;
			} else {
				specific += field.value + " ";
			}
		}
	});	
	tempString = rest_period + " " + penalty + " " + special + " " +type+ " " + specific;
	console.log("Post build string: " + tempString + ", extra row num: " + extra_rowNum)
	
	for(var i = 0; i< extra_rowNum; i++) {
		var elm = document.getElementById("extrapart_"+i+"");
		console.log(elm);
		if(elm === null) {
			console.log("NULL")
		} else {
			if($("#extrapart_"+i+"").val().length < 1)
				$("#extrapart_"+i+"").val(tempString);
			else console.log("false");
		} 
	}
	$("#newPartModal").dialog("close");
	
});	


/***************************** Manage Subscription Page Change *****************************/

function manageSubscription() {
	window.location.replace("http://compete-box.com/billingTest.php");
}

/******************************** LOAD TABLES **************************************/


/************************************** DYNAMIC CONTENT **********************************/

/*
* Called from the  new_wod_form in the input div
* Creates a new <p> which holds the new rows of input
* Increments rowNum by 1 each time the button is pressed,
* and uses rowNum to id the paragraph and input text fields
*
*/
function addRow() {
	rowNum++;
	console.log("RowNum ADDED ROW: "+rowNum);
	var row = '<p id="rowNum'+rowNum+'">Movement: <input type="text" name="movement[]" class="movement" id="movement_'+rowNum+'"> Weight: <input type="text" name="weight[]" class="weight" id="weight_'+rowNum+'"> Reps: <input type="text" name="reps[]" class="reps" id="reps_'+rowNum+'"> <input type="button" value="Remove" id="removebutton" onclick="removeRow('+rowNum+');"></p>';
	$('#new_wod_row').append(row);
}

/*
* Called from the  new_wod_form in the input div
* Creates a new <p> which holds the new rows of input
* Increments rowNum by 1 each time the button is pressed,
* and uses rowNum to id the paragraph and input text fields
*
*/
function addRow(movement, weight, reps, part) {
	
	var row = "";
	var toReset = 0;
	if(typeof part === "undefined" ) {
		rowNum++;
		console.log("RowNum ADDED ROW: "+rowNum);
		row = '<p id="rowNum'+rowNum+'">Movement: <input type="text" name="movement[]" class="movement" id="movement_9999" value="'+movement+'"> Weight: <input type="text" name="weight[]" class="weight" id="weight_9999" value="'+weight+'"> Reps: <input type="text" name="reps[]" class="reps" id="reps_9999" value="'+reps+'"> <input type="button" value="Remove" id="removebutton" onclick="removeRow('+rowNum+');"></p>';
	} else if(part.length > 0) {
		console.log("Adding part...");
		toReset = 1;
		console.log("extra_RowNum ADDED PART: "+extra_rowNum);
		//open modal with options here...
		row = '<p id="extrarowNum'+extra_rowNum+'">Extra part '+extra_rowNum+': <input type="text" name="extrapart[]" class="extrapart" id="extrapart_'+extra_rowNum+'" value=""><input type="button" value="Remove" id="removebutton" onclick="removeRow('+extra_rowNum+', 1);"></p>';	
		extra_rowNum++;
		//open modal
		//first set the html to go inside modal...
		var html = '<form id="submitPartToText">';
		html+= 'Type of WOD: <select id="wod_type_selector_modal" name="wod_type_selector_modal">';
		html+='<option value="NONE"> - </option>';
		html+='<option value="RFT">RFT</option>';
		html+='<option value="AMRAP">AMRAP</option>';
		html+='<option value="TABATA">TABATA</option>';
		html+='</select>';
		html+='<div id="specific_to_wod_modal"></div>';
		html+='<div><p>';
		html+='Penalty: <input type="text" name="penalty_m" class="extra_wod_stuff" id="penalty_m" placeholder="Everytime you drop the bar..."/>';
		html+='Special: <input type="text" name="special_m" class="extra_wod_stuff" id="special_m" placeholder="Every minute on the minute..."/>';
		html+='</p></div>';
		html='<input type="button" value="Submit" id="submitPartToText" onclick="partToText();"></form>';
		console.log(html);
		openNewPartModal();
	} 
	console.log("ROW: " + row);
	$('.new_wod_p').append(row);
	resetIDs(toReset);
}

/*
* Called from the  new_wod_form in the input div
* Removes a <p> which holds the rows of input. 
* Decrements rowNum by 1 each time the button is pressed
* 
* Cannot remove the first line of inputs
*
*/
function removeRow(rnum, extra) {
	var movement =  "";
	var weight =  "";
	var reps =  "";
	var counter = 0;
	var counter_for_extra = 0;
	var rowc  = 0;
	movement =  $('#movement_'+rnum+'').val();
	weight =  $('#weight_'+rnum+'').val();
	reps =  $('#reps_'+rnum+'').val();
	//metric = $('#rep_type_selector_'+rowNum+'').val();
	
	var extra = (typeof extra === "undefined") ? 0 : extra;
	
	if(extra > 0) {
		$('#extrarowNum'+rnum).remove();
		$('#inter_extrarowNum'+rnum).remove();
		$('#nov_extrarowNum'+rnum).remove();
	} else {
		$('#rowNum'+rnum).remove();
		$('#inter_rowNum'+rnum).remove();
		$('#nov_rowNum'+rnum).remove();
	}
	//alert("Exercise to remove: " + movementArray[rnum] + " " +weightArray[rnum]+ " " +repArray[rnum]);
	//reset all the ids of existing rows
	console.log("Extra: " + extra);
	
	//function: reset ID's
	resetIDs(extra);
	
	if(rowNum > 0 && extra < 1) {
		console.log("RowNum REMOVED ROW: "+rowNum);
	} else if(extra_rowNum >= 0 && extra > 0) {
		console.log("extra_RowNum REMOVED ROW: "+extra_rowNum);
	}
}

function resetIDs(extra) {
	var movement =  "";
	var weight =  "";
	var reps =  "";
	var counter = 0;
	var counter_for_extra = 0;
	var rowc  = 0;
	var myDiv = document.getElementById( "new_wod_row" ); 
	var inputArr = myDiv.getElementsByTagName( "input" ); 
	
	var hasChanged = false;
	
	if(extra > 0) {
		console.log("Resetting IDs for extrarows");
	} else {
		console.log("Resetting IDs for exercises");
	}
	console.log("ARRAYLENGTH: " + inputArr.length);
	
	for (var i = 0; i < inputArr.length; i++) 
	{ 
		var tempString = inputArr[i].getAttribute( 'id' );
		if(tempString == "removebutton") {
			console.log("button");
			console.log("i: "+i+", rowc: "+rowc);
		} else if(tempString.indexOf("extrapart") > -1) {
			console.log("extra part");
			console.log("i: "+i+", rowc: "+rowc);
			if(extra > 0) {				
				//extract rowNum from the ID
				var t_index = tempString.indexOf("_"); 
				var t_id = tempString.substring(t_index+1, tempString.length);
				console.log("tempString: "+tempString+", ID: "+t_id + ", INDEX: "+t_index +", counter: " + counter_for_extra); 
				document.getElementById("extrapart_"+t_id+"").id = "extrapart_"+(counter_for_extra);
				counter_for_extra++;
				//extra_rowNum = counter_for_extra + 1;
			}
		} else if(extra < 1){
			if(rowc%3==0) {
				hasChanged = false;
				counter++;
			}
			var t_index = tempString.indexOf("_"); 
			var t_id = tempString.substring(t_index+1, tempString.length);
			console.log("tempString: "+tempString+", ID: "+t_id + ", INDEX: "+t_index +", rowCount variable: "+rowc+" counter: " + counter); 
			if(document.getElementById("movement_"+t_id+"") && hasChanged == false)
			{
				document.getElementById("movement_"+t_id+"").id = "movement_"+(counter-1);
				document.getElementById("weight_"+t_id+"").id = "weight_"+(counter-1);
				document.getElementById("reps_"+t_id+"").id = "reps_"+(counter-1);
				hasChanged = true;
				//rowNum = counter;
			}
			rowc++;
		}	
	}
}


/*
* Called from the  example_modal when user presses Load RX Data.
* Creates a new <p> which holds the new rows of input, uses for 
* loop to populate the forms based on RX data. Loads RX movements
* and weight automatically in form for better user experience
* 
*/
var ep_positon = 0;
var ep_num = 0;
var ep_id = "0,";
var ep_array = new Array();
function addScaledRows()
{
	if(ep_array.length > 0) {
		while (ep_array.length > 0) {
			ep_array.pop();
		}
	}

	var movement =  "";
	var weight =  "";
	var reps =  "";
	var extraInstruction = "";
	var countForWodType = 7; //default for RFT, should = 9 if AMRAP
	var rowCount = 0;
	var extraRowCount = 0;
	var totalRowCount = 0;
	var newMvmArray = $("#new_wod_form").serializeArray();
	$.each(newMvmArray, function(i, field){
		if(field.name === "wod_type_selector") {
			if(field.value == "AMRAP") {
				countForWodType = 9;
			}
		}
		if(i >= countForWodType && i%3==1 && field.name !== "extrapart[]") {
			x = i;
			console.log("testing add scaled: " + field.name);
			if(typeof movement === 'undefined') {
				console.log("UNDEFINED!!!! X = "+x+", RowCount: "+rowCount+", Movement: " +movement + ", Weight: " + weight + ", Reps: " + reps);
			} else {
				movement =  $('#movement_'+rowCount+'').val();
				weight =  $('#weight_'+rowCount+'').val();
				reps =  $('#reps_'+rowCount+'').val();
				movementArray.push(movement);
				weightArray.push(weight);
				repArray.push(reps);
				rowCount++;
			}
			x = i + 3
			console.log("rowCount: " + rowCount)
		} else if(field.name === "extrapart[]"){
			extraInstruction = "E98734" + $('#extrapart_'+extraRowCount+'').val();
			console.log("Extra Part: " + extraInstruction);
			movementArray.push(extraInstruction);
			weightArray.push(" ");
			repArray.push(" ");
			extraRowCount++;
			ep_num = extraRowCount;
			ep_positon = rowCount + extraRowCount;
			/*ep_id.substring(0) = ep_num;
			ep_id.substring(ep_id.indexOf(",")+1) = ep_id.concat(ep_position;
			*/
			ep_array.push(ep_positon);
		}
		totalRowCount++;
  	});
	
	ep_array.push(ep_num);
	ep_id = ep_array[ep_array.length-1] + ",";
	console.log("EP ID: " + ep_id);
	for(var t = 0; t < ep_array.length; t++) {
		if(typeof ep_array[t+1] !== "undefined") {
			ep_id = ep_id.concat(ep_array[t] + ",");
		}
		console.log("EP ID for loop: " + ep_id );
	}
	
	
	for(var k = 0; k < movementArray.length; k++)
	{
		if(movementArray[k].indexOf("E98734") < 0 ) {
			console.log("Row to be inserted: " + movementArray[k] + " "+weightArray[k]+" "+ repArray[k]);
		} else {
			console.log("New Part: " + movementArray[k]);
		}
	}
	if(movementArray.length > 0) 
	{
		var row = "";
		var inter_extra_row_inc = 0;
		var nov_extra_row_inc = 0;
		var substring = 0;
		//first intermediate
		$('#inter_new_wod_row').empty();
		for(var i = 0; i < movementArray.length; i++) 
		{	
			substring = movementArray[i].indexOf("E98734");
			if(substring < 0 ) {
				row = '<p id="inter_rowNum'+i+'">Movement: <input type="text" name="inter_movement[]" class="inter_movement" id="inter_movement_'+i+'" value="'+ movementArray[i] +'"> Weight: <input type="text" name="inter_weight[]" class="inter_weight" id="inter_weight_'+i+'" value="'+weightArray[i]+'"> Reps: <input type="text" name="inter_reps[]" class="inter_reps" id="inter_reps_'+i+'" value="'+repArray[i]+'"></p>';
			} else {
				console.log("Substring: " + substring);
				row = '<p id="inter_extrarowNum'+inter_extra_row_inc+'">Extra part: <input type="text" name="inter_extrapart[]" class="inter_extrapart" id="inter_extrapart_'+inter_extra_row_inc+'" value="'+movementArray[i].substring(6)+'"></p>';	
				inter_extra_row_inc++;
			}
			$('#inter_new_wod_row').append(row);
		}
		$('#novice_new_wod_row').empty();
		for(var j = 0; j < movementArray.length; j++) {
			substring = movementArray[j].indexOf("E98734");
			if(substring < 0 ) {
				row = '<p id="nov_rowNum'+j+'">Movement: <input type="text" name="nov_movement[]" class="nov_movement" id="nov_movement_'+j+'" value="'+ movementArray[j] +'"> Weight: <input type="text" name="nov_weight[]" class="nov_weight" id="nov_weight_'+j+'" value="'+weightArray[j]+'"> Reps: <input type="text" name="nov_reps[]" class="nov_reps" id="nov_reps_'+j+'" value="'+repArray[j]+'"></p>';
			} else {
				console.log("Substring: " + substring);
				row = '<p id="nov_extrarowNum'+nov_extra_row_inc+'">Extra part: <input type="text" name="nov_extrapart[]" class="nov_extrapart" id="nov_extrapart_'+nov_extra_row_inc+'" value="'+movementArray[j].substring(6)+'"></p>';	
				nov_extra_row_inc++;
			}
			$('#novice_new_wod_row').append(row);
		}
	}
	//empty the arrays
	while (movementArray.length > 0) {
		movementArray.pop();
		weightArray.pop();
		repArray.pop();
	}
}

/*
* Not used...
*/
function loadRxIntoScale()
{
	//alert("Load RX data into Scaled form");
}

function submitPartToText(form) {
	
}

/*********************************** FINAL SUBMIT *****************************************/

/*
* Called when the user submits the form on the main page.
* Serializes the RX form and the concatenates it with the 
* concatenated intermediate and novice arrays. Submit via
* ajax call. 
*
* Performs final input checking to ensure invalid characters
* are not present
*
* Returns a popup notification of whatever the MySQL result
* is.
*
*/
var data_array_2;
function submitWOD() {
	if(hasLoadedScaled == true) {
		var sendRequest = true;
		var num_of_movements = 0;
		var num_of_parts = 0;
		if($("#datepicker").val().length == 0){
			sendRequest = false;
		}
		
		var datastring = $("#new_wod_form").serializeArray();
		var id_of_girl = "";

		var data_four = datastring.concat(data_three);
		console.log("data_four: " + data_four.toString());
		$('.movement').each(function(i, item) {
			var movement =  $('#movement_'+i+'').val();
			var characterReg = /^[a-zA-Z\s]*$/;
			if(!characterReg.test(movement)) {
				sendRequest = false;
				$('#movement_'+i+'').addClass("big_input_wod_error");
			} else if (movement.length == 0) {
				$('#movement_'+i+'').addClass("big_input_wod_error");
				sendRequest = false;
			}
		});
		
		$('.weight').each(function(i, item) {
			var weight =  $('#weight_'+i+'').val();
			var characterReg = /^[0-9\/]*$/;
			if(!characterReg.test(weight)) {
				sendRequest = false;
				$('#weight_'+i+'').addClass("big_input_wod_error ");
			}
		});
		
		$('.reps').each(function(i, item) {
			var reps =  $('#reps_'+i+'').val();
			var characterReg = /^[a-zA-Z0-9\s]*$/;
			if(!characterReg.test(reps)) {
				sendRequest = false;
				$('#reps_'+i+'').addClass("big_input_wod_error ");
			} /*else if (reps.length == 0) {
				$('#reps_'+i+'').addClass("big_input_wod_error");
				sendRequest = false;
			}*/
		});
		var amrap_time = "";
		$.each(data_four, function(i, field){
			var name = field.name;
			if(name.indexOf("amrap_0") > -1 ) {
				//alert("DATA: " +field.name + ":" + field.value + " ");
				amrap_time += field.value;
			} else if(name.indexOf("amrap_1") > -1 || name.indexOf("amrap_2") > -1 ) {
				//alert("DATA: " +field.name + ":" + field.value + " ");
				amrap_time += ":"+field.value;
			}
		});
		
		var t_index = 0;
		var numRounds = "";
		$.each(data_four, function(i, field){
			console.log("DATA: " +field.name + ":" + field.value + " ");
			if(field.name == "num_of_rounds" ) {
				numRounds = field.value;
			}
			if(field.value == "grl_04" || field.value == "grl_14" || field.value == "grl_19" || field.value == "grl_20" ) {
				id_of_girl = field.value;
				field.value = "AMRAP";	
				amrap_time = girl_amrap_time;
				data_four.push({ name: "girl_id", value: id_of_girl });
			} else if (field.value.indexOf("grl_") > -1) {
				id_of_girl = field.value;
				field.value = "RFT";
				data_four.push({ name: "num_of_rounds", value: number_of_rounds });
				data_four.push({ name: "girl_id", value: id_of_girl });
			} else if(field.name.indexOf("extrapart[]") > -1) {
				if(field.name.indexOf("inter_extrapart[]") > -1) {
					data_four.splice(i, 1, {name:"inter_movement[]", value:"r0000*"+field.value});
				} else if(field.name.indexOf("nov_extrapart[]") > -1) {
					data_four.splice(i, 1, {name:"nov_movement[]", value:"r0000*"+field.value});
				} else {
					data_four.splice(i, 1, {name:"movement[]", value:"r0000*"+field.value});
					if(field.value.indexOf("RFT") > -1) {
						console.log("RFT Index: "+field.value.indexOf("RFT"));
						t_index = field.value.indexOf("RFT");
						newWODType.push({ name:field.value.substring(t_index, t_index+3), value:field.value.substring(t_index+4)})
					} else if(field.value.indexOf("AMRAP") > -1) {
						console.log("AMRAP Index: "+field.value.indexOf("AMRAP"));
						t_index = field.value.indexOf("AMRAP");
						newWODType.push({ name:field.value.substring(t_index, t_index+5), value:field.value.substring(t_index+6)});
					}
				}				
			}
			
		});
		var isDifferent = false;
		var mixed = "";
		var originalWODType = $("#wod_type_selector").val();
		if(newWODType.length > 0) {
			for(var z = 0; z < newWODType.length; z++) {
				console.log(newWODType[z].name +" : "+newWODType[z].value );
				if(newWODType[z].name !== originalWODType) {
					isDifferent = true;
				}
			}
		}
		//console.log("Does "+$("#wod_type_selector").val() +" = "+newWODType +"?");
		
		if(newWODType.length > 0 && isDifferent === true) {
			mixed += originalWODType + " " + amrap_time + "" + numRounds + ",";
			for(var z = 0; z < newWODType.length; z++) {
				mixed += newWODType[z].name +" "+newWODType[z].value +",";
			}
		}
		console.log("Mixed: " + mixed);
		
		data_four.push({ name: "amrap_time_update", value: amrap_time });
		data_four.push({ name: "mixed_column", value: mixed });
		data_four.push({ name: "custom_wod", value: "0" });
		
		$.each(data_four, function(i, field){
			console.log("DATA ROUND TWO: " +field.name + ":" + field.value + " ");
		});
		data_array_2 = data_four;
		//sendRequest = false;
		if(sendRequest == true) {
			$.ajax({
				type: "POST",
				url: "php_form_test.php",
				data: data_four,
				success: function(data) {
					 console.log('Data send:' + data + " : substring: " + data.substring(0,1));
					 if(data.substring(0,1) == "1") {
						 openModal("Duplicate Entry","You've already input a WOD for today, would you like to add to custom list? <p><p><a onclick=\"\" class=\"btn btn-primary btn-small\">No</a><a onclick=\"addToCustomWOD()\" class=\"btn btn-primary btn-small\">Yes</a></p></p>");
					 } else {
						 $("#wod_form_modal").dialog("close");
						 while(newWODType.length > 0) {
							newWODType.pop();
						 }
						 openModal("Success","WOD Entered Successfully<p><p><a onclick=\"resetFormModals()\" class=\"btn btn-primary btn-small\">Ok!</a></p></p>");
						 $('#calendar').fullCalendar('refetchEvents');
					 }
					 
				},
				error: function(data) {
						alert('Error:' + data);
				}
			});
		}
	} else {
		openModal("Set Scaled Movements","Please set scaled movements before submitting the WOD");
	}
}

/*
* Called when the user submits the form on the main page.
* Serializes the RX form and the concatenates it with the 
* concatenated intermediate and novice arrays. Submit via
* ajax call. 
*
* Performs final input checking to ensure invalid characters
* are not present
*
* Returns a popup notification of whatever the MySQL result
* is.
*
*/
function submitStrength() {
	var sendRequest = true;
	if($("#str_datepicker").val().length == 0){
		sendRequest = false;
	}
	
	var strength_data = $("#new_strength_form").serializeArray();
	
	alert("strength_data: " + strength_data.toString());
	var movement =  $('#strength_mov_0'+'').val();
	var reps = $('#strength_reps'+'').val();
	var strength_instructions = $('#strength_special_instructions'+'').val();
	var moveReg = /^[a-zA-Z\s]*$/;
	var repsReg = /^[a-z0-9\-]*$/;
	var weightReg = /^[0-9]*$/;
	if(!moveReg.test(movement)) {
		sendRequest = false;
		$('#strength_mov_0').addClass("big_input_wod_error");
	} else if (movement.length == 0) {
		$('#strength_mov_0').addClass("big_input_wod_error");
		sendRequest = false;
	}
	
	if(!repsReg.test(reps)) {
		sendRequest = false;
		$('#strength_reps').addClass("big_input_wod_error");
	} else if (reps.length < 0) {
		$('#strength_reps').addClass("big_input_wod_error");
		sendRequest = false;
	}
	
	if(!moveReg.test(strength_instructions)) {
		sendRequest = false;
		$('#strength_special_instructions').addClass("big_input_wod_error");
	}
	
	$.each(strength_data, function(i, field){
    	console.log("DATA: " +field.name + ":" + field.value + " ");
  	});

	//sendRequest = false;
	if(sendRequest == true) {
        $.ajax({
            type: "POST",
            url: "adminAddStrength.php",
            data: strength_data,
            success: function(data) {
                console.log('Data send:' + data);
				$("#strength_form_modal").dialog("close");
				openModal("Success","Strength Entered Successfully<p><p><a onclick=\"resetStrengthFormModal()\" class=\"btn btn-primary btn-small\">Ok!</a></p></p>");
				$('#calendar').fullCalendar('refetchEvents');
            }
        });
	}
}


function addToCustomWOD() {
		var sendRequest = false;
		var data_array = $("#new_wod_form").serializeArray();
		var mixed = "";
		data_array.push({ name: "custom_wod", value: "1" });
		
		$.each(data_array, function(i, field){
			console.log("DATA TO CUSTOM: " +field.name + ":" + field.value + " ");
			if(field.name.indexOf("extrapart[]") > -1) {
				data_array.splice(i, 1, {name:"movement[]", value:"r0000*"+field.value});
			}				
		});

		//openModal("Almost there...","Custom WOD functionality is currently in the works");
		
		sendRequest = true;
		if(sendRequest == true) {
			$.ajax({
				type: "POST",
				url: "php_form_test.php",
				data: data_array,
				success: function(data) {
					 console.log('Data send:' + data);
					 $("#wod_form_modal").dialog("close");
					 openModal("Success","Custom WOD Entered Successfully<p><p><a onclick=\"resetFormModals()\" class=\"btn btn-primary btn-small\">Ok!</a></p></p>");
					 $('#calendar').fullCalendar('refetchEvents');
				},
				error: function(data) {
						alert('Error:' + data);
				}
			});
		}
}


/****************************** Girls and heroes array builder **********************************************/
function theGirls(girl_id)
{
	var girl_array = new Array();
	
	//angie
	if(girl_id == "grl_01") {
		number_of_rounds = 1;
		girl_array.push("For Time. Complete all reps of each exercise before moving to the next");
		girl_array.push("pull ups, , 100");
		girl_array.push("push ups, , 100");
		girl_array.push("sit ups, , 100");
		girl_array.push("squats, , 100");
	} else if(girl_id == "grl_02") {
		number_of_rounds = 5;
		girl_array.push("5 rounds For Time. 3 minute rest in between each set");
		girl_array.push("pull ups, , 20");
		girl_array.push("push ups, , 30");
		girl_array.push("sit ups, , 40");
		girl_array.push("squats, , 50");
	} else if(girl_id == "grl_03") {
		number_of_rounds = 30;
		girl_array.push("Every minute on the minute for 30 minutes");
		girl_array.push("pull ups, , 5");
		girl_array.push("push ups, , 10");
		girl_array.push("squats, , 15");
	} else if(girl_id == "grl_04") {
		girl_amrap_time = "20:00";
		girl_array.push("20 minute AMRAP");
		girl_array.push("pull ups, , 5");
		girl_array.push("push ups, , 10");
		girl_array.push("squats, , 15");
	} else if(girl_id == "grl_05") {
		number_of_rounds = 1;
		girl_array.push("21-15-9 reps for time of ");
		girl_array.push("deadlift, 225, ");
		girl_array.push("handstand push ups, , ");
	} else if(girl_id == "grl_06") {
		number_of_rounds = 1;
		girl_array.push("21-15-9 reps for time of ");
		girl_array.push("Power clean, 135, ");
		girl_array.push("ring dips, , ");
	} else if(girl_id == "grl_07") {
		number_of_rounds = 1;
		girl_array.push("21-15-9 reps for time of ");
		girl_array.push("thrusters, 95, ");
		girl_array.push("pull ups, , ");		
	} else if(girl_id == "grl_08") {
		number_of_rounds = 1;
		girl_array.push("30 reps for time of ");
		girl_array.push("Clean and Jerks, 135, ");
	} else if(girl_id == "grl_09") {
		number_of_rounds = 3;
		girl_array.push("3 rounds for time of ");
		girl_array.push("Run, , 400m");
		girl_array.push("Kettlebell swings, 50, 21");
		girl_array.push("Pull ups, , 12");
	} else if(girl_id == "grl_10") {
		number_of_rounds = 1;
		girl_array.push("30 reps for time of ");
		girl_array.push("Snatch, 135, ");		
	} else if(girl_id == "grl_11") {
		number_of_rounds = 1;
		girl_array.push("For time ");
		girl_array.push("Row, , 1000m");
		girl_array.push("Thrusters, 45, 50");
		girl_array.push("pull ups, , 30");
	} else if(girl_id == "grl_12") {
		number_of_rounds = 1;
		girl_array.push("For time ");
		girl_array.push("Wall balls, 20/14, 150");
	} else if(girl_id == "grl_13") {
		number_of_rounds = 1;
		girl_array.push("10-9-8-7-6-5-4-3-2-1 of ");
		girl_array.push("Deadlift, , ");
		girl_array.push("Bench, , ");
		girl_array.push("Clean, , ");
	} else if(girl_id == "grl_14") {
		girl_amrap_time = "20:00";
		girl_array.push("20 minute AMRAP");
		girl_array.push("handstand push ups, , 5");
		girl_array.push("pistols, , 10");
		girl_array.push("pull ups, , 15");
	} else if(girl_id == "grl_15") {
		number_of_rounds = 5;
		girl_array.push("5 rounds for time ");
		girl_array.push("Run, , 400m");
		girl_array.push("overhead squat, 95, 15");
	}else if(girl_id == "grl_16") {
		number_of_rounds = 1;
		girl_array.push("50-40-30-20-10 of ");
		girl_array.push("Double unders, , ");
		girl_array.push("Sit ups, , ");
	} else if(girl_id == "grl_17") {
		number_of_rounds = 5;
		girl_array.push("5 rounds for time ");
		girl_array.push("Run, , 800m");
		girl_array.push("Kettlebell swings, 70, 30");
		girl_array.push("Pull ups, , 30");
	} else if(girl_id == "grl_18") {
		number_of_rounds = 5;
		girl_array.push("5 rounds for time ");
		girl_array.push("Run, , 400m");
		girl_array.push("Box jumps, , 24in");
		girl_array.push("Wall balls, 20/14, 30");
	} else if(girl_id == "grl_19") {
		girl_amrap_time = "90:00";
		girl_array.push("5 rounds of max effort ");
		girl_array.push("Bench press, , ");
		girl_array.push("Pull ups, , ");
	} else if(girl_id == "grl_20") {
		girl_amrap_time = "20:00";
		girl_array.push("Your score is total pull ups, 20 minute AMRAP ");
		girl_array.push("Run, , 400m");
		girl_array.push("Pull ups, , ");
	} else if(girl_id == "grl_21") {
		number_of_rounds = 1;
		girl_array.push("9-7-5 reps For time ");
		girl_array.push("Muscle ups, , ");
		girl_array.push("Snatches, 135/95, ");
	}
	
	return girl_array;
}

/*******************************Calendar Related functions *****************************************/

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

function renderCalendar() {
	getCurrentDate();
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		defaultDate: today,
		selectable: true,
		selectHelper: true,
		/*select: function(start, end) {
			var title = prompt('Event Title:');
			var eventData;
			if (title) {
				eventData = {
					title: title,
					start: start,
					end: end
				};
				$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
			}
			$('#calendar').fullCalendar('unselect');
		},*/
		editable: false,
		eventSources: 
		[
			{
				type:"POST",                                     
				url:"getAdminWODs.php",         
				dataType: "json",                //data format      
				success: function(response) //on recieve of reply
				{
					console.log("calendar loaded workouts");
					
				},
				error: function(){
					alert('error loading workouts!');
				},
				textColor: 'black' // a non-ajax option
			},
			{
				type:"POST",                                     
				url:"getAdminStrength.php",         
				dataType: "json",                //data format      
				success: function(response) //on recieve of reply
				{
					console.log("calendar strength: " + response);
				},
				error: function(){
					alert('error loading workouts!');
				},
				color: 'black',   // a non-ajax option
				textColor: 'white' // a non-ajax option
			},
			{
				type:"POST",                                     
				url:"getAdminPWODs.php",         
				dataType: "json",                //data format      
				success: function(response_wods) //on recieve of reply
				{
					console.log("post wod: " + response_wods);
				},
				error: function()
				{
					alert('error loading post wods!');
				}
			
			}
		],
		eventRender: function (event, element) {
			element.attr('href', 'javascript:void(0);');
			console.log(event.description);
			var quoteIndex = 0;
			var tempString = "";
			var t_String2 = "";
			var description = "";
			quoteIndex = event.description.indexOf("\"");
			if(quoteIndex > -1) {
				t_String2 = event.description;
				while (quoteIndex > -1) {
					tempString = t_String2.substring(0, quoteIndex);
					description += tempString +"\\" + '"';
					t_String2 = t_String2.substring(quoteIndex+1);
					quoteIndex = t_String2.indexOf("\"");
				}
				description += t_String2;
				console.log("Description: " + description);
			}
			if(description.length < 1) {
				element.attr('onclick', 'openModal("' + event.title + '","' + event.description + '","'+true+'");');
			} else {
				element.attr('onclick', 'openModal("' + event.title + '","' + description + '","'+true+'");');
			}
		}
	});
	getWorkouts();
}

function openWODModal() {
    $( "#wod_form_modal" ).dialog({
      height: 600,
	  width: 1000,
      modal: true
    });
	
	
	$( "#wod_form_modal" ).dialog();
	
	$('#weight_0').qtip({ 
    content: 'Leave blank for bodyweight movements'
	});
	$('#reps_0').qtip({ 
    content: 'Use calories or meters if distance'
	});
}

function openScaledWODModal() {
    $( "#scaled_wod_form_modal" ).dialog({
      height: 500,
	  width: 800,
      modal: true
    });
	$( "#scaled_wod_form_modal" ).dialog();
	
}

function openStrengthModal() {
    $( "#strength_form_modal" ).dialog({
      height: 600,
	  width: 1000,
      modal: true
    });
	
	
	$( "#strength_form_modal" ).dialog();

}

function openPostWODModal() {
    openModal("Post WOD", "This feature is not yet implemented", '', 200);
}

function openNewPartModal() {
    $( "#newPartModal" ).dialog({
      height: 400,
	  width: 600,
      modal: true
    });

	$( "#newPartModal" ).dialog();
	
}

function openModal(title, info, shouldFormat, height, width) {
    
	var opt_height = (typeof height === "undefined") ? 400 : height;
	var opt_width = (typeof width === "undefined") ? 300 : width;
	console.log(info);
    $( "#dialog-modal" ).dialog({
      height: opt_height,
	  width: opt_width,
      modal: true
    });
	
	var optional = (typeof shouldFormat === "undefined") ? false : shouldFormat;
	console.log("optional: " + optional);
	
	if(optional == "true") {
		/******
		 * First take care of Time/Rounds, Buy In, Cash Out, Special Instructions
		 * Then parse the WOD
		 *******/
		
		
		/*if(main_index == -1) {
			formattedDescription = tempStr_one;
		} else {
			while(main_index > -1) {
				console.log("main_index  = "+main_index);
				tempStr_two = tempStr_one.substring(0, main_index);
				tempStr_one = tempStr_one.substring(main_index+1);
				formattedDescription += tempStr_two + "*P*";
				main_index = tempStr_one.indexOf(";");
			}
			formattedDescription += tempStr_one;
			console.log("after main index T1: " + tempStr_one + " FMDes: " + formattedDescription);
			
			exerIndex = formattedDescription.indexOf(","); //parse the exercises/movements
			if(exerIndex > -1) {
				tempStr_one = formattedDescription;
				formattedDescription = "";
				while(exerIndex > -1) {
					console.log("exerIndex  = "+exerIndex);
					tempStr_two = tempStr_one.substring(0, exerIndex);
					tempStr_one = tempStr_one.substring(exerIndex+1);
					formattedDescription += tempStr_two + " *E* ";
					exerIndex = tempStr_one.indexOf(",");
					console.log("T1: " + tempStr_one + " T2: " + tempStr_two + " exerIndex: " + exerIndex);
				}
			}
			formattedDescription += tempStr_one;
			console.log("after exr index T1: " + tempStr_one + " FMDes: " + formattedDescription);
			
			npIndex = formattedDescription.indexOf(">"); //parse the WOD
			if(npIndex > -1) {
				tempStr_one = formattedDescription;
				formattedDescription = "";
				while(npIndex > -1) {
					console.log("npIndex  = "+npIndex);
					tempStr_two = tempStr_one.substring(0, npIndex);
					tempStr_one = tempStr_one.substring(npIndex+1);
					formattedDescription += tempStr_two + " *N* ";
					npIndex = tempStr_one.indexOf(">");
					console.log("T1: " + tempStr_one + " T2: " + tempStr_two + " npIndex: " + npIndex);
				}
			}
			formattedDescription += tempStr_one;
			console.log("after np index T1: " + tempStr_one + " FMDes: " + formattedDescription);
		}
		console.log("Formatted: "+formattedDescription);
	} else {
		formattedDescription = info;
	}*/
	
	//main_index = formattedDescription.indexOf("*P*");
	/*
	if(main_index > -1) 
	{
		tempStr_one = formattedDescription;
		formattedDescription = "";
		while(main_index > -1) {
			console.log("main_index  = "+main_index);
			tempStr_two = tempStr_one.substring(0, main_index);
			tempStr_one = tempStr_one.substring(main_index+3);
			formattedDescription += tempStr_two + "<p></p>";
			main_index = tempStr_one.indexOf("*P*");
		}
		formattedDescription += tempStr_one;
		console.log("after main index T1: " + tempStr_one + " FMDes: " + formattedDescription);
	}
	exerIndex = formattedDescription.indexOf("*E*"); //parse the exercises/movements
	if(exerIndex > -1) {
		tempStr_one = formattedDescription;
		formattedDescription = "";
		while(exerIndex > -1) {
			console.log("exerIndex  = "+exerIndex);
			tempStr_two = "<p> "+tempStr_one.substring(0, exerIndex) + " </p>";
			tempStr_one = tempStr_one.substring(exerIndex+3);
			formattedDescription += tempStr_two;
			exerIndex = tempStr_one.indexOf("*E*");
			console.log("T1: " + tempStr_one + " T2: " + tempStr_two + " exerIndex: " + exerIndex);
		}
	}
	formattedDescription += tempStr_one;
	console.log("after exr index T1: " + tempStr_one + " FMDes: " + formattedDescription);
	
	npIndex = formattedDescription.indexOf("*N*"); //parse the WOD
	if(npIndex > -1) {
		tempStr_one = formattedDescription;
		formattedDescription = "";
		while(npIndex > -1) {
			console.log("npIndex  = "+npIndex);
			tempStr_two = tempStr_one.substring(0, npIndex);
			tempStr_one = tempStr_one.substring(npIndex+3);
			formattedDescription += tempStr_two + "<p></p>";
			npIndex = tempStr_one.indexOf("*N*");
			console.log("T1: " + tempStr_one + " T2: " + tempStr_two + " npIndex: " + npIndex);
		}
	}
	formattedDescription += tempStr_one;
	console.log("after np index T1: " + tempStr_one + " FMDes: " + formattedDescription);
	console.log("Formatted: "+formattedDescription);
	*/
	}
	formattedDescription = info;
	
	console.log("Formatted Description: " + formattedDescription);
	
	$( "#dialog-modal" ).dialog('option', 'title', title);
	$('#workoutcontent').html(formattedDescription);
	
}

function closeScaledModal() {
	$( "#dialog-modal" ).dialog("close");
	$( "#scaled_wod_form_modal" ).dialog("close");
}

function resetFormModals() {
	for(var i = rowNum; i > 0; i--) {
		removeRow(i);
	}
	
	var element = document.getElementById('wod_type_selector');
    element.value = "RFT";
	
	$('#addRowBut').removeAttr('disabled');
	str = "Rounds: <input type=\"text\" name=\"num_of_rounds\" class=\"num_of_rounds\" id=\"num_of_rounds\" placeholder=\"5, 21-15-9, etc\"/>";
	$("#specific_to_wod").html(str);
	
	$( "#wod_form_modal input" ).each(function(index, element) {
        console.log(index + " : " + $(this).text() + " : " + $(this).attr("id"));
		if($(this).attr("id") == "removebutton" || $(this).attr("id") == "addRowBut" || $(this).attr("id") == "submitWodBut" || $(this).attr("id") == "addPartBut") {
			console.log("button");
		} else {
			$(this).val('');
		}
    });
	$( "#scaled_wod_form_modal input" ).each(function(index, element) {
        console.log(index + " : " + $(this).text() + " : " + $(this).attr("id"));
		if($(this).attr("id") == "load" || $(this).attr("id") == "submit") {
			console.log("button");
		} else {
			$(this).val('');
		}
    });
	$("#dialog-modal").dialog("close");
}


function resetStrengthFormModal() {
	var element = document.getElementById('str_instruction_selector');
    element.value = "PER";
	
	$( "#strength_form_modal input" ).each(function(index, element) {
        console.log(index + " : " + $(this).text() + " : " + $(this).attr("id"));
		if($(this).attr("id") == "submitStrengthButton") {
			console.log("button");
		} else {
			$(this).val('');
		}
    });
	$("#dialog-modal").dialog("close");
}


var source = new Array();
function getWorkouts() {
	console.log("getting workouts...");

	var myDate= new Date();
	myDate.setFullYear(2014,4,20);
	var myEvent = {
		title:"my new event",
		allDay: false,
		start: myDate,
		end: myDate,
		id: '1'
	};
	console.log("Title, start, myDate:" + myEvent.title + ", " + myEvent.start + ", " + myDate);
	//$('#calendar').fullCalendar( 'renderEvent', myEvent, true );
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getAdminWODs.php",         
	  dataType: "text",                //data format      
	  success: function(response) //on recieve of reply
	  {
		//console.log("response: "+response);
	  },
	  error: function(){
			alert('error loading workouts!');
		}
	});

}
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50665970-2', 'compete-box.com');
  ga('send', 'pageview');

</script>

</body>
</html>