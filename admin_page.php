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
####Set for debugging purposes
#	$_SESSION['MM_Username'] = "kellintc";
####
if(!(isset($_SESSION['MM_Username'])))
{
	header("Location: Error401UnauthorizedAccess.php");
}
mysql_select_db($database_cboxConn, $cboxConn);
$colname_getUserBox = "-1";
$userBoxID = "";

####Set for debugging purposes
#	$_SESSION['MM_UserID'] = "2";
####
if (isset($_SESSION['MM_UserID'])) {
  $colname_getUserBox = $_SESSION['MM_UserID'];
  
  $LoginRS__query="select box.box_id from box JOIN athletes AS a ON box.box_id=a.box_id where user_id='{$colname_getUserBox}'"; 
   
  $LoginRS = mysql_query($LoginRS__query, $cboxConn) or die(mysql_error());
  $loginFoundBox = mysql_num_rows($LoginRS);
  if ($loginFoundBox) {
    $row = mysql_fetch_row($LoginRS);
	if (PHP_VERSION >= 5.1) {
		session_regenerate_id(true);
	} 
	else {
			session_regenerate_id();
	}
    //declare boxID session variable and assign them
    $_SESSION['MM_BoxID'] = $row[0];   

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Administrator</title>

<!-- Bootstrap core CSS -->
     <!--<link href="dist/css/jquery.datepick.css" rel="stylesheet">-->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="dist/css/admin_page.css" rel="stylesheet">
    -->
 	<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="dist/css/admin_page.css" rel="stylesheet">
    <link href="dist/css/jquery.datepick.css" rel="stylesheet">
</head>

<body>

<div id="div_container">
    <div id="navbar_main">
        <ul id="navbar_main_ul"> 
            <li id="home" ><a href="Admin_home_page.php" >HOME</a></li> 
            <li id="compare"><a href="user_compare_page.php" >COMPARE</a></li> 
            <li id="wod" ><a href="user_wod_page.php" >WOD</a></li> 
            <li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li>
            <li id="admin" class="active"><a href="#" >Admin</a></li>
            <li id="account" ><a href="#" >ACCOUNT</a></li> 
            <li id="logout" ><a href="#" >LOGOUT</a></li>
        </ul> 
    </div>

    <hr class="featurette-divider">
    <h1>Past WODs</h1>
    <div id="past_wods">
        <table id="tbl_past_wod" rules="cols">
        	<tbody class="tbl_body_past_wods">
			</tbody>
        </table>
    </div><!-- END OF past_wods -->
    
    <hr class="featurette-divider">
    <h1>Past Strength</h1>
    <div id="past_strength">
    	<table id="tbl_past_str" rules="cols">
        	<tbody class="tbl_body_past_str">
			</tbody>
        </table>
    </div><!-- END OF past_strength -->
    
    <hr class="featurette-divider">
    <h1>Past Post WODs</h1>
    <div id="past_post_wods">
    	<table id="tbl_past_pwod" rules="cols">
        	<tbody class="tbl_body_past_pwod">
			</tbody>
        </table>
    </div><!-- END OF past_post_wods -->
    
    <hr class="featurette-divider">
    <h1>New WOD</h1>
    <div id="new_wod_container">
    <form method="POST" id="new_wod_form" class="new_wod">
    <p id="date_type_p">
    	Date: <input type="text" name="date" class="datepicker" id="datepicker"/> 
        Type of WOD: <select id="wod_type_selector" name="wod_type_selector">
          <option value="RFT">RFT</option>
          <option value="AMRAP">AMRAP</option>
          <option value="TABATA">TABATA</option>
          <option value="GIRLS">GIRLS</option>
          <option value="HERO">HEROES</option>
		</select>
        <div id="specific_to_wod"></div>
        <div>
        <p>
        Buy In: <input type="text" name="buy_in" class="extra_wod_stuff" id="buy_in" placeholder="optional"/>
        Cash Out: <input type="text" name="cash_out" class="extra_wod_stuff" id="cash_out" placeholder="optional"/>
        Penalty: <input type="text" name="penalty" class="extra_wod_stuff" id="penalty" placeholder="Everytime you drop the bar..."/>
        Special: <input type="text" name="special" class="extra_wod_stuff" id="special" placeholder="Every minute on the minute..."/>
        </p>
        </div>
	</p>
        <div id="new_wod_row" class="new_wod_row">
            Movement: <input type="text" name="movement[]" class="movement" id="movement_0"/> 
            Weight (leave blank if bodyweight): <input type="text" name="weight[]" class="weight" id="weight_0" placeholder="Guys/Girls"/> 
			Reps/Distance: <input type="text" name="reps[]" class="reps" id="reps_0" placeholder="use calories or meters where needed"/>
            <p class="new_wod_p"></p>
        </div> <!-- END OF new_wod -->
        <input onclick="addRow('','','');" type="button" value="Add row" id="addRowBut"/>
        <input onclick="submitWOD(this.form);" type="button" value="Submit WOD" />
     </form>
     </div> <!-- END OF new_wod_container -->
    
   
	<div id="example_modal" class="modal" style="display:none; ">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Scale for Intermediate and Novice</h3>
        </div>
        <div class="modal-body">
        	<!-- Grab number of rows and place that many into here -->  
            <form method="POST" id="inter_new_wod_form" class="new_wod">
                <h4>Intermediate</h4>
				<div id="inter_new_wod_row">
                    Movement: <input type="text" name="inter_movement[]" class="inter_movement" id="inter_movement_0"/> 
                    Weight (leave blank if bodyweight): <input type="text" name="inter_weight[]" class="inter_weight" id="inter_weight_0" placeholder="Guys/Girls"/> 
                    Reps/Distance: <input type="text" name="inter_reps[]" class="inter_reps" id="inter_reps_0"/>
                    <p></p>
                    
                </div> <!-- END OF new_wod -->
             </form> 
             <hr class="featurette-divider"> 
             <form method="POST" id="nov_new_wod_form" class="new_wod">
                <div id="novice_new_wod_row">
                <h4>Novice</h4>
                    Movement: <input type="text" name="nov_movement[]" class="nov_movement" id="nov_movement_0"/> 
                    Weight (leave blank if bodyweight): <input type="text" name="nov_weight[]" class="nov_weight" id="nov_weight_0" placeholder="Guys/Girls"/> 
                    Reps/Distance: <input type="text" name="nov_reps[]" class="nov_reps" id="nov_reps_0"/>
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
<p><a data-toggle="modal" href="#example_modal" class="btn btn-primary btn-small">Set Scaled Movements</a></p>
   
    
    <hr class="featurette-divider">
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
            <input onclick="submitStrength(this.form);" type="button" value="Submit Strength" />
            </form>
        </div> <!-- END OF new_wod_container -->
    </div><!-- END OF past_post_wods -->
    
    <hr class="featurette-divider">
    <div id="new_post_wod">
    	<p>NEW POST WOD FORM HERE</p>
    </div><!-- END OF past_post_wods -->

</div> <!-- END OF DIV_CONTAINER -->

<!-- JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script src="dist/js/jquery.plugin.min.js"></script>
<script src="dist/js/jquery.datepick.min.js"></script>


<!--

<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
-->
<script id="source" language="javascript" type="text/javascript">

var movementArray = new Array();
var weightArray = new Array();
var repArray = new Array();

var data_one;
var data_two;
var data_three;

var rowNum = 0;

var previousVal = "";
var currentVal = "";

var number_of_rounds = 0;
var girl_amrap_time = "";

/*
* Once the document is  loaded, grab the WoD information
*
*/
$(document).ready(function() {
	//event.preventDefault();
	getPastWODS(<?php echo $_SESSION['MM_BoxID'] ?>);
	getPastStrength(<?php echo $_SESSION['MM_BoxID'] ?>);
	getPastPostWODS(<?php echo $_SESSION['MM_BoxID'] ?>);
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
					window.location.replace("http://cboxbeta.com/login_bootstrap.php");
				} 
			});
		}
	});	

