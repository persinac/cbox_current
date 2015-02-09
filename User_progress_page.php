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
else if ($_SESSION['MM_Admin'] == "1") {$link = "Admin_home_page.php";;} // COMMENT 
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Progress</title>
	<!-- Custom styles for this template -->
     <link href="dist/css/user_progress_page.css" rel="stylesheet">
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="dist/css/jquery.qtip.css" />
    
	<!-- Calendar stuff -->
	<link href='dist/fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='dist/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
    <link href="dist/css/jquery.datepick.css" rel="stylesheet">
	<link href="dist/css/navbar_extended.css" rel="stylesheet">
	
</head>


<body>
<div id="container">
<div id="rect_one"></div>
<div id="rect_two"></div>
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

<div id="navbar_sub"> 
  <ul id="navbar_sub_ul"> 
	<li id="cft_lnk" ><a href="#javascript: return null;" >CROSSFIT</a></li> 
	<li id="oly_lnk"><a href="#javascript: return null;" >OLYMPIC</a></li> 
	<li id="pwr_lnk"><a href="#javascript: return null;" >POWERLIFTING</a></li> 
	<li id="mis_lnk" ><a href="#javascript: return null;" >MISC</a></li> 
  </ul> 
</div>

<div id="sidebar">
	<ul id="sidebar_ul">

    </ul>
</div>

<div id="first_time">
	<input onclick="prDataModal();" type="button" id="pr_button" value="Add some PR's" />
	<input onclick="openNewDataModal();" type="button" id="new_data_but" value="New Open Data" />
</div>

	<div id="pr_modal" style="display:none;" title="New PR's">
		<div id="tab_container" class="tabs">
			<ul id="pr_tab_list" class="tab-links">
				<li class="active"><a href="#fnd">Foundamentals</a></li>
				<li><a href="#grl">Girls</a></li>
				<li><a href="#hro">Heroes</a></li>
				<li><a href="#oly">Olympic</a></li>
				<li><a href="#pwr">Powerlifting</a></li>
				<li><a href="#mis">Misc</a></li>
			</ul>
			<div id="pr-modal-body">
				<!-- Grab number of rows and place that many into here -->  
				<div id="tab-content" class="tab-content">
					<div id="fnd" class="tab active">
						<form id="pr_form_fnd" class="pr_">
							<div>
								<h4>Backsquat:</h4> 
								<p></p>
								Weight: <input type="text" name="backsquat_input" class="_weight" id="weight_0_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="backsquat_datepicker"/>
								<p></p>
								<h4>Frontsquat:</h4> 
								<p></p>
								Weight: <input type="text" name="frontsquat_input" class="_weight" id="weight_1_input"/>
								<p></p>
								Date Achieved:  <input type="text" name="date" class="_datepicker" id="frontsquat_datepicker"/> 
								<p></p>
								<h4>Overhead Squat:</h4> 
								<p></p>
								Weight: <input type="text" name="ohsquat_input" class="_weight" id="weight_2_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="ohsquat_datepicker"/> 
								<p></p>
								<h4>Deadlift:</h4> 
								<p></p>
								Weight: <input type="text" name="deadlift_input" class="_weight" id="weight_3_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="deadlift_datepicker"/> 
								<p></p>
								<h4>Sumo Deadlift Highpull:</h4> 
								<p></p>
								Weight: <input type="text" name="sdlhp_input" class="_weight" id="weight_4_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="sdlhp_datepicker"/> 
								<p></p>
								<h4>Power Clean:</h4> 
								<p></p>
								Weight: <input type="text" name="pc_input" class="_weight" id="weight_5_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="pc_datepicker"/> 
								<p></p>
								<h4>Overhead Press:</h4> 
								<p></p>
								Weight: <input type="text" name="ohp_input" class="_weight" id="weight_6_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="ohp_datepicker"/> 
								<p></p>
								<h4>Push Press:</h4> 
								<p></p>
								Weight: <input type="text" name="pp_input" class="_weight" id="weight_7_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="pp_datepicker"/> 
								<p></p>
								<h4>Push Jerk:</h4> 
								<p></p>
								Weight: <input type="text" name="pj_input" class="_weight" id="weight_8_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="pj_datepicker"/> 
							</div>
							<input type="button" onclick='submitPRs("fnd")' id="newPRButton" value="Submit"/>
						</form>
					</div>
					<div id="grl" class="tab">
						<form id="pr_form_grl" class="pr_">
							<div id="pr_grl_div">
							</div>
							<input type="button" onclick='submitPRs("grl")' id="newPRButton" value="Submit"/>
						</form>
					</div>
					<div id="hro" class="tab">
						<form id="pr_form_hro" class="pr_">
							<div id="pr_hro_div">
							</div>
							<input type="button" onclick='submitPRs("hro")' id="newPRButton" value="Submit"/>
						</form>
					</div>
					<div id="oly" class="tab">
						<form id="pr_form_oly" class="pr_">
							<div>
								<h4>Clean and Jerk:</h4> 
								<p></p>
								Weight: <input type="text" name="cj_input" class="_weight" id="weight_9_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="cj_datepicker"/> 
								<p></p>
								<h4>Snatch:</h4> 
								<p></p>
								Weight: <input type="text" name="snatch_input" class="_weight" id="weight_10_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="snatch_datepicker"/> 
							</div> 
							<input type="button" onclick='submitPRs("oly")' id="newPRButton" value="Submit"/>
						</form> 
					</div>
					<div id="pwr" class="tab">
						<form id="pr_form_pwr" class="pr_">
							<div>
								<h4>Bench:</h4> 
								<p></p>
								Weight: <input type="text" name="bench_input" class="_weight" id="weight_11_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="bench_datepicker"/> 
								<h4>Backsquat:</h4> 
								<p></p>
								Weight: <input type="text" name="backsquat_input" class="_weight" id="weight_0_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="backsquat_datepicker"/>
								<p></p>
								<h4>Deadlift:</h4> 
								<p></p>
								Weight: <input type="text" name="deadlift_input" class="_weight" id="weight_3_input"/>
								<p></p>
								Date Achieved: <input type="text" name="date" class="_datepicker" id="deadlift_datepicker"/>
							</div>
							<input type="button" onclick='submitPRs("pwr")' id="newPRButton" value="Submit"/>
						</form> 
					</div>
					<div id="mis" class="tab">
						<form id="pr_form_mis" class="pr_">
							<div>
							</div>
							<input type="button" onclick='submitPRs("mis")' id="newPRButton" value="Submit"/>
						</form> 
					</div>
				</div>				 
			</div>
		</div>
	</div>
	<div id="new_open_data" style="display:none;">
		<div id="tab_container" class="tabs">
			<ul id="tab_list" class="tab-links">
				<li class="active"><a href="#tab1">2012</a></li>
				<li><a href="#tab2">2013</a></li>
				<li><a href="#tab3">2014</a></li>
			</ul>
		 
			<div id="tab-content" class="tab-content">
				<div id="tab1" class="tab active">
				<form id="2012_form">
					<div id="121_container" class="descrip">
						<h3>WOD #1</h3>
						<p>Complete as many reps as possible in 7 minutes of: Burpees </p>
						Score: <input type="text" name="121input" id="121input" class="open_score"/>
						Date: <input type="text" name="121date" id="121date" class="open_date"/>
					</div>
					<div id="122_container" class="descrip">
						<h3>WOD #2</h3>
						<p>Snatch ladder, complete the following in 10min: 75# snatch 30 reps, 135# snatch 30 reps, 165# snatch 30 reps, 210# snatch as many as possible </p>
						Score: <input type="text" name="122input" id="122input" class="open_score"/>
						Date: <input type="text" name="122date" id="122date" class="open_date"/>
					</div>
					<div id="123_container" class="descrip">
						<h3>WOD #3</h3>
						<p>Complete as many reps as possible in 18 minutes of: 15 box jumps 24" box, 115# push press 12 reps, 9 toes to bar</p>
						Score: <input type="text" name="123input" id="123input" class="open_score"/>
						Date: <input type="text" name="123date" id="123date" class="open_date"/>
					</div>
					<div id="124_container" class="descrip">
						<h3>WOD #4</h3>
						<p>Complete as many reps as possible in 12 minutes of: 150 wall balls (20lbs, 10' target), 90 double unders, 30 muscle ups</p>
						Score: <input type="text" name="124input" id="124input" class="open_score"/>
						Date: <input type="text" name="124date" id="124date" class="open_date"/>
					</div>
					<div id="125_container" class="descrip">
						<h3>WOD #5</h3>
						<p>Complete as many reps as possible in 7 minutes of: 3 chest to bar pull ups, 3 thrusters @ 100#, 6 C2B pull ups, 6 Thrusters, 9 and 9, 12 and 12, ...</p>
						Score: <input type="text" name="125input" id="125input" class="open_score"/>
						Date: <input type="text" name="125date" id="125date" class="open_date"/>
					</div>
					<input type="button" onclick='submitNewOpenData("2012")' id="newOpenDataButton" value="Submit" />
				</form>
				</div>
		 
				<div id="tab2" class="tab">
				<form id="2013_form">
					<div id="131_container">
						<h3>WOD #1</h3>
						<p></p>
						Score: <input type="text" name="131input" id="131input" class="open_score"/>
						Date: <input type="text" name="131date" id="131date" class="open_date"/>
					</div>
					<div id="132_container">
						<h3>WOD #2</h3>
						<p></p>
						Score: <input type="text" name="132input" id="132input" class="open_score"/>
						Date: <input type="text" name="132date" id="132date" class="open_date"/>
					</div>
					<div id="133_container">
						<h3>WOD #3</h3>
						<p></p>
						Score: <input type="text" name="133input" id="133input" class="open_score"/>
						Date: <input type="text" name="133date" id="133date" class="open_date"/>
					</div>
					<div id="134_container">
						<h3>WOD #4</h3>
						<p></p>
						Score: <input type="text" name="134input" id="134input" class="open_score"/>
						Date: <input type="text" name="134date" id="134date" class="open_date"/>
					</div>
					<div id="135_container">
						<h3>WOD #5</h3>
						<p></p>
						Score: <input type="text" name="135input" id="135input" class="open_score"/>
						Date: <input type="text" name="135date" id="135date" class="open_date"/>
					</div>
					<input type="button" onclick='submitNewOpenData("2013")' id="newOpenDataButton" value="Submit"/>
				</form>
				</div>
		 
				<div id="tab3" class="tab">
					<form id="2014_form">
					<div id="141_container">
						<h3>WOD #1</h3>
						<p></p>
						Score: <input type="text" name="141input" id="141input" class="open_score"/>
						Date: <input type="text" name="141date" id="141date" class="open_date"/>
					</div>
					<div id="142_container">
						<h3>WOD #2</h3>
						<p></p>
						Score: <input type="text" name="142input" id="142input" class="open_score"/>
						Date: <input type="text" name="142date" id="142date" class="open_date"/>
					</div>
					<div id="143_container">
						<h3>WOD #3</h3>
						<p></p>
						Score: <input type="text" name="143input" id="143input" class="open_score"/>
						Date: <input type="text" name="143date" id="143date" class="open_date"/>
					</div>
					<div id="144_container">
						<h3>WOD #4</h3>
						<p></p>
						Score: <input type="text" name="144input" id="144input" class="open_score"/>
						Date: <input type="text" name="144date" id="144date" class="open_date"/>
					</div>
					<div id="145_container">
						<h3>WOD #5</h3>
						<p></p>
						Score: <input type="text" name="145input" id="145input" class="open_score"/>
						Date: <input type="text" name="145date" id="145date" class="open_date"/>
					</div>
					<input type="button" onclick='submitNewOpenData("2014")' id="newOpenDataButton" value="Submit"/>
					</form>
				</div>
			</div>
			<p></p>
			<div id="division_selector_container" class="tabs">
				<h4>Please select a division:</h4>
				<select id="division_selector" name="division_selector" class="selector">
					<option value="none"> - </option>
					<option value="today">Individual Men</option>
					<option value="search">Individual Women</option>
					<option value="compare">Team</option>
					<option value="today">Masters Men 40-44</option>
					<option value="search">Masters Women 40-44</option>
					<option value="compare">Masters Men 45-49</option>
					<option value="today">Masters Women 45-49</option>
					<option value="search">Masters Men 50-54</option>
					<option value="compare">Masters Women 50-54</option>
					<option value="today">Masters Men 55-59</option>
					<option value="search">Masters Women 55-59</option>
					<option value="compare">Masters Men 60+</option>
					<option value="compare">Masters Women 60+</option>
				</select>
			</div>
		</div>
	</div>


<div id="image_div">
<img src="images/progress_page/PROGRESS_PAGE_CFAPP_template.jpg" width="1224" height="792" alt=""/>
</div>

<div id="data_container">
	<div id="cft_foundamentals_sec1_div">
		<table class="cft_foundamentals_sec1">
            <tbody class="sec1_body">
            	<tr class="sec1_data">
                </tr>	
            </tbody>
		</table>
     </div>
     <div id="cft_foundamentals_sec2_div">
		<table class="cft_foundamentals_sec2">
            <tbody class="sec2_body">
            </tbody>
        </table>
     </div>
	 <div id=\"cft_foundamentals_sec3_div">
		<table class="cft_foundamentals_sec3">
            <tbody class="sec3_body">
            </tbody>
        </table>
     </div>
 
</div> <!-- END data_container -->

</div> <!-- END div_container -->

<!-- JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<!-- These must be in THIS ORDER! DO NOT FUCK WITH -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="dist/js/heroes.js"></script> 
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

var jsonHeroData;
$(document).ready(function() {
	addDivSeparators();
	$.getJSON( "../../CRUD/progress/cf_heroes.json", function(jsonData) {
		console.log(jsonData);
		jsonHeroData = jsonData;
	});
	$("#navbar_sub_ul li").click(function() {
		//event.preventDefault();
		var id = jQuery(this).attr("id");
		//empty the tables
		$('.sec1_body').empty();
		$('.sec2_body').empty();
		$('.sec3_body').empty();
		$('#cft_wod_div').empty();
		isActive(id);
		setSideBar(id);
		addDivSeparators();
		if(id=="oly_lnk" || id=="pwr_lnk" || id=="mis_lnk") {
			getData(id);
		}
	});	
});

$("#main_nav_bar li").click(function() {
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

$("#dd_nav_bar li").click(function() {
	var id = jQuery(this).attr("id");
	if(id=="calendar") {
		window.open("http://cboxbeta.com/challenge.php#uid="+<?echo $_SESSION['MM_UserID'];?>+"");
	}
});
	
$(function(){
	$("button#submit").click(function() {
		//alert("HELLO");
		submitPRs();
		$('#pr_modal').modal('hide');
	});
});

var myVar;

function submitPRs(value) {
	var datastring;
	var dataToSend = new Array();
	if(value == "fnd") {
		datastring = $("#pr_form_fnd").serializeArray();
	} else if(value == "grl") {
		datastring = $("#pr_form_grl").serializeArray();
	} else if(value == "hro") {
		datastring = $("#pr_form_hro").serializeArray();
	} else if(value == "oly") {
		datastring = $("#pr_form_oly").serializeArray();
	} else if(value == "pwr") {
		datastring = $("#pr_form_pwr").serializeArray();
	} else if(value == "mis") {
		datastring = $("#pr_form_mis").serializeArray();
	}

	var sendRequest = true;

	/*
	$.each(datastring, function(i, field) {
		console.log("DATASTRING: " + field.name + " : " + field.value);
	});
	*/
	if(value == "grl") {
		var timeItems = $("._time");
		var idOfMvmnt = "";
		var dateID = "";
		var t_date = "";
		var t_id = "";
		var score = "";
		var time = "";
		var reps = "";
		var wod_type = "";
		timeItems.each(function(i, field) {
			if($(this).hasClass('input_error') == true) {
				console.log("HAS ERROR");
				sendRequest = false;
			} else if($(this).hasClass('input_error') == false	) {
				if(field.value.length > 0) {
					console.log("NO ERROR AND VALUE > 0");
					t_id = field.name.substring(0,field.name.indexOf('_'));
					t_id = field.name.substring(field.name.indexOf('_')+1);
					t_id = t_id.substring(0, field.name.indexOf('_')-1);
					console.log(t_id);
					idOfMvmnt = "grl_" + t_id;
					dateID = "grl_" + t_id + "_datepicker";
					t_date = $("#"+dateID+"").val();
					score = $("#score_"+t_id+"_input").val();
					console.log(idOfMvmnt + ", " + dateID + ", " +t_date+", "+score);
					//var result = t_date.replace(/-/g, "");
					/* Need to implement handle for Hero, Misc when ready */
					if(t_id == "04" || t_id == "14" || t_id == "19" || t_id == "20") {
						time = "-";
						reps = score;
						wod_type = "a";
					} else {
						time = score;
						reps = "-";
						wod_type = "r";
					}
					dataToSend.push({id:idOfMvmnt, 
						name: "-",
						value: 0,
						time: time,
						dateval:t_date,
						reps: reps,
						wt: wod_type});
				}
			}
		});
		//sendRequest = false;
		console.log("FIN GRL");
	} else if(value == "hro") {
		//alert("Coming soon!");
		var timeItems = $("._time");
		var idOfMvmnt = "";
		var dateID = "";
		var t_date = "";
		var t_id = "";
		var score = "";
		var time = "";
		var reps = "";
		var wod_type = "";
		var chkBox = "";
		timeItems.each(function(i, field) {
			if($(this).hasClass('input_error') == true) {
				console.log("HAS ERROR");
				sendRequest = false;
			} else if($(this).hasClass('input_error') == false	) {
				if(field.value.length > 0) {
					console.log("NO ERROR AND VALUE > 0");
					t_id = field.name.substring(0,field.name.indexOf('_'));
					console.log("FIRST: "+t_id);
					t_id = field.name.substring(field.name.indexOf('_')+1);
					console.log("SECOND: "+t_id);
					t_id = t_id.substring(0, t_id.indexOf('_'));
					console.log("LAST:"+t_id);
					chkBox = $("#level_"+t_id+'').is(":checked");
					idOfMvmnt = "hro_" + t_id;
					dateID = "hro_" + t_id + "_datepicker";
					t_date = $("#"+dateID+"").val();
					score = $("#score_"+t_id+"_input").val();
					console.log(idOfMvmnt + ", " + dateID + ", " +t_date+", "+score+", chkbox: "+chkBox);
					
					dataToSend.push({id:t_id,
						value: score,
						dateval:t_date,
						chkbox: chkBox});
				}
			}
		});
		console.log("FIN HRO");
	} else {
		var weightItems = $("._weight");
		var idOfMvmnt = "";
		var dateID = "";
		var t_date = "";
		weightItems.each(function(i, field) {
			if($(this).hasClass('input_error') == true) {
				console.log("HAS ERROR");
				sendRequest = false;
			} else if($(this).hasClass('input_error') == false	) {
				if(field.value.length > 0) {
					console.log("NO ERROR AND VALUE > 0");
					idOfMvmnt = idOfMovement(field.name);
					dateID = field.name.substring(0,field.name.indexOf('_')) + "_datepicker";
					t_date = $("#"+dateID+"").val();
					//var result = t_date.replace(/-/g, "");
					/* Need to implement handle for  Misc when ready */
					dataToSend.push({id:idOfMvmnt, 
						name: "-",
						value: field.value,
						time: "-",
						dateval:t_date,
						reps: "-",
						wt: "-"});
				}
			}
		});
	}
	
	var sendCount = 0;
	console.log(sendRequest);
	if(sendRequest === false) {
		while(dataToSend.length > 0) {
			dataToSend.pop();
		}
		alert("Please fix errors before submitting!");
	} else {
		if(dataToSend.length > -1) {
			for(var i = 0; i < dataToSend.length; i++) {
				console.log("dataToSend["+i+"]: "+dataToSend[i].id + " : " +dataToSend[i].value+ " : "+dataToSend[i].dateval);
				if(value == "hro") {
					$.ajax({
						type: "POST",
						url: "CRUD/progress/addUserHeroPR.php",
						data: { "id" : dataToSend[i].id,
								"score" : dataToSend[i].value,
								"date" : dataToSend[i].dateval,
								"level" : dataToSend[i].chkbox
							},
						success: function(data) {
							 console.log('Data send:' + data);
							 sendCount++;
							 confirmOutput(sendCount, dataToSend.length, data);
						}
					});
				} else { 
					$.ajax({
						type: "POST",
						url: "addUserFoundPR.php",
						data: { "mvmnt_id" : dataToSend[i].id,
							"name" : dataToSend[i].name,
								"weight": dataToSend[i].value, 
								"time" : dataToSend[i].time,
								"date" : dataToSend[i].dateval,
								"reps" : dataToSend[i].reps,
								"wod_type" : dataToSend[i].wt
							},
						success: function(data) {
							 console.log('Data send:' + data);
							 sendCount++;
							 confirmOutput(sendCount, dataToSend.length, data);
						}
					});
					if(dataToSend[i].id == "cft_01") {
						$.ajax({
							type: "POST",
							url: "addUserFoundPR.php",
							data: { "mvmnt_id" : "pwr_01",
								"name" : dataToSend[i].name,
								"weight": dataToSend[i].value, 
								"time" : dataToSend[i].time,
								"date" : dataToSend[i].dateval,
								"reps" : dataToSend[i].reps,
								"wod_type" : dataToSend[i].wt
								},
							success: function(data) {
								 console.log('Data send:' + data);
								}
							});
					} else if(dataToSend[i].id == "cft_04") {
						$.ajax({
							type: "POST",
							url: "addUserFoundPR.php",
							data: { "mvmnt_id" : "pwr_03",
								"name" : "-",
								"weight": dataToSend[i].value, 
								"time" : "-",
								"date" : dataToSend[i].dateval,
								"reps" : "-",
								"wod_type" : "-"
								},
							success: function(data) {
							 console.log('Data send:' + data);
							}
						});
					}
				}
			}
		}
	}
}
		
$("#sidebar_ul").on("click", "li", function() {
	//event.preventDefault();
	var id = jQuery(this).attr("id");
	//alert("SideBar click, ID: " + id);
	if(id == "wod" || id == "str")
	{
		remDivSeparators();	
	}
	else
	{
		addDivSeparators();
	}
	getData(id);
});	

function addDivSeparators()
{
	if($('#rect_one').hasClass('rect_divider_one') == true) {
		//do nothing
	}
	else if($('#rect_one').hasClass('rect_divider_one') == false) {
		//add separators
		$('#rect_one').addClass('rect_divider_one');
		$('#rect_two').addClass('rect_divider_two');
	}

}

function remDivSeparators()
{
	if($('#rect_one').hasClass('rect_divider_one') == true) {
		$('#rect_one').removeClass('rect_divider_one');
		$('#rect_two').removeClass('rect_divider_two');
	}
	else if($('#rect_one').hasClass('rect_divider_one') == false) {
		//do nothing
	}

}

function isActive(anID) {
	var listItems = $("#navbar_sub_ul li");
	listItems.each(function(i, li)
	{
    	$(li).removeClass('active_bar');
  	});
  setActive(anID);
  
}

function setActive(anID) {
	$('#'+anID).addClass('active_bar');
	$('#'+anID+" a").addClass('a');
	$('#'+anID).removeClass('active');
}

function changeTextColorOfTables() {
 $('tr:odd').addClass('alt');
}

  function getData(movement_id) 
  {
	//NEW AJAX URL: CRUD/progress/getUserBenchmarksOnMvmntID.php
	  var html = "";
	if(movement_id == "cft")
	{
		//create html here:
		html += "<div id=\"cft_foundamentals_sec1_div\">";
		html +=" <table class=\"cft_foundamentals_sec1\">";
		html +=" <tbody class=\"sec1_body\">"; 	
        html +=" </tbody>";
		html +=" </table></div><div id=\"cft_foundamentals_sec2_div\">";
		html +=" <table class=\"cft_foundamentals_sec2\">";
		html +=" <tbody class=\"sec2_body\"></tbody></table></div>";
		html +=" <div id=\"cft_foundamentals_sec3_div\">";
		html +=" <table class=\"cft_foundamentals_sec3\">";
		html +=" <tbody class=\"sec3_body\">";
		html +=" </tbody></table></div>";
    
		$('#data_container').html(html);
		
		$.ajax({ 
		  type:"POST",                                     
		  url: "php_json_test.php",           
		  data: { dataString: movement_id },
		  dataType: "json",
		  success: function(response)
		  {
			loadCFTData(response);
		  } 
		});
	}
	else if(movement_id =="wod")
	{
		$('.sec1_body').empty();
		$('.sec2_body').empty();
		$('.sec3_body').empty();
		
		
		html += "<div id=\"cft_wod_div\">";
		html += "<table class=\"cft_wods altbackground\">";
		html += "<tbody class=\"wods_table_body\">";
		html += "</tbody>";
		html += "</table>";
		html += "</div>";

		$('#data_container').html(html);
		
		//now load data into table
		$.ajax({ 
			  type:"POST",                                     
			  url: "getUserWODs.php",      
			  dataType: "json",   
			  success: function(response)
			  {
				loadCFTWODData(response);
			  } 
		});
	}
	else if(movement_id =="str")
	{
		$('.sec1_body').empty();
		$('.sec2_body').empty();
		$('.sec3_body').empty();
		
		
		html += "<div id=\"cft_wod_div\">";
		html += "<table class=\"cft_wods altbackground\">";
		html += "<tbody class=\"wods_table_body\">";
		html += "</tbody>";
		html += "</table>";
		html += "</div>";

		$('#data_container').html(html);

		$.ajax({ 
			  type:"GET",                                     
			  url: "getUserStrength.php", 
			  dataType: "html",   
			  success: function(response) 
			  {
				console.log("STRENGTH RESPONSE: "+response);
				if(response.substring(0,1) == "1") {
					console.log("NO STRENGTH DATA");
					alert("You haven't input any strength workouts or data yet! Go to the WOD page and click 'Add Strength'!");
				} else {
					$('.wods_table_body').html(response);
				}
			  } 
		});
	} else if(movement_id =="cus") {
		$('.sec1_body').empty();
		$('.sec2_body').empty();
		$('.sec3_body').empty();
		remDivSeparators();
		html += "<div id=\"cft_wod_div\">";
		html += "<table class=\"cft_wods altbackground\">";
		html += "<tbody class=\"wods_table_body\">";
		html += "</tbody>";
		html += "</table>";
		html += "</div>";

		$('#data_container').html(html);
		
		$.ajax({ 
			  type:"POST",                                     
			  url: "getUserCustomWODs.php", 
			  data: { progress : "p" },
			  dataType: "html",   
			  success: function(response) 
			  {
				console.log("CUSTOM WOD RESPONSE: "+response);
				$('.wods_table_body').html(response);
			  } 
		});
	} else if(movement_id == "oly_lnk") {
		
		//create html here:
		html += "<div id=\"cft_foundamentals_sec1_div\">";
		html +=" <table class=\"cft_foundamentals_sec1\">";
		html +=" <tbody class=\"sec1_body\">"; 	
        html +=" </tbody>";
		html +=" </table></div><div id=\"cft_foundamentals_sec2_div\">";
		html +=" <table class=\"cft_foundamentals_sec2\">";
		html +=" <tbody class=\"sec2_body\"></tbody></table></div>";
		html +=" <div id=\"cft_foundamentals_sec3_div\">";
		html +=" <table class=\"cft_foundamentals_sec3\">";
		html +=" <tbody class=\"sec3_body\">";
		html +=" </tbody></table></div>";
    
		$('#data_container').html(html);
		
		$.ajax({ 
		  type:"POST",                                     
		  url: "php_json_test.php",          
		  data: { dataString: "oly" }, 
		  dataType: "json", 
		  success: function(response)
		  {
			loadOlyData(response);
		  } 
		});
	} else if(movement_id == "pwr_lnk") {
		
		//create html here:
		html += "<div id=\"cft_foundamentals_sec1_div\">";
		html +=" <table class=\"cft_foundamentals_sec1\">";
		html +=" <tbody class=\"sec1_body\">"; 	
        html +=" </tbody>";
		html +=" </table></div><div id=\"cft_foundamentals_sec2_div\">";
		html +=" <table class=\"cft_foundamentals_sec2\">";
		html +=" <tbody class=\"sec2_body\"></tbody></table></div>";
		html +=" <div id=\"cft_foundamentals_sec3_div\">";
		html +=" <table class=\"cft_foundamentals_sec3\">";
		html +=" <tbody class=\"sec3_body\">";
		html +=" </tbody></table></div>";
    
		$('#data_container').html(html);
		
		$.ajax({ 
		  type:"POST",                                     
		  url: "php_json_test.php",      
		  data: { dataString: "pwr" },
		  dataType: "json",            
		  success: function(response) 
		  {
			loadPWRData(response);
		  } 
		});
	}
	else if(movement_id == "mis_lnk") {
		
		//create html here:
		html += "<div id=\"cft_foundamentals_sec1_div\">";
		html +=" <table class=\"cft_foundamentals_sec1\">";
		html +=" <tbody class=\"sec1_body\">"; 	
        html +=" </tbody>";
		html +=" </table></div><div id=\"cft_foundamentals_sec2_div\">";
		html +=" <table class=\"cft_foundamentals_sec2\">";
		html +=" <tbody class=\"sec2_body\"></tbody></table></div>";
		html +=" <div id=\"cft_foundamentals_sec3_div\">";
		html +=" <table class=\"cft_foundamentals_sec3\">";
		html +=" <tbody class=\"sec3_body\">";
		html +=" </tbody></table></div>";
    
		$('#data_container').html(html);
		
		$.ajax({ 
		  type:"POST",                                     
		  url: "php_json_test.php",         
		  data: { dataString: "mis" },
		  dataType: "json", 
		  success: function(response)
		  {
			loadCFTData(response);
		  } 
		});
	} else if(movement_id == "grl")
	{
		//create html here:
		html += "<div id=\"cft_foundamentals_sec1_div\">";
		html +=" <table class=\"cft_foundamentals_sec1\">";
		html +=" <tbody class=\"sec1_body\">"; 	
        html +=" </tbody>";
		html +=" </table></div><div id=\"cft_foundamentals_sec2_div\">";
		html +=" <table class=\"cft_foundamentals_sec2\">";
		html +=" <tbody class=\"sec2_body\"></tbody></table></div>";
		html +=" <div id=\"cft_foundamentals_sec3_div\">";
		html +=" <table class=\"cft_foundamentals_sec3\">";
		html +=" <tbody class=\"sec3_body\">";
		html +=" </tbody></table></div>";
    
		$('#data_container').html(html);
		
		$.ajax({ 
		  type:"POST",                                     
		  url: "php_json_test.php",        
		  data: { dataString: movement_id },
		  dataType: "json",      
		  success: function(response)
		  {
				console.log(response);
				loadGRLData(response);
		  } 
		});
	} else if(movement_id == "hro") {
		
		html += "<div id=\"cft_foundamentals_sec1_div\">";
		html +=" <table class=\"cft_foundamentals_sec1\">";
		html +=" <tbody class=\"sec1_body\">"; 	
        html +=" </tbody>";
		html +=" </table></div><div id=\"cft_foundamentals_sec2_div\">";
		html +=" <table class=\"cft_foundamentals_sec2\">";
		html +=" <tbody class=\"sec2_body\"></tbody></table></div>";
		html +=" <div id=\"cft_foundamentals_sec3_div\">";
		html +=" <table class=\"cft_foundamentals_sec3\">";
		html +=" <tbody class=\"sec3_body\">";
		html +=" </tbody></table></div>";
    
		$('#data_container').html(html);

		$.ajax({ 
		  type:"POST",                                     
		  url: "../../CRUD/progress/getUserBenchmarksOnMvmntID.php",        
		  data: { dataString: movement_id },
		  dataType: "json",      
		  success: function(response)
		  {
				console.log(response);
				loadHROData(response);
		  } 
		});
		
	}
	return false;
  } 
  
  function loadCFTData(data)
  {
		var html_sec1 = "";
		var html_sec2 = "";
		var html_sec3 = "";
		var sec1_classID = "sec1_data"; 
		var sec2_classID = "sec2_data";
		var sec3_classID = "sec3_data";
		var w = "w_";             //get id
		var vname;           //get name
		for(var i = 0; i < data.length; i++) {
			vname = nameOfMovement(data[i].mvmnt_id);
			if(i <= 2) {
					html_sec1 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			} else if (i > 2 && i <= 5) {
				html_sec2 += "<tr class="+sec2_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			}
			else {
				html_sec3 += "<tr class="+sec3_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			}
		}
		//Update html content
		$('.sec1_body').empty();
		$('.sec1_body').html(html_sec1);
		$('.sec2_body').empty();
		$('.sec2_body').html(html_sec2);
		$('.sec3_body').empty();
		$('.sec3_body').html(html_sec3);
		
		changeTextColorOfTables();
  }
  
  function loadCFTWODData(data)
  {
		var html_sec1 = "";
		var sec1_classID = "cftwod_sec1_data"; 
		var w = "w_";             //get id
		var dow = "";
		var wodname;
		var lvl_perf = "";
		var type_of_wod = "";
		var descrip = "";
		var time_completed = "";
		var rounds_completed = "";
		var strength = ""; //concat with str.movement and str.description
		var post_wod = ""; //concat with p.movement and p.description
		for(var i = 0; i < data.length; i++) {
			dow = data[i].date_of_wod;
			wodname = data[i].WOD_Name;
			lvl_perf = data[i].level_perf;
			type_of_wod = data[i].type_of_wod;
			descrip = data[i].Description;
			if(type_of_wod == "RFT") {
				rounds_completed = data[i].rounds_compl + " rounds";
			} else if (type_of_wod == "AMRAP" || type_of_wod == "ME" || type_of_wod == "TABATTA" ) {
				rounds_completed = data[i].rounds_compl + " reps";
			} else if (type_of_wod == "MIXED") {
				rounds_completed = data[i].mixed_score.substring(data[i].mixed_score.indexOf("Final")+6) + " reps";
			}
			time_completed = data[i].time_comp;
			if(data[i].str_mov != "") {
				strength = data[i].str_mov +" "+ data[i].str_des
			}
			
			html_sec1 += "<tr class="+sec1_classID+">";
			html_sec1 += "<td>"+dow+"</td>";
			html_sec1 +="<td>"+wodname+"</td>";
			html_sec1 +="<td>"+lvl_perf+"</td>";
			html_sec1 +="<td>"+type_of_wod+"</td>";
			html_sec1 +="<td class=\"cftwod_descrip\">"+descrip+"</td>";
			html_sec1 +="<td>"+time_completed+"</td>";
			html_sec1 +="<td>"+rounds_completed+"</td>";
			if(strength.length > 2) {
				html_sec1 +="<td>"+strength+"</td>";
			} else {
				html_sec1 +="<td>No Strength</td>";
			}
			
			html_sec1 += "</tr>";
		}
		//Update html content
		$('.wods_table_body').empty();
		$('.wods_table_body').html(html_sec1);
		
		//changeTextColorOfTables();
  }
  
  
  function loadOlyData(data)
  {
		var html_sec1 = "";
		var html_sec2 = "";
		var html_sec3 = "";
		var sec1_classID = "sec1_data"; 
		var sec2_classID = "sec2_data";
		var sec3_classID = "sec3_data";
		var w = "w_";             //get id
		var vname;           //get name
		for(var i = 0; i < data.length; i++) {
			vname = nameOfMovement(data[i].mvmnt_id);
			if(i < 1) {
					html_sec1 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			} else if (i > 0 && i <= 1) {
				html_sec2 += "<tr class="+sec2_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			}
			else {
				html_sec3 += "<tr class="+sec3_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			}
		}
		//Update html content
		$('.sec1_body').empty();
		$('.sec1_body').html(html_sec1);
		$('.sec2_body').empty();
		$('.sec2_body').html(html_sec2);
		$('.sec3_body').empty();
		$('.sec3_body').html(html_sec3);
		
		changeTextColorOfTables();
  }
  
  function loadPWRData(data)
  {
		var html_sec1 = "";
		var html_sec2 = "";
		var html_sec3 = "";
		var sec1_classID = "sec1_data"; 
		var sec2_classID = "sec2_data";
		var sec3_classID = "sec3_data";
		var w = "w_";             //get id
		var vname;           //get name
		for(var i = 0; i < data.length; i++) {
			vname = nameOfMovement(data[i].mvmnt_id);
			if(i < 1) {
					html_sec1 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			} else if (i > 0 && i <= 1) {
				html_sec2 += "<tr class="+sec2_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			}
			else {
				html_sec3 += "<tr class="+sec3_classID+"><td>"+
						vname+"</td><td>"+data[i].weight+"</td></tr>";
			}
		}
		//Update html content
		$('.sec1_body').empty();
		$('.sec1_body').html(html_sec1);
		$('.sec2_body').empty();
		$('.sec2_body').html(html_sec2);
		$('.sec3_body').empty();
		$('.sec3_body').html(html_sec3);
		
		changeTextColorOfTables();
  }
  
  
  function loadGRLData(data)
  {
	  console.log("GRL DATA ENTRY");
		var html_sec1 = "";
		var html_sec2 = "";
		var html_sec3 = "";
		var sec1_classID = "sec1_data"; 
		var sec2_classID = "sec2_data";
		var sec3_classID = "sec3_data";
		var w = "w_";             //get id
		var vname;           //get name
		var time = "--:--";
		for(var i = 0; i < data.length; i++) {
			vname = nameOfGirl(data[i].mvmnt_id);
			console.log("name: " + vname + ", score: " + data[i].time);
			if(i <= 6) {
				if(data[i].wod_type == 'r') {
					if(data[i].time == '99:99') {
						data[i].time = "--:--";
					}
					time = timeFormatter(data[i].time);
					console.log("FORMATTED TIME: " + time);
					html_sec1 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+time+"</td></tr>";
				}
				else {
					html_sec1 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].reps+"</td></tr>";
				}
			} else if (i > 6 && i <= 13) {
				if(data[i].wod_type == 'r') {
					if(data[i].time == '99:99') {
						data[i].time = "--:--";
					}
					time = timeFormatter(data[i].time);
					console.log("FORMATTED TIME: " + time);
					html_sec2 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+time+"</td></tr>";
				}
				else {
					html_sec2 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].reps+"</td></tr>";
				}
			}
			else {
				if(data[i].wod_type == 'r') {
					if(data[i].time == '99:99') {
						data[i].time = "--:--";
					}
					time = timeFormatter(data[i].time);
					console.log("FORMATTED TIME: " + time);
					html_sec3 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+time+"</td></tr>";
				}
				else {
					html_sec3 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].reps+"</td></tr>";
				}
			}
		}
		//Update html content
		$('.sec1_body').empty();
		$('.sec1_body').html(html_sec1);
		$('.sec2_body').empty();
		$('.sec2_body').html(html_sec2);
		$('.sec3_body').empty();
		$('.sec3_body').html(html_sec3);
		
		changeTextColorOfTables();
  }
  
  function loadHROData(data)
  {
		console.log("HRO DATA ENTRY");
		var html_sec1 = "";
		var html_sec2 = "";
		var html_sec3 = "";
		var sec1_classID = "sec1_data"; 
		var sec2_classID = "sec2_data";
		var sec3_classID = "sec3_data";
		var vname;
		var score = "";
		var extraLength = data.length % 3;
		var lengthOfSection = 0;
		lengthOfSection = (data.length - extraLength) / 3;
		for(var i = 0; i < data.length-1; i++) {
			
			vname = nameOfHero((data[i].hero_id), jsonHeroData);
		
			console.log("name: " + vname + ", score: " + data[i].score + ", level: " +data[i].level);
			
			if(data[i].score == '99:99') {
				data[i].score = "--:--";
			}
			if(data[i].level.trim() == 's') {
				data[i].score += '*';
			}
			
			if(i <= lengthOfSection) {
				html_sec1 += "<tr class="+sec1_classID+"><td>"+
					vname+"</td><td>"+data[i].score+"</td></tr>";
			} else if (i > lengthOfSection && i <= (lengthOfSection*2)) {
				html_sec2 += "<tr class="+sec1_classID+"><td>"+
					vname+"</td><td>"+data[i].score+"</td></tr>";
			}
			else {
				html_sec3 += "<tr class="+sec1_classID+"><td>"+
					vname+"</td><td>"+data[i].score+"</td></tr>";
			}
		}
		//Update html content
		$('.sec1_body').empty();
		$('.sec1_body').html(html_sec1);
		$('.sec2_body').empty();
		$('.sec2_body').html(html_sec2);
		$('.sec3_body').empty();
		$('.sec3_body').html(html_sec3);
		
		changeTextColorOfTables();
  }
  
  
  
  function setSideBar(id)
  {
	  var html = "";
	  $('#sidebar_ul').empty();
	  if(id == "cft_lnk")
	  {	
	  	html += "<li id=\"wod\" ><a href=\"javascript: return null;\">WODS</a></li>";
		html += "<li id=\"cft\" ><a href=\"javascript: return null;\">FUNDAMENTALS</a></li>";
		html += "<li id=\"str\" ><a href=\"javascript: return null;\">STRENGTH</a></li>";
		html += "<li id=\"grl\" ><a href=\"javascript: return null;\">THE GIRLS</a></li>";
		html += "<li id=\"hro\" ><a href=\"javascript: return null;\">HEROES</a></li>";
		html += "<li id=\"cus\" ><a href=\"javascript: return null;\">CUSTOM WODS</a></li>";
	  }
	  $('#sidebar_ul').html(html);
  }
  