/*
* Set up the datepicker object
*
*/
$(function() {
	//event.preventDefault();
    $("#datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#str_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
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
		$('#specific_to_wod').empty();
		if($( this ).text() == "RFT"){
			$('#addRowBut').removeAttr('disabled');
		str = "Rounds: <input type=\"text\" name=\"num_of_rounds\" class=\"num_of_rounds\" id=\"num_of_rounds\"/>";
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
			$('#movement_0').val(movement);
			$('#weight_0').val(weight);
			$('#reps_0').val(reps);
		} else {
			addRow(movement, weight, reps);
		}
		
	}
});

  
/********************************* GETTER METHODS *********************************/

/*
* Called when page is finished loading
* Used to gather all the WoDs specific to Admin's
* box. 
*
*/
function getPastWODS(box_id)
{
	var boxID = box_id;

	var html = "";
	$('.tbl_past_wod').html(html);
	//now load data into table
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getAdminWODs.php",         
	  data: { "dataString" : boxID }, //insert argumnets here to pass to getAdminWODs
	  dataType: "json",  //data format      
	  success: function(response_wods) //on recieve of reply
	  {
		  console.log("response_wods: " + response_wods);
		loadPastWODS(response_wods);
	  },
  	  error: function(){
    		alert('error loading wods!');
  		}
	});
	//alert("Past WODs FIN");
}