function nameOfMovement(movement_id)
{ 
 	var value = "Unknown Movement"; 
	if (movement_id == "cft_01") { 
	  value = "Back Squat"; 
	} 
	else if (movement_id == "cft_02") { 
	  value = "Front Squat"; 
	} 
	else if (movement_id == "cft_03") { 
	  value = "Overhead Squat"; 
	}
	else if (movement_id == "cft_04") { 
	  value = "Deadlift"; 
	}
	else if (movement_id == "cft_05") { 
	  value = "SDLHP"; 
	}
	else if (movement_id == "cft_06") { 
	  value = "Power Clean"; 
	}
	else if (movement_id == "cft_07") { 
	  value = "Overhead Press"; 
	}
	else if (movement_id == "cft_08") { 
	  value = "Push Press"; 
	}
	else if (movement_id == "cft_09") { 
	  value = "Push Jerk"; 
	} 
	else if (movement_id == "oly_01") { 
	  value = "Clean & Jerk"; 
	}
	else if (movement_id == "oly_02") { 
	  value = "Snatch"; 
	}
	else if (movement_id == "pwr_01") { 
	  value = "Squat"; 
	}
	else if (movement_id == "pwr_02") { 
	  value = "Bench"; 
	}
	else if (movement_id == "pwr_03") { 
	  value = "Deadlift"; 
	}
  return value; 
}

function idOfMovement(movement_name)
{
 	var value = "Unknown Movement"; 
	if (movement_name == "backsquat_input") { 
	  value = "cft_01"; 
	} 
	else if (movement_name == "frontsquat_input") { 
	  value = "cft_02"; 
	} 
	else if (movement_name == "ohsquat_input") { 
	  value = "cft_03"; 
	}
	else if (movement_name == "deadlift_input") { 
	  value = "cft_04"; 
	}
	else if (movement_name == "sdlhp_input") { 
	  value = "cft_05"; 
	}
	else if (movement_name == "pc_input") { 
	  value = "cft_06"; 
	}
	else if (movement_name == "ohp_input") { 
	  value = "cft_07"; 
	}
	else if (movement_name == "pp_input") { 
	  value = "cft_08"; 
	}
	else if (movement_name == "pj_input") { 
	  value = "cft_09"; 
	} 
	else if (movement_name == "cj_input") { 
	  value = "oly_01"; 
	}
	else if (movement_name == "snatch_input") { 
	  value = "oly_02"; 
	}
	else if (movement_name == "bench_input") { 
	  value = "pwr_02"; 
	}
  return value; 
}

function nameOfGirl(movement_id)
{ 
 	var value = "Unknown Movement"; 
	if (movement_id == "grl_01") { 
	  value = "Angie"; 
	} 
	else if (movement_id == "grl_02") { 
	  value = "Barbara"; 
	} 
	else if (movement_id == "grl_03") { 
	  value = "Chelsea"; 
	}
	else if (movement_id == "grl_04") { 
	  value = "Cindy"; 
	}
	else if (movement_id == "grl_05") { 
	  value = "Diane"; 
	}
	else if (movement_id == "grl_06") { 
	  value = "Elizabeth"; 
	}
	else if (movement_id == "grl_07") { 
	  value = "Fran"; 
	}
	else if (movement_id == "grl_08") { 
	  value = "Grace"; 
	}
	else if (movement_id == "grl_09") { 
	  value = "Helen"; 
	} 
	else if (movement_id == "grl_10") { 
	  value = "Isabel"; 
	}
	else if (movement_id == "grl_11") { 
	  value = "Jackie"; 
	}
	else if (movement_id == "grl_12") { 
	  value = "Karen"; 
	}
	else if (movement_id == "grl_13") { 
	  value = "Linda"; 
	}
	else if (movement_id == "grl_14") { 
	  value = "Mary"; 
	}
	else if (movement_id == "grl_15") { 
	  value = "Nancy"; 
	}
	else if (movement_id == "grl_16") { 
	  value = "Annie"; 
	}
	else if (movement_id == "grl_17") { 
	  value = "Eva"; 
	}
	else if (movement_id == "grl_18") { 
	  value = "Kelly"; 
	}
	else if (movement_id == "grl_19") { 
	  value = "Lynne"; 
	}
	else if (movement_id == "grl_20") { 
	  value = "Nicole"; 
	}
	else if (movement_id == "grl_21") { 
	  value = "Amanda"; 
	}
  return value; 
}