/*
* Called when page is finished loading
* Used to gather all the Strength specific to Admin's
* box. 
*
*/
function getPastStrength(box_id)
{
	var boxID = box_id;
	
	//$('.tbl_past_wod').empty();
	var html = "";
	

	//alert("HTML: " + html )
	$('.tbl_past_str').html(html);
	//now load data into table
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getAdminStrength.php",         
	  data: { "dataString" : boxID }, //insert argumnets here to pass to getAdminWODs
	  dataType: "json",                //data format      
	  success: function(response) //on recieve of reply
	  {
	   console.log("strength: " + response);
		loadPastStr(response);
	  },
  	  error: function(){
    		alert('error loading strength!');
  		}
	});
	//alert("Past WODs FIN");
}

/*
* Called when page is finished loading
* Used to gather all the Post WoDs specific to Admin's
* box. 
*
*/
function getPastPostWODS(box_id)
{
	var boxID = box_id;
	
	//$('.tbl_past_wod').empty();
	var html = "";
	

	//alert("HTML: " + html )
	$('.tbl_past_pwod').html(html);
	//now load data into table
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getAdminPWODs.php",         
	  data: { "dataString" : boxID }, //insert argumnets here to pass to getAdminWODs
	  dataType: "json",                //data format      
	  success: function(response_wods) //on recieve of reply
	  {
		  console.log("post wod: " + response_wods);
		loadPastPWODS(response_wods);
	  },
  	  error: function(){
    		alert('error loading post wods!');
  		}
	});
}

/******************************** LOAD TABLES **************************************/

/*
* Called in getPastWODS function.
* Parses the ajax request - JSON - format, and
* places the results in descending order in a table 
* in the appropriate DOM.
*
*/
function loadPastWODS(data_wods)
{
	var t_data = data_wods;
	
	var html_sec1 = "";
	var sec1_classID = "pastwod_sec1_data"; 
	var wod_id = "";
	var dow = "";
	var wodname;
	var type_of_wod = "";
	var descrip = "";
	//alert("loadPastWODS PRE-FOR LOOP");
	//alert("DATA: " + data_wods);
	//alert("t_DATA: " + t_data);
	for(var i = 0; i < data_wods.length; i++) {
		wod_id = data_wods[i].wod_id;
		dow = data_wods[i].date_of_wod;
		wodname = data_wods[i].name_of_wod;
		type_of_wod = data_wods[i].type_of_wod;
		descrip = data_wods[i].rx_descrip;
		
		html_sec1 += "<tr class="+sec1_classID+">";
		html_sec1 += "<td>"+wod_id+"</td>";
		html_sec1 += "<td>"+dow+"</td>";
		html_sec1 +="<td>"+wodname+"</td>";
		html_sec1 +="<td>"+type_of_wod+"</td>";
		html_sec1 +="<td class=\"pastwod_descrip\">"+descrip+"</td>";
		html_sec1 += "</tr>";
	}
	//Update html content
	//alert("HTML: " + html);
	$('.tbl_body_past_wods').empty();
	$('.tbl_body_past_wods').html(html_sec1);
	
}

/*
* Called in getPastStrength function.
* Parses the ajax request - JSON - format, and
* places the results in descending order in a table 
* in the appropriate DOM.
*
*/
function loadPastStr(data)
{
	var t_data = data;
	
	var html_sec1 = "";
	var sec1_classID = "str_sec1_data"; 
	var str_id = "";
	var dos = "";
	var movement = "";
	var descrip = "";

	for(var i = 0; i < data.length; i++) {
		str_id = data[i].str_id;
		dos = data[i].date_of_str;
		movement = data[i].movement;
		descrip = data[i].descrip;
		
		html_sec1 += "<tr class="+sec1_classID+">";
		html_sec1 += "<td>"+str_id+"</td>";
		html_sec1 += "<td>"+dos+"</td>";
		html_sec1 +="<td>"+movement+"</td>";
		html_sec1 +="<td class=\"paststr_descrip\">"+descrip+"</td>";
		html_sec1 += "</tr>";
	}
	//Update html content
	$('.tbl_body_past_str').empty();
	$('.tbl_body_past_str').html(html_sec1);
	
}