/***************** CHANGE TAB Method *******************************/
$('.tab-links a').on('click', function(e)  {
	var currentAttrValue = $(this).attr('href');

	// Show/Hide Tabs
	$('.tabs ' + currentAttrValue).show().siblings().hide();
	if(currentAttrValue.indexOf("grl") > -1) {
		addGirls();
	} else if(currentAttrValue.indexOf("hro") > -1) {
		addHeroes();
	}
	// Change/remove current tab to active
	$(this).parent('li').addClass('active').siblings().removeClass('active');

	e.preventDefault();
});

/***************** Crossfit Open Methods *******************************/

function openNewDataModal() {
	//Initialize all the datepick objects
	$(".open_date").datepick(
		{dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: true
	});
	//$(".date-month-year").find("").removeClass("select");
	var title = "New Crossfit Open Data";
	$( "#new_open_data" ).dialog({
      height: 800,
	  width: 800,
      modal: true
    });
	
	$( "#new_open_data" ).dialog();
	//selectorListener();
}

function checkForError(inputID) {
	var value = "";
	var weightReg = /^[0-9\:]*$/;
	var timeReg = /^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/;
	//console.log("ID: " + id + ", value: " + value);
	
	if ( inputID.indexOf("input") >= 0 )
	{	
		
		value = $("#" + inputID).val();
		console.log("ID: " + inputID + ", value: " + value);
		if(!weightReg.test(value))
		{
			console.log("ERROR!");
			$("#"+inputID+"").addClass("input_error");
			$("#"+inputID+"").qtip({ 
				content: 'Only characters 0-9 and : are allowed',
				style: { 
					name : "red", 
					border : {
						width : 3,
						radius : 5
					},
					color : "red"
				},
				show : {
					ready : true
				}
			});
		} else {
			console.log("NO ERROR!");
			//make sure there's no more than 2 colons
			//also ensure order of colons if timed event
			if(value.indexOf(":") > -1) {
				//time
				if(!timeReg.test(value)) {
					//error
					console.log("ERROR!");
					$("#"+inputID+"").addClass("input_error");
					$("#"+inputID+"").qtip({ 
						content: 'Invalid format! Valid formats: HH:MM:SS, MM:SS, M:SS',
						style: { 
							name : "red", 
							border : {
								width : 3,
								radius : 5
							},
							color : "red"
						},
						show : {
							ready : true
						}
					});
				} else {
					$("#"+inputID+"").removeClass("input_error");
					$("#"+inputID+"").qtip("destroy");
				}
			} else {
				$("#"+inputID+"").removeClass("input_error");
				$("#"+inputID+"").qtip("destroy");
			}
			
		}
	}
}

$( this ).focusout(function (event) {
	var id = event.target.id;
	checkForError(id);
});
/*
$("#new_open_data").on("change", "#division_selector", function() {
	console.log("division_selector changed");
});
*/
$("#new_open_data").on("change", "#division_selector", function() {
	console.log("division_selector changed");
	var html = "";
    $( "#division_selector option:selected" ).each(function() {
		var year = $("#tab_list").find('li.active').text();
		var special = "  SPECIAL: If 90 reps (3 rounds) is completed in under 4 minutes, time is extended to 8 minutes.";
		special += " If 180 reps (6 rounds) is completed in under 8 minutes, time is extended to 12 minutes.";
		special += " If 270 reps (9 rounds) is completed in under 12 minutes, time is extended to 16 minutes.";
		console.log("Selector: "+$(this).text()+" YEAR:" +year);
		if($(this).text() == 'Individual Women' || 
			$(this).text() == 'Masters Women 40-44' ||
			$(this).text() == 'Masters Women 45-49' ||
			$(this).text() == 'Masters Women 50-54' ) {
			
			var arr = $("#tab-content div div p:first");
			if(year == "2012") {
				if ( $('#tab1').length > 0 ){
					console.log("there's a tab");
					if ( $('#tab1').children('form').length > 0 ){
						console.log("there's a form");
						if ( $('#tab1').children('form').children('div').length > 0 ){
							console.log("there's a div");
							if ( $('#tab1').children('form').children('div').children('p').length > 0 ){
								console.log("there's a p");
							}
						}
					}
				}
				$('#tab1').children('form').children('div').children('p').each(function (i, field) { 
					//console.log($(this).text());
					if(i == '0') {
						
						$(this).text("Complete as many reps as possible in 7 minutes of: Burpees");
					} else if(i == '1') { 
					console.log($(this).text());
						$(this).text("Snatch ladder, complete the following in 10min: 45# snatch 30 reps, 75# snatch 30 reps, 100# snatch 30 reps, 120# snatch as many as possible ");
					} else if(i == '2') {
					console.log($(this).text());
						$(this).text('Complete as many reps as possible in 18 minutes of: 15 box jumps 20" box, 75# push press 12 reps, 9 toes to bar');
					} else if(i == '3') {
					console.log($(this).text());
						$(this).text("Complete as many reps as possible in 12 minutes of: 150 wall balls (14lbs, 8' target), 90 double unders, 30 muscle ups");
					} else if(i == '4') {
					console.log($(this).text());
						$(this).text("Complete as many reps as possible in 7 minutes of: 3 chest to bar pull ups, 3 thrusters @ 65#, 6 C2B pull ups, 6 Thrusters, 9 and 9, 12 and 12, ....");
					}
				});
			} else if(year == "2013") {
				$('#tab2').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete the following in 17min: 40 burpees, 45# snatch 30 reps, 30 burpees, 75# snatch 30 reps, 20 burpees, 100# snatch 30 reps, 40 burpees, 120# snatch as many as possible");
					} else if(i == '1') {
						$(this).text("Complete as many rounds as possible in 10min of: 75# shoulder to overhead 5 reps, 75# deadlift 10 reps, 15 box jumps 20\"");
					} else if(i == '2') {
						$(this).text("Complete as many reps as possible in 12 minutes of: 150 wall balls (14lbs, 9' target), 90 double unders, 30 muscle ups");
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 7 minutes of: 3 Clean and Jerks @ 95#, 3 toes-to-bar, 6 Clean and Jerks @ 95#, 6 toes-to-bar, 9 and 9, 12 and 12, ....");
					} else if(i == '4') {
						$(this).text("Complete as many reps as possible in 4 minutes of: 65# thruster 15 reps, 15 chest to bar pull ups" + special);
					}
				});
			} else if(year == "2014") {
				$('#tab3').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete as many reps as possible of: 30 double unders, 55lb power snatches 15 reps");
					} else if(i == '1') {
						$(this).text("From 00:00-3:00 2 rounds of: 10 overhead squats @ 65lbs, 10 chest to bar pull ups. " +
							"From 3:00-6:00 2 rounds of: 12 overhead squats @ 65lbs, 12 chest to bar pull ups. " + 
							"From 6:00-9:00 2 rounds of: 14 overhead squats @ 65lbs, 14 chest to bar pull ups. " +
							"Etc. following the same pattern until you fail to complete both rounds ");
					} else if(i == '2') {
						var box_jumps = " 15 box jumps @ 20\", "
							$(this).text('Complete as many reps as possible in 8 minutes of: 65lb deadlift 10 reps,'+box_jumps+
								'95lb deadlift 15 reps,'+box_jumps+
								'135lb deadlift 20 reps,'+box_jumps+
								'185lb deadlift 25 reps,'+box_jumps+'225lb deadlift 30 reps,'+box_jumps+
								'275lb deadlift 35 reps,'+box_jumps);
					} else if(i == '3') { 
						$(this).text("Complete as many reps as possible in 14 minutes of: " +
							"60-calorie row, 50 toes to bar, 40 wall balls @ 14lb 9' target, 30 cleans @ 95lbs, 20 muscle ups");
					} else if(i == '4') {
						$(this).text("21-18-15-12-9-6-3 reps for time of: 65lb thrusters, burpees");
					}
				});
			}
		} else if($(this).text() == 'Masters Women 60+' || $(this).text() == 'Masters Women 55-59') {
			if(year == "2012") {
				$('#tab1').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete as many reps as possible in 7 minutes of: Burpees");
					} else if(i == '1') {
						$(this).text("Snatch ladder, complete the following in 10min: 35# snatch 30 reps, 55# snatch 30 reps, 75# snatch 30 reps, 90# snatch as many as possible ");
					} else if(i == '2') {
						$(this).text('Complete as many reps as possible in 18 minutes of: 15 box jumps 20" box, 55# push press 12 reps, 9 toes to bar');
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 12 minutes of: 150 wall balls (10lbs, 9' target), 90 double unders, 30 muscle ups");
					} else if(i == '4') {
						$(this).text("Complete as many reps as possible in 7 minutes of: 3 chin over bar pull ups, 3 thrusters @ 55#, 6 C2B pull ups, 6 Thrusters, 9 and 9, 12 and 12, ....");
					}
				});
			} else if(year == "2013") {
				$('#tab2').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete the following in 17min: 40 burpees, 35# snatch 30 reps, 30 burpees, 55# snatch 30 reps, 20 burpees, 75# snatch 30 reps, 40 burpees, 90# snatch as many as possible");
					} else if(i == '1') {
						$(this).text("Complete as many rounds as possible in 10min of: 55# shoulder to overhead 5 reps, 55# deadlift 10 reps, 15 box jumps 20\"");
					} else if(i == '2') {
						$(this).text("Complete as many reps as possible in 12 minutes of: 150 wall balls (10lbs, 9' target), 90 double unders, 30 muscle ups");
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 7 minutes of: 3 Clean and Jerks @ 65#, 3 toes-to-bar, 6 Clean and Jerks @ 65#, 6 toes-to-bar, 9 and 9, 12 and 12, ....");
					} else if(i == '4') {
						$(this).text("Complete as many reps as possible in 4 minutes of: 45# thruster 15 reps, 15 jumping chest to bar pull ups" + special);
					}
				});
			} else if(year == "2014") {
				$('#tab3').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete as many reps as possible of: 30 double unders, 45lb power snatches 15 reps");
					} else if(i == '1') {
						$(this).text("From 00:00-3:00 2 rounds of: 10 overhead squats @ 65lbs, 10 jumping chest to bar pull ups. " +
							"From 3:00-6:00 2 rounds of: 12 overhead squats @ 65lbs, 12 jumping chest to bar pull ups. " + 
							"From 6:00-9:00 2 rounds of: 14 overhead squats @ 65lbs, 14 jumping chest to bar pull ups. " +
							"Etc. following the same pattern until you fail to complete both rounds ");
					} else if(i == '2') {
						var box_jumps = " 15 box jumps @ 20\", "
							$(this).text('Complete as many reps as possible in 8 minutes of: 95lb deadlift 10 reps,'+box_jumps+
								'135lb deadlift 15 reps,'+box_jumps+
								'185lb deadlift 20 reps,'+box_jumps+'225lb deadlift 25 reps,'+box_jumps+
								'275lb deadlift 30 reps,'+box_jumps+'315lb deadlift 35 reps,'+box_jumps);
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 14 minutes of: " +
							"60-calorie row, 50 toes to bar, 40 wall balls @ 10lb 9' target, 30 cleans @ 65lbs, 20 muscle ups");
					} else if(i == '4') {
						$(this).text("21-18-15-12-9-6-3 reps for time of: 45lb thrusters, burpees");
					}
				});
			}
		} else if($(this).text() == 'Masters Men 60+' || $(this).text() == 'Masters Men 55-59') {
			if(year == "2012") {
				$('#tab1').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete as many reps as possible in 7 minutes of: Burpees");
					} else if(i == '1') {
						$(this).text("Snatch ladder, complete the following in 10min: 45# snatch 30 reps, 75# snatch 30 reps, 100# snatch 30 reps, 120# snatch as many as possible ");
					} else if(i == '2') {
						$(this).text('Complete as many reps as possible in 18 minutes of: 15 box jumps 20" box, 95# push press 12 reps, 9 toes to bar');
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 12 minutes of: 150 wall balls (20lbs, 9' target), 90 double unders, 30 muscle ups");
					} else if(i == '4') {
						$(this).text("Complete as many reps as possible in 7 minutes of: 3 chest to bar pull ups, 3 thrusters @ 90#, 6 C2B pull ups, 6 Thrusters, 9 and 9, 12 and 12, ....");
					}
				});
			} else if(year == "2013") {
				$('#tab2').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete the following in 17min: 40 burpees, 45# snatch 30 reps, 30 burpees, 75# snatch 30 reps, 20 burpees, 100# snatch 30 reps, 40 burpees, 120# snatch as many as possible");
					} else if(i == '1') {
						$(this).text("Complete as many rounds as possible in 10min of: 95# shoulder to overhead 5 reps, 95# deadlift 10 reps, 15 box jumps 20\"");
					} else if(i == '2') {
						$(this).text("Complete as many reps as possible in 12 minutes of: 150 wall balls (20lbs, 9' target), 90 double unders, 30 muscle ups");
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 7 minutes of: 3 Clean and Jerks @ 115#, 3 toes-to-bar, 6 Clean and Jerks @ 115#, 6 toes-to-bar, 9 and 9, 12 and 12, ....");
					} else if(i == '4') {
						$(this).text("Complete as many reps as possible in 4 minutes of: 65# thruster 15 reps, 15 Chin over pull ups" + special);
					}
				});
			} else if(year == "2014") {
				$('#tab3').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete as many reps as possible of: 30 double unders, 65lb power snatches 15 reps");
					} else if(i == '1') {
						$(this).text("From 00:00-3:00 2 rounds of: 10 overhead squats @ 95lbs, 10 chin over bar pull ups. " +
							"From 3:00-6:00 2 rounds of: 12 overhead squats @ 95lbs, 12 chin over bar pull ups. " + 
							"From 6:00-9:00 2 rounds of: 14 overhead squats @ 95lbs, 14 chin over bar pull ups. " +
							"Etc. following the same pattern until you fail to complete both rounds ");
					} else if(i == '2') {
						var box_jumps = " 15 box jumps @ 20\", "
							$(this).text('Complete as many reps as possible in 8 minutes of: 95lb deadlift 10 reps,'+box_jumps+
								'135lb deadlift 15 reps,'+box_jumps+
								'185lb deadlift 20 reps,'+box_jumps+'225lb deadlift 25 reps,'+box_jumps+
								'275lb deadlift 30 reps,'+box_jumps+'315lb deadlift 35 reps,'+box_jumps);
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 14 minutes of: " +
							"60-calorie row, 50 toes to bar, 40 wall balls @ 20lb 9' target, 30 cleans @ 115lbs, 20 muscle ups");
					} else if(i == '4') {
						$(this).text("21-18-15-12-9-6-3 reps for time of: 65lb thrusters, burpees");
					}
				});
			}
		/********************** MEN UNDER 54********************************/
		} else if($(this).text() == 'Individual Men' || 
			$(this).text() == 'Masters Men 40-44' ||
			$(this).text() == 'Masters Men 45-49' ||
			$(this).text() == 'Masters Men 50-54' ) {

			/***************  2012   *******************/
			if(year == "2012") {
				$('#tab1').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete as many reps as possible in 7 minutes of: Burpees");
					} else if(i == '1') {
						$(this).text("Snatch ladder, complete the following in 10min: 75# snatch 30 reps, 135# snatch 30 reps, 165# snatch 30 reps, 210# snatch as many as possible");
					} else if(i == '2') {
						$(this).text("Complete as many reps as possible in 18 minutes of: 15 box jumps 24\" box, 115# push press 12 reps, 9 toes to bar");
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 12 minutes of: 150 wall balls (20lbs, 10' target), 90 double unders, 30 muscle ups");
					} else if(i == '4') {
						$(this).text("Complete as many reps as possible in 7 minutes of: 3 chest to bar pull ups, 3 thrusters @ 100#, 6 C2B pull ups, 6 Thrusters, 9 and 9, 12 and 12, ....");
					}
				});
			/***************  2013  *******************/
			} else if(year == "2013") {
				$('#tab2').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete the following in 17min: 40 burpees, 75# snatch 30 reps, 30 burpees, 135# snatch 30 reps, 20 burpees, 165# snatch 30 reps, 40 burpees, 210# snatch as many as possible");
					} else if(i == '1') {
						$(this).text("Complete as many rounds as possible in 10min of: 115# shoulder to overhead 5 reps, 115# deadlift 10 reps, 15 box jumps 24\"");
					} else if(i == '2') {
						$(this).text('Complete as many reps as possible in 12 minutes of: 150 wall balls (20lbs, 10\' target), 90 double unders, 30 muscle ups');
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 7 minutes of: 3 Clean and Jerks @ 135#, 3 toes-to-bar, 6 Clean and Jerks @ 135#, 6 toes-to-bar, 9 and 9, 12 and 12, ....");
					} else if(i == '4') {
						$(this).text("Complete as many reps as possible in 4 minutes of: 100# thruster 15 reps, 15 Chest to bar pull ups" + special);
					}
				});
			/***************  2014  *******************/
			} else if(year == "2014") {
				$('#tab3').children('form').children('div').children('p').each(function (i, field) {
					if(i == '0') {
						$(this).text("Complete as many reps as possible of: 30 double unders, 75lb power snatches 15 reps");
					} else if(i == '1') {
						$(this).text("From 00:00-3:00 2 rounds of: 10 overhead squats @ 95lbs, 10 chest to bar pull ups. " +
						"From 3:00-6:00 2 rounds of: 12 overhead squats @ 95lbs, 12 chest to bar pull ups. " + 
						"From 6:00-9:00 2 rounds of: 14 overhead squats @ 95lbs, 14 chest to bar pull ups. " +
						"Etc. following the same pattern until you fail to complete both rounds ");
					} else if(i == '2') {
						var box_jumps = " 15 box jumps @ 24\", "
						$(this).text('Complete as many reps as possible in 8 minutes of: 135lb deadlift 10 reps,'+box_jumps+
							'185lb deadlift 15 reps,'+box_jumps+'225lb deadlift 20 reps,'+box_jumps+
							'275lb deadlift 25 reps,'+box_jumps+'315lb deadlift 30 reps,'+box_jumps+
							'3655lb deadlift 35 reps,'+box_jumps);
					} else if(i == '3') {
						$(this).text("Complete as many reps as possible in 14 minutes of: " +
						"60-calorie row, 50 toes to bar, 40 wall balls @ 20lb 10' target, 30 cleans @ 135lbs, 20 muscle ups");
					} else if(i == '4') {
						$(this).text("21-18-15-12-9-6-3 reps for time of: 95lb thrusters, burpees");
					}
				});
			}
		}
	});
});