/*
* Called in getPastPostWODS function.
* Parses the ajax request - JSON - format, and
* places the results in descending order in a table 
* in the appropriate DOM.
*
*/
function loadPastPWODS(data)
{
	var t_data = data;
	
	var html_sec1 = "";
	var sec1_classID = "pwod_sec1_data"; 
	var pwod_id = "";
	var dop = "";
	var type_of_pwod = "";
	var descrip = "";

	for(var i = 0; i < data.length; i++) {
		pwod_id = data[i].pwod_id;
		dop = data[i].date_of_pwod;
		type_of_pwod = data[i].type_of_pwod;
		descrip = data[i].descrip;
		
		html_sec1 += "<tr class="+sec1_classID+">";
		html_sec1 += "<td>"+pwod_id+"</td>";
		html_sec1 += "<td>"+dop+"</td>";
		html_sec1 +="<td>"+type_of_pwod+"</td>";
		html_sec1 +="<td class=\"pastpwod_descrip\">"+descrip+"</td>";
		html_sec1 += "</tr>";
	}
	//Update html content
	$('.tbl_body_past_pwod').empty();
	$('.tbl_body_past_pwod').html(html_sec1);
}

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
	var row = '<p id="rowNum'+rowNum+'">Movement: <input type="text" name="movement[]" class="movement" id="movement_'+rowNum+'"> Weight (leave blank if bodyweight): <input type="text" name="weight[]" class="weight" id="weight_'+rowNum+'"> Reps: <input type="text" name="reps[]" class="reps" id="reps_'+rowNum+'"> <input type="button" value="Remove" id="removebutton" onclick="removeRow('+rowNum+');"></p>';
	$('#new_wod_row').append(row);
}

/*
* Called from the  new_wod_form in the input div
* Creates a new <p> which holds the new rows of input
* Increments rowNum by 1 each time the button is pressed,
* and uses rowNum to id the paragraph and input text fields
*
*/
function addRow(movement, weight, reps) {
	rowNum++;
	console.log("RowNum ADDED ROW: "+rowNum);
	var row = '<p id="rowNum'+rowNum+'">Movement: <input type="text" name="movement[]" class="movement" id="movement_'+rowNum+'" value="'+movement+'"> Weight (leave blank if bodyweight): <input type="text" name="weight[]" class="weight" id="weight_'+rowNum+'" value="'+weight+'"> Reps: <input type="text" name="reps[]" class="reps" id="reps_'+rowNum+'" value="'+reps+'"> <input type="button" value="Remove" id="removebutton" onclick="removeRow('+rowNum+');"></p>';
	$('.new_wod_p').append(row);
}

/*
* Called from the  new_wod_form in the input div
* Removes a <p> which holds the rows of input. 
* Decrements rowNum by 1 each time the button is pressed
* 
* Cannot remove the first line of inputs
*
*/
function removeRow(rnum) {
	var movement =  "";
	var weight =  "";
	var reps =  "";
	var counter = 0;
	var rowc  = 0;
	movement =  $('#movement_'+rnum+'').val();
	weight =  $('#weight_'+rnum+'').val();
	reps =  $('#reps_'+rnum+'').val();
	//metric = $('#rep_type_selector_'+rowNum+'').val();
	$('#rowNum'+rnum).remove();
	$('#inter_rowNum'+rnum).remove();
	$('#nov_rowNum'+rnum).remove();
	
	
	//alert("Exercise to remove: " + movementArray[rnum] + " " +weightArray[rnum]+ " " +repArray[rnum]);
	//reset all the ids of existing rows
	
	var myDiv = document.getElementById( "new_wod_row" ); 
	var inputArr = myDiv.getElementsByTagName( "input" ); 
	
	console.log("ARRAYLENGTH: " + inputArr.length);
	var hasChanged = false;
	for (var i = 0; i < inputArr.length; i++) 
	{ 
		var tempString = inputArr[i].getAttribute( 'id' );
		if(tempString == "removebutton") {
			console.log("button");
			console.log("i: "+i+", rowc: "+rowc);
		}
		 else {
			if(rowc%3==0) {
				hasChanged = false;
				counter++;
			}
			var t_index = tempString.indexOf("_"); 
			var t_id = tempString.substring(t_index+1, tempString.length);
			console.log("ID: "+t_id + ", INDEX: "+t_index +", rowCount variable: "+rowc+" counter: " + counter); 
			if(document.getElementById("movement_"+t_id+"") && hasChanged == false)
			{
				//alert("movement_"+t_id+"  exists");
				
				document.getElementById("movement_"+t_id+"").id = "movement_"+(counter-1);
				document.getElementById("weight_"+t_id+"").id = "weight_"+(counter-1);
				document.getElementById("reps_"+t_id+"").id = "reps_"+(counter-1);
				//document.getElementById("rep_type_selector_"+t_id+"").id = "rep_type_selector_"+(counter-1);
				hasChanged = true;
			}
			rowc++;
		}	
	}
	if(rowNum > 0) {
			rowNum--;
			console.log("RowNum REMOVED ROW: "+rowNum);
		}
}