function submitNewOpenData(year) {
	console.log("Year:" + year);
	var data = $("#"+year+"_form").serializeArray();
	var sendRequest = true;
	$.each(data, function(i, field) {
		console.log("Name: " + field.name + ", value: " + field.value);
	});

	data.push({name:"division", value: $( "#division_selector option:selected" ).text()});

	var scoreItems = $(".open_score");
	var idOfMvmnt = "";
	var dateID = "";
	var t_date = "";
	scoreItems.each(function(i, field) {
		if($(this).hasClass('input_error') == true) {
			console.log("HAS ERROR");
			sendRequest = false;
		}
	});
	if(sendRequest === true) {
		$.ajax(
		{ 
		  type:"POST",                                     
		  url:"insertNewOpenWOD.php",         
		  data: data, //insert arguments here to $_POST
		  dataType: "text",  //data format      
		  success: function(response) //on receive of reply
		  {
			console.log("insert OPEN WOD response: " + response);
		  },
		  error: function(error){
				console.log('error insert OPEN WOD!' + error);
			}
		});
	} else {
		alert("Please fix errors before submitting!");
	}
}

/**************************************************/

function prDataModal() {
	//Initialize all the datepick objects
	$("._datepicker").datepick(
		{dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: true
	});
	//$(".date-month-year").find("").removeClass("select");
	var title = "New Crossfit Open Data";
	$( "#pr_modal" ).dialog({
      height: 800,
	  width: 800,
      modal: true
    });
	
	$( "#pr_modal" ).dialog();
	
}

/********************* Utility ***************************/

function timeFormatter(value) {
	var t_time = value;
	if(t_time.indexOf(":") > -1)
	{
		if(t_time.length > 5) {
			if(t_time.indexOf("00:") > -1 && t_time.indexOf("00:") < 4) {
				t_time = t_time.substring(t_time.indexOf(":")+1);
			}
		}
		if(t_time.length < 6) {
			if(t_time.substring(0,1) == "0") {
				t_time = t_time.substring(1);
			}
		}
	} else {
		t_time = "ERROR!"
	}
	return t_time;
}

var submitted = true;
function confirmOutput(count, arrayLength, output) {
	console.log(count + " = " + arrayLength);
	if(count == arrayLength && submitted == true) {
		alert("Data entered successfully!");
		clearInputs();
	} else {
		if(count > arrayLength) {
			submitted = false;
			alert("uh oh...");
		} else {
			if(output != "1") {
				submitted = false;
			}
		}
	}
}

function clearInputs() {
	$(':input','#pr_form_fnd')
	 .not(':button, :submit, :reset, :hidden')
	 .val('')
	 .removeAttr('checked')
	 .removeAttr('selected');
	$(':input','#pr_form_grl')
	 .not(':button, :submit, :reset, :hidden')
	 .val('')
	 .removeAttr('checked')
	 .removeAttr('selected');
	$(':input','#pr_form_hro')
	 .not(':button, :submit, :reset, :hidden')
	 .val('')
	 .removeAttr('checked')
	 .removeAttr('selected');
	$(':input','#pr_form_oly')
	 .not(':button, :submit, :reset, :hidden')
	 .val('')
	 .removeAttr('checked')
	 .removeAttr('selected');
	$(':input','#pr_form_pwr')
	 .not(':button, :submit, :reset, :hidden')
	 .val('')
	 .removeAttr('checked')
	 .removeAttr('selected');
	$(':input','#pr_form_mis')
	 .not(':button, :submit, :reset, :hidden')
	 .val('')
	 .removeAttr('checked')
	 .removeAttr('selected');
}

function addGirls() {
	var html = '';
	//angie
	html += '<h4>Angie</h4>For Time. Complete all reps of each exercise before moving to the next: <br/><br/>100 Pull-ups, 100 Push-ups, 100 Sit-ups, 100 Squats';
	html += '<br/><br/>Time: <input type="text" name="grl_01_input" class="_time" id="score_01_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_01_datepicker"/>';
	//barbara
	html += '<h4>Barbara</h4>5 rounds for time: <br/><br/>20 Pull-ups, 30 Push-ups, 40 Sit-ups, 50 Squats';
	html += '<br/><br/>Time: <input type="text" name="grl_02_input" class="_time" id="score_02_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_02_datepicker"/>';
	//Chelsea
	html += '<h4>Chelsea</h4>Each min on the min for 30 min: <br/><br/>5 Pull-ups, 10 Push-ups, 15 Squats';
	html += '<br/><br/>Time: <input type="text" name="grl_03_input" class="_time" id="score_03_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_03_datepicker"/>';
	//Cindy
	html += '<h4>Cindy</h4>As many rounds as possible in 20 min: <br/><br/>5 Pull-ups, 10 Push-ups, 15 Squats';
	html += '<br/><br/>Rounds: <input type="text" name="grl_04_input" class="_time" id="score_04_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_04_datepicker"/>';
	//Diane
	html += '<h4>Diane</h4>21-15-9 reps, for time: <br/><br/>Deadlift 225 lbs, Handstand push-ups';
	html += '<br/><br/>Time: <input type="text" name="grl_05_input" class="_time" id="score_05_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_05_datepicker"/>';
	//Elizabeth
	html += '<h4>Elizabeth</h4>21-15-9 reps, for time: <br/><br/>Clean 135 lbs, Ring Dips';
	html += '<br/><br/>Time: <input type="text" name="grl_06_input" class="_time" id="score_06_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_06_datepicker"/>';
	//Fran
	html += '<h4>Fran</h4>21-15-9 reps, for time: <br/><br/>Thruster 95 lbs, Pull-ups';
	html += '<br/><br/>Time: <input type="text" name="grl_07_input" class="_time" id="score_07_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_07_datepicker"/>';
	//Grace
	html += '<h4>Grace</h4>30 reps for time: <br/><br/>Clean and Jerk 135 lbs';
	html += '<br/><br/>Time: <input type="text" name="grl_08_input" class="_time" id="score_08_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_08_datepicker"/>';
	//Helen
	html += '<h4>Helen</h4>3 Rounds for time: <br/><br/>400 meter run, 55lb/35lb (1.5/1.0 pood)Kettlebell swing x 21, Pull-ups 12 reps';
	html += '<br/><br/>Time: <input type="text" name="grl_09_input" class="_time" id="score_09_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_09_datepicker"/>';
	//Isabel
	html += '<h4>Isabel</h4>30 reps for time: <br/><br/>Snatch 135 pounds';
	html += '<br/><br/>Time: <input type="text" name="grl_10_input" class="_time" id="score_10_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_10_datepicker"/>';
	//Jackie
	html += '<h4>Jackie</h4>For time: <br/><br/>1000 meter row, Thruster 45 lbs (50 reps), Pull-ups (30 reps)';
	html += '<br/><br/>Time: <input type="text" name="grl_11_input" class="_time" id="score_11_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_11_datepicker"/>';
	//Karen
	html += '<h4>Karen</h4>For time: <br/><br/>150 Wall-ball Shots (20/14)';
	html += '<br/><br/>Time: <input type="text" name="grl_12_input" class="_time" id="score_12_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_12_datepicker"/>';
	//Linda
	html += '<h4>Linda</h4>10/9/8/7/6/5/4/3/2/1 rep rounds for time: <br/><br/>Deadlift 1 1/2 BW, Bench BW, Clean 3/4 BW';
	html += '<br/><br/>Time: <input type="text" name="grl_13_input" class="_time" id="score_13_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_13_datepicker"/>';
	//Mary
	html += '<h4>Mary</h4>As many rounds as possible in 20 min of: <br/><br/>5 Handstand push-ups, 10 1-legged squats, 15 Pull-ups';
	html += '<br/><br/>Rounds: <input type="text" name="grl_14_input" class="_time" id="score_14_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_14_datepicker"/>';
	//Nancy
	html += '<h4>Nancy</h4>5 rounds for time: <br/><br/>400 meter run, Overhead squat 95 lbs x 15';
	html += '<br/><br/>Time: <input type="text" name="grl_15_input" class="_time" id="score_15_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_15_datepicker"/>';
	//Annie
	html += '<h4>Annie</h4>50-40-30-20 and 10 rep rounds; for time: <br/><br/>Double-unders, Sit-ups';
	html += '<br/><br/>Time: <input type="text" name="grl_16_input" class="_time" id="score_16_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_16_datepicker"/>';
	//Eva
	html += '<h4>Eva</h4>5 rounds for time: <br/><br/>Run 800 meters, 70lb/55lb (2/1.5 pood) KB swing 30 reps, 30 pullups';
	html += '<br/><br/>Time: <input type="text" name="grl_17_input" class="_time" id="score_17_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_17_datepicker"/>';
	//Kelly
	html += '<h4>Kelly</h4>5 rounds for time: <br/><br/>Run 400 meters, 30 box jumps @ 24/20 inch box, 30 Wall-ball shots 20lb/14lb';
	html += '<br/><br/>Time: <input type="text" name="grl_18_input" class="_time" id="score_18_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_18_datepicker"/>';
	//Lynne
	html += '<h4>Lynne</h4>5 rounds for REPS: <br/><br/>Bodyweight bench press (e.g., same amount on bar as you weigh), Pullups';
	html += '<br/><br/>Total Reps: <input type="text" name="grl_19_input" class="_time" id="score_19_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_19_datepicker"/>';
	//Nicole
	html += '<h4>Nicole</h4>As many rounds as possible in 20 minutes. Score is the number of pull-ups completed for each round: <br/><br/>Run 400 meters, Max effort Pull-ups';
	html += '<br/><br/>Total Reps: <input type="text" name="grl_20_input" class="_time" id="score_20_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_20_datepicker"/>';
	//Amanda
	html += '<h4>Amanda</h4>9-7-5 For time: <br/><br/>Muscle ups, Snatches (135/95)';
	html += '<br/><br/>Total Reps: <input type="text" name="grl_21_input" class="_time" id="score_21_input"/>  ';
	html += 'Date Achieved: <input type="text" name="date" class="_datepicker" id="grl_21_datepicker"/>';
	$("#pr_grl_div").html(html);
	
	//init datepicker
	$("._datepicker").datepick(
		{dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false
	});
}

function addHeroes() {
	var html = '';
	$.getJSON( "../../CRUD/progress/cf_heroes.json", function(data) {
		console.log(data);
		html = generateHeroes(data);
		//console.log(html);
		
		$("#pr_hro_div").html(html);
		$("._datepicker").datepick(
			{dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false
		});
	});
}

  </script>
    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  /*TEST SERVER*/
  ga('create', 'UA-50665970-1', 'cboxbeta.com');
  
  /* LIVE SERVER */
  //ga('create', 'UA-50665970-2', 'compete-box.com');
  
  ga('send', 'pageview');

</script>
	
</body>
</html>