/*
* Called from the  example_modal when user presses Load RX Data.
* Creates a new <p> which holds the new rows of input, uses for 
* loop to populate the forms based on RX data. Loads RX movements
* and weight automatically in form for better user experience
* 
*/
function addScaledRows()
{
	var movement =  "";
	var weight =  "";
	var reps =  "";
	var x = 7;
	var rowCount = 0;
	var newMvmArray = $("#new_wod_form").serializeArray();
	$.each(newMvmArray, function(i, field){
		if(i >= 7 && i%3==1) {
			x = i;
			movement =  $('#movement_'+rowCount+'').val();
			weight =  $('#weight_'+rowCount+'').val();
			reps =  $('#reps_'+rowCount+'').val();
			if(typeof movement === 'undefined')
			{
				console.log("UNDEFINED!!!! X = "+x+", RowCount: "+rowCount+", Movement: " +movement + ", Weight: " + weight + ", Reps: " + reps);
			} else {
				movementArray.push(movement);
				weightArray.push(weight);
				repArray.push(reps);
			 }
			rowCount++;
		}
		x = i + 3
  	});
	
	for(var k = 0; k < movementArray.length; k++)
	{
		console.log("Row to be inserted: " + movementArray[k] + " "+weightArray[k]+" "+ repArray[k]);
	}
	if(movementArray.length > 0) 
	{
		var row = "";
		//first intermediate
		$('#inter_new_wod_row').empty();
		for(var i = 0; i < movementArray.length; i++) 
		{	
			row = '<p id="inter_rowNum'+i+'">Movement: <input type="text" name="inter_movement[]" class="inter_movement" id="inter_movement_'+i+'" value="'+ movementArray[i] +'"> Weight (leave blank if bodyweight): <input type="text" name="inter_weight[]" class="inter_weight" id="inter_weight_'+i+'" value="'+weightArray[i]+'"> Reps: <input type="text" name="inter_reps[]" class="inter_reps" id="inter_reps_'+i+'" value="'+repArray[i]+'"></p>';
			$('#inter_new_wod_row').append(row);
		}
		$('#novice_new_wod_row').empty();
		for(var j = 0; j < movementArray.length; j++) {
			row = '<p id="nov_rowNum'+j+'">Movement: <input type="text" name="nov_movement[]" class="nov_movement" id="nov_movement_'+j+'" value="'+ movementArray[j] +'"> Weight (leave blank if bodyweight): <input type="text" name="nov_weight[]" class="nov_weight" id="nov_weight_'+j+'" value="'+weightArray[j]+'"> Reps: <input type="text" name="nov_reps[]" class="nov_reps" id="nov_reps_'+j+'" value="'+repArray[j]+'"></p>';
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
function submitWOD() {
	var sendRequest = true;
	if($("#datepicker").val().length == 0){
		sendRequest = false;
	}
	
	var datastring = $("#new_wod_form").serializeArray();
	var id_of_girl = "";

	var data_four = datastring.concat(data_three);
	alert("data_four: " + data_four.toString());
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
		} else if (reps.length == 0) {
			$('#reps_'+i+'').addClass("big_input_wod_error");
			sendRequest = false;
		}
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
	//alert("AMRAP_TIME: "+amrap_time);
	$.each(data_four, function(i, field){
		console.log("DATA: " +field.name + ":" + field.value + " ");
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
		}
  	});
	
	data_four.push({ name: "amrap_time_update", value: amrap_time });
	
	
	$.each(data_four, function(i, field){
		console.log("DATA ROUND TWO: " +field.name + ":" + field.value + " ");
  	});
	
	//sendRequest = false;
	if(sendRequest == true) {
        $.ajax({
            type: "POST",
            url: "php_form_test.php",
            data: data_four,
            success: function(data) {
                 alert('Data send:' + data);
            },
			error: function(data) {
					alert('Error:' + data);
			}
        });
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
	} else if (reps.length == 0) {
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
                 alert('Data send:' + data);
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