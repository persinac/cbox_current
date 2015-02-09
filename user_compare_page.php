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

$link = "index.html";
if ($_SESSION['MM_Admin'] == "0") {$link = "User_Home_Page.php";} //Default Blank 
else if ($_SESSION['MM_Admin'] == "1") {$link = "Admin_home_page.php";} // COMMENT 
	
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>COMPARE</title>
    <!-- Bootstrap core CSS and Custom CSS -->
	<link href="dist/css/user_compare_page.css" rel="stylesheet">
	<link href="dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="dist/css/bootstrap-combined.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="dist/css/jquery.qtip.css" />
    
	<!-- Calendar stuff -->
	<link href='dist/fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='dist/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
    <link href="dist/css/jquery.datepick.css" rel="stylesheet">
</head>

<body>

<div id="my_container">

<div id="navbar_main">
  <ul id="navbar_main_ul"> 
	<li id="home" >
	<?php echo "<a href='$link' >"; ?>HOME</a></li> 
	<li id="compare" class="navactive"><a href="#" >COMPARE</a></li> 
	<li id="wod"><a href="user_wod_page.php" >WOD</a>
		<ul id="challenge_calendar">
			<li><a href="#" >Calendar</a></li>
		</ul>
	</li> 
	<li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li> 
	<li id="account" ><a href="user_information.php" >ACCOUNT</a></li> 
	<li id="logout" ><a href="#" >LOGOUT</a></li>
  </ul> 
</div>


    <div id="image_div">
        <img src="images/compare_page/COMPARE_PAGE_CFAPP2.jpg" width="1224" height="792" alt=""/>
    </div>
    
    <div id="my_data_container">
        <div id="what_to_compare">
        	<h4>Results</h4>
            <select id="initial_selector" name="initial_selector" class="selector">
            	<option value="today">Today's Results</option>
				  <option value="search">Search</option>
				  <option value="compare">Compare</option>
				</select><br>
            <!--<h4>Search by: </h4>-->
            <form id="what_to_compare_form">
                <div id="div_compare_by">
                </div>
                <p></p>
            </form>
            
        </div> <!-- END WHAT_TO_COMPARE -->
        
        <div id="wod_list">
        </div> <!-- END WOD_LIST -->
		<div id="wod_description">
			<h2 style="color: #FFF" id="wod_desc_hdr">WOD Description</h2>
			<div id="wod_actual_description">
			</div>
		</div>
        <div id="leaderboard">
			<h2 style="color: #FFF" id="ldrbrd_hdr">Leaderboard</h2>
			<div id="leaderboard_data">
				<table width="350" rules="cols" id="tbl_leaderboard">
					<tr  id="leaderboard_headers">
						<th width="175" height="25">Athlete</th>
						<th width="175">Score</th>
					</tr>
					<tbody class="tbl_body_leaderboard" id="tbl_body_leaderboard">
					</tbody>
				</table>
			</div>
			<div id="mixed_leaderboard_display">
			</div>
		</div>
		
		<div id="average_user_score">
			<h2 id="avg_score_header">Average Score</h2>
			<p></p>
			<div id="avg_score">
			</div>
		</div>
    </div> <!-- END DATA_CONTAINER -->
	<div id="chart_div"></div>
	<div id="new_open_data" style="display:none;" title="What if? Open Data">
		<div id="tab_container" class="tabs">
			<ul id="tab_list" class="tab-links">
				<li class="active"><a href="#tab1">2012</a></li>
				<li><a href="#tab2">2013</a></li>
				<li><a href="#tab3">2014</a></li>
			</ul>
		 
			<div id="tab-content" class="tab-content" >
				<div id="tab1" class="tab active">
				<form id="2012_form">
					<div id="121_container" class="descrip">
						<h3>WOD #1</h3>
						<p>Complete as many reps as possible in 7 minutes of: Burpees </p>
						Original Score: <input type="text" name="121input" id="121input" readonly /><br/>
						"What if" Score: <input type="text" name="121whatifinput" id="121whatifinput"/>
					</div>
					<div id="121_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="121place" id="121place" readonly /><br/>
						"What if" Place: <input type="text" name="121whatifplace" id="121whatifplace"/>
					</div>
					<div id="122_container" class="descrip">
						<h3>WOD #2</h3>
						<p>Snatch ladder, complete the following in 10min: 75# snatch 30 reps, 135# snatch 30 reps, 165# snatch 30 reps, 210# snatch as many as possible </p>
						Original Score: <input type="text" name="122input" id="122input" readonly /><br/>
						"What if" Score: <input type="text" name="122whatifinput" id="122whatifinput"/>
					</div>
					<div id="122_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/><br/>
						Original Place: <input type="text" name="122place" id="122place" readonly /><br/>
						"What if" Place: <input type="text" name="122whatifplace" id="122whatifplace"/>
					</div>
					<div id="123_container" class="descrip">
						<h3>WOD #3</h3>
						<p>Complete as many reps as possible in 18 minutes of: 15 box jumps 24" box, 115# push press 12 reps, 9 toes to bar</p>
						Original Score: <input type="text" name="123input" id="123input" readonly /><br/>
						"What if" Score: <input type="text" name="123whatifinput" id="123whatifinput"/>
					</div>
					<div id="123_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/><br/>
						Original Place: <input type="text" name="123place" id="123place" readonly /><br/>
						"What if" Place: <input type="text" name="123whatifplace" id="123whatifplace"/>
					</div>
					<div id="124_container" class="descrip">
						<h3>WOD #4</h3>
						<p>Complete as many reps as possible in 12 minutes of: 150 wall balls (20lbs, 10' target), 90 double unders, 30 muscle ups</p>
						Original Score: <input type="text" name="124input" id="124input" readonly /><br/>
						"What if" Score: <input type="text" name="124whatifinput" id="124whatifinput"/>
					</div>
					<div id="124_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/><br/>
						Original Place: <input type="text" name="124place" id="124place" readonly /><br/>
						"What if" Place: <input type="text" name="124whatifplace" id="124whatifplace"/>
					</div>
					<div id="125_container" class="descrip">
						<h3>WOD #5</h3>
						<p>Complete as many reps as possible in 7 minutes of: 3 chest to bar pull ups, 3 thrusters @ 100#, 6 C2B pull ups, 6 Thrusters, 9 and 9, 12 and 12, ...</p>
						Original Score: <input type="text" name="125input" id="125input" readonly /><br/>
						"What if" Score: <input type="text" name="125whatifinput" id="125whatifinput"/>
					</div>
					<div id="125_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/><br/>
						Original Place: <input type="text" name="125place" id="125place" readonly /><br/>
						"What if" Place: <input type="text" name="125whatifplace" id="125whatifplace"/>
					</div>
					<input type="button" onclick='submitWhatIfData("2012")' id="newOpenDataButton" value="Submit What If Scores" />
				</form>
				</div>
		 
				<div id="tab2" class="tab">
				<form id="2013_form">
					<div id="131_container" class="descrip">
						<h3>WOD #1</h3>
						<p></p>
						Original Score: <input type="text" name="131input" id="131input" readonly /><br/>
						"What if" Score: <input type="text" name="131whatifinput" id="131whatifinput"/>
					</div>
					<div id="131_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="131place" id="131place" readonly /><br/>
						"What if" Place: <input type="text" name="131whatifplace" id="131whatifplace"/>
					</div>
					<div id="132_container" class="descrip">
						<h3>WOD #2</h3>
						<p></p>
						Original Score: <input type="text" name="132input" id="132input" readonly /><br/>
						"What if" Score: <input type="text" name="132whatifinput" id="132whatifinput"/>
					</div>
					<div id="132_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="132place" id="132place" readonly /><br/>
						"What if" Place: <input type="text" name="132whatifplace" id="132whatifplace"/>
					</div>
					<div id="133_container" class="descrip">
						<h3>WOD #3</h3>
						<p></p>
						Original Score: <input type="text" name="133input" id="133input" readonly /><br/>
						"What if" Score: <input type="text" name="133whatifinput" id="133whatifinput"/>
					</div>
					<div id="133_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="133place" id="133place" readonly /><br/>
						"What if" Place: <input type="text" name="133whatifplace" id="133whatifplace"/>
					</div>
					<div id="134_container" class="descrip">
						<h3>WOD #4</h3>
						<p></p>
						Original Score: <input type="text" name="134input" id="134input" readonly /><br/>
						"What if" Score: <input type="text" name="134whatifinput" id="134whatifinput"/>
					</div>
					<div id="134_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="134place" id="134place" readonly /><br/>
						"What if" Place: <input type="text" name="134whatifplace" id="134whatifplace"/>
					</div>
					<div id="135_container" class="descrip">
						<h3>WOD #5</h3>
						<p></p>
						Original Score: <input type="text" name="135input" id="135input" readonly /><br/>
						"What if" Score: <input type="text" name="135whatifinput" id="135whatifinput"/>
					</div>
					<div id="135_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="135place" id="135place" readonly /><br/>
						"What if" Place: <input type="text" name="135whatifplace" id="135whatifplace"/>
					</div>
					<input type="button" onclick='submitWhatIfData("2013")' id="newOpenDataButton" value="Submit What If Scores"/>
				</form>
				</div>
		 
				<div id="tab3" class="tab">
					<form id="2014_form">
					<div id="141_container" class="descrip">
						<h3>WOD #1</h3>
						<p></p>
						Original Score: <input type="text" name="141input" id="141input" readonly /><br/>
						"What if" Score: <input type="text" name="141whatifinput" id="141whatifinput"/>
					</div>
					<div id="141_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="141place" id="141place" readonly /><br/>
						"What if" Place: <input type="text" name="141whatifplace" id="141whatifplace"/>
					</div>
					<div id="142_container" class="descrip">
						<h3>WOD #2</h3>
						<p></p>
						Original Score: <input type="text" name="142input" id="142input" readonly /><br/>
						"What if" Score: <input type="text" name="142whatifinput" id="142whatifinput"/>
					</div>
					<div id="142_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="142place" id="142place" readonly /><br/>
						"What if" Place: <input type="text" name="142whatifplace" id="142whatifplace"/>
					</div>
					<div id="143_container" class="descrip">
						<h3>WOD #3</h3>
						<p></p>
						Original Score: <input type="text" name="143input" id="143input" readonly /><br/>
						"What if" Score: <input type="text" name="143whatifinput" id="143whatifinput"/>
					</div>
					<div id="143_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="143place" id="143place" readonly /><br/>
						"What if" Place: <input type="text" name="143whatifplace" id="143whatifplace"/>
					</div>
					<div id="144_container" class="descrip">
						<h3>WOD #4</h3>
						<p></p>
						Original Score: <input type="text" name="144input" id="144input" readonly /><br/>
						"What if" Score: <input type="text" name="144whatifinput" id="144whatifinput"/>
					</div>
					<div id="144_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="144place" id="144place" readonly /><br/>
						"What if" Place: <input type="text" name="144whatifplace" id="144whatifplace"/>
					</div>
					<div id="145_container" class="descrip">
						<h3>WOD #5</h3>
						<p></p>
						Original Score: <input type="text" name="145input" id="145input" readonly /><br/>
						"What if" Score: <input type="text" name="145whatifinput" id="145whatifinput"/>
					</div>
					<div id="145_whatIf" class="whatIfdescrip">
						<br/><br/><br/><br/>
						Original Place: <input type="text" name="145place" id="145place" readonly /><br/>
						"What if" Place: <input type="text" name="145whatifplace" id="145whatifplace"/>
					</div>
					<input type="button" onclick='submitWhatIfData("2014")' id="newOpenDataButton" value="Submit What If Scores"/>
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
				<h4>Optional - Select a region:</h4>
				<select id="region_selector" name="region" class="selector">
					<option value="none"> - </option>
					<option value="Africa">Africa</option>
					<option value="Asia">Asia</option>
					<option value="Australia">Australia</option>
					<option value="Canada East">Canada East</option>
					<option value="Canada West">Canada West</option>
					<option value="Central East">Central East</option>
					<option value="Europe">Europe</option>
					<option value="Latin America">Latin America</option>
					<option value="Mid Atlantic">Mid Atlantic</option>
					<option value="Northern California">Northern California</option>
					<option value="North Central">North Central</option>
					<option value="North East">North East</option>
					<option value="North West">North West</option>
					<option value="South Central">South Central</option>
					<option value="South East">South East</option>
					<option value="South West">South West</option>
					<option value="Southern California">Southern California</option>
				</select>
				<br/><br/>
				<input type="button" onclick='toLoadUserOpenScores()' id="yourOpenDataButton" value="Load Your Data"/>
			</div>
		</div>
	</div>
	<div id="dialog-modal" style="display:none;">
		<div id="modal_content">
		</div>
	</div>
	<div id="success-modal" style="display:none;">
		<div id="success_content">
		</div>
	</div>
</div> <!-- END CONTAINER -->

<!-- JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<!-- These must be in THIS ORDER! DO NOT FUCK WITH -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="dist/js/filter_actions.js"></script> 

<!-- Required for full calendar -->
<script src='dist/lib/moment.min.js'></script>
<script src="dist/jq_ui/js/jquery-1.10.2.js"></script>
<script src="dist/jq_ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src='dist/fullcalendar/fullcalendar.min.js'></script>

<!-- Required for date picker -->
<script src="dist/js/jquery.plugin.min.js"></script>
<script src="dist/js/jquery.datepick.min.js"></script> 

<script type="text/javascript" src="dist/js/jquery.qtip.js"></script>

<!-- d3js.org/d3.v3.min.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js" charset="utf-8"></script>

<script>
/*
* Once the document is  loaded...
*
*/
google.load('visualization', '1.0', {'packages':['corechart']});
$(document).ready(function() {
	//event.preventDefault();
	//console.log("READY!!!");
	getCurrentDate();
});

var header_wod = "";
var type_of_wod_main = "";
var main_description = "";

var json_object = [];
var dynCount = 0;

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
		}
	});	

$('.tabs .tab-links a').on('click', function(e)  {
	var currentAttrValue = $(this).attr('href');

	// Show/Hide Tabs
	$('.tabs ' + currentAttrValue).show().siblings().hide();

	// Change/remove current tab to active
	$(this).parent('li').addClass('active').siblings().removeClass('active');

	e.preventDefault();
});
	
	
/*
* Set up the datepicker object
*
*/
$(function() {
	//event.preventDefault();
    $("#datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
  });
  
  $( "#initial_selector" ).change(function() {
    var str = "";
	var second_str = "";
	//console.log("INITIAL CHANGED");
    $( "#initial_selector option:selected" ).each(function() 
	{
		clearAll();
		$('#div_compare_by').empty();
		if($(this).text() == "Today's Results") {
			console.log("TODAY");
			$("#avg_score_header").html("Average Score");
            str += 'Male <input type="radio" name="gender_to_compare" class="radio_butts" value="M">';
            str += 'Female <input type="radio" name="gender_to_compare" class="radio_butts" value="F">';
            str += 'All <input type="radio" name="gender_to_compare" class="radio_butts" value="A"> <br><br>';
            str += '<select id="today_compare_selector" name="compare_selector" class="selector">';
			str += "<option value=\"ALL\"> - </option>";
			str += '<option value="RX">RX</option>';
			str += '<option value="INTER">Intermediate</option>';
			str += '<option value="NOV">Novice</option>';
			str += '</select><br>';
			str += '<input onclick="today_compare(this.form);" type="button" id="compare_but" value="Today" />';
			//$('#div_compare_by').append(str);
		} else if($(this).text() == "Search") {
			console.log("Search");
			$("#avg_score_header").html("Average Score");
                /*<!--Box <input type="radio" name="area_to_compare" class="radio_butts" value="box">
                Region <input type="radio" name="area_to_compare" class="radio_butts" value="reg">
                Country <input type="radio" name="area_to_compare" class="radio_butts" value="cou"> <br><br>-->*/
            str += '<select id="compare_selector" name="compare_selector" class="selector">';
			str += "<option value=\"ALL\"> - </option>";
			str += '<option value="WOD">WODS</option>';
			str += '<option value="CORE">Core Lifts</option>';
			str += '<option value="OLY">Olympic</option>';
			str += '<option value="PWR">Powerlifting</option>';
			str += '<option value="GIRLS">Girls</option>';
			str += '<option value="HERO">Heroes</option>';
			str += '<option value="OPEN">Open</option>';
			str += '<option value="REG">Regionals</option>';
			str += '<option value="GAME">Games</option>';
			str += '</select>';
			str += '<p id="comparisons_to_add">';
			
			str += "Level Performed: <select id=\"level_selector\" name=\"level_selector\">";
			str += "<option value=\"ALL\"> - </option>";
			str +="<option value=\"RX\">RX</option>";
			str += "<option value=\"INTER\">Intermediate</option>";
			str +="<option value=\"NOV\">Novice</option>";
			str +="<option value=\"CUS\">Custom</option>";
			str +="</select><br>";
			str += "Type of WOD: <select id=\"wod_type_selector\" name=\"wod_type_selector\">";
			str += "<option value=\"ALL\"> - </option>";
			str +="<option value=\"RFT\">RFT</option>";
			str += "<option value=\"AMRAP\">AMRAP</option>";
			str +="<option value=\"TABATA\">TABATA</option>";
			str += "<option value=\"GIRLS\">GIRLS</option>"
			str +="<option value=\"HERO\">HEROES</option>";
			str +="</select><br>";
			str += '</p>';
			str += '<input onclick="serializeForm(this.frm);" type="button" id="search_but" value="Search" />';
			str += ' <input onclick="whatIfModal();" type="button" id="what_if_but" value="What if?" />';
			
		} else if($(this).text() == "Compare") {
			console.log("COMPARE");
			str += '<h4>My: </h4>';
			$("#avg_score_header").empty();
            str += '<select id="my_compare_selector" name="my_compare_selector" class="selector">';
			str += "<option value=\"NONE\"> - </option>";
			str += '<option value="CORE"> Core Benchmarks </option>';
			str += '<option value="OLY"> Olympic </option>';
			str += '<option value="PWR"> Powerlifting </option>';
			str += '<option value="GIRLS"> Girl Benchmarks </option>';
			str += '<option value="HERO"> Heroes </option>';
			str += '</select>';
			str += '<h4> - VS - </h4>'
			str += '<select id="vs_compare_selector" name="vs_compare_selector" class="selector">';
			str += "<option value=\"NONE\"> - </option>";
			str += "<option value=\"YRBX\"> My Box </option>";
			str += "<option value=\"ANBX\"> Another Box </option>";
			str += "<option value=\"REGI\"> Region </option>";
			str += "<option value=\"OPAT\"> Open Athletes </option>";
			str += "<option value=\"REAT\"> Regional Athletes </option>";
			str += "<option value=\"GAAT\"> Games Athletes </option>";
			str += '</select>';
			str += '<div id="vs_txt_area"></div>';
			//str += '<input onclick="serializeForm(this.frm);" type="button" id="search_but" value="Compare" />';
			//str += ' <input onclick="openNewDataModal();" type="button" id="new_data_but" value="New Open Data" />';
			//update the wod_list...
			second_str = '<div id="vs_who_to_compare" class="filters filter_border"></div>';
			second_str += '<div id="vs_what_to_compare" class="filters filter_border"></div>';
			second_str += '<div id="vs_button_to_compare" class="filters filter_border"></div>';
			$("#wod_list").html(second_str);
		}
		$('#div_compare_by').append(str);
		
		
    });
  }).trigger( "change" );
  
$( "#div_compare_by" ).on("change", "#compare_selector", function() {
    var str = "";
	var second_str = "";
	console.log("Compare selector CHANGED");
	//clear the page
	clearAll();
	
    $( "#compare_selector option:selected" ).each(function() 
	{
		$('#comparisons_to_add').empty();
		if($(this).text() == 'WODS') {
			console.log("WODS");
			header_wod = "Past WODs";
			str += "Month: <select size=\"1\" name=\"months\" class=\"date_compare\" id=\"months\">";
			str += "<option value=\"ALL\"> - </option>";
			str += "<option value=\"01\">January</option>";
			str += "<option value=\"02\">February</option>";
			str += "<option value=\"03\">March</option>";
			str += "<option value=\"04\">April</option>";
			str += "<option value=\"05\">May</option>";
			str += "<option value=\"06\">June</option>";
			str += "<option value=\"07\">July</option>";
			str += "<option value=\"08\">August</option>";
			str += "<option value=\"09\">September</option>";
			str += "<option value=\"10\">October</option>";
			str += "<option value=\"11\">November</option>";
			str += "<option value=\"12\">December</option>";
			str +="</select>";
			str += "Year: <select size=\"1\" name=\"year\" class=\"date_compare\" id=\"years\">";
			str += "<option value=\"ALL\"> - </option>";
			//for loop to produce 00-59
			for(var i = 0; i < 20; i++) {
				if(i < 10) {
					str += "<option value=\"0"+i+"\">200"+i+"</option>";
				} else {
					str += "<option value=\""+i+"\">20"+i+"</option>";
				}	
			}
			str +="</select>";
			str +="<p></p>";
			str += "Type of WOD: <select id=\"wod_type_selector\" name=\"wod_type_selector\">";
			str +="<option value=\"NONE\">-</option>";
			str +="<option value=\"ALL\">ALL</option>";
			str +="<option value=\"RFT\">RFT</option>";
			str += "<option value=\"AMRAP\">AMRAP</option>";
			str +="<option value=\"TABATA\">TABATA</option>";
			str +="<option value=\"MIXED\">MIXED</option>";
			str += "<option value=\"GIRLS\">GIRLS</option>"
			str +="<option value=\"HERO\">HEROES</option>";
			str +="</select>";
			str +="<p></p>";
			/*str += "Level Performed: <select id=\"level_selector\" name=\"level_selector\">";
			str +="<option value=\"RX\">RX</option>";
			str += "<option value=\"INTER\">Intermediate</option>";
			str +="<option value=\"NOV\">Novice</option>";
			str +="<option value=\"CUS\">Custom</option>";
			str +="</select>";*/
			str +="<p></p>";
			$("#wod_desc_hdr").html("WOD Description");
			$("#ldrbrd_hdr").html("Leaderboard");
			$("#leaderboard_headers").html("<th width=\"175\" height=\"25\">Athlete</th><th width=\"175\">Score</th>");
		} else if($(this).text().indexOf("Core") > -1) {
			$("#chart_div").empty();
			console.log("Core movements");
			$("#wod_desc_hdr").html("Core Movements");
			$("#ldrbrd_hdr").html("Leaderboard");
			$("#leaderboard_headers").html("<th width=\"175\" height=\"25\">Name</th><th width=\"175\">Weight</th>");
			str += "<p></p>";
			str += 'Male <input type="radio" name="gender" id="genderM" class="radio_butts" value="M">';
			str += 'Female <input type="radio" name="gender" id="genderF" class="radio_butts" value="F"><p></p>';
			str += "Core Lift: <select id=\"core_type_selector\" name=\"core_type_selector\">";
			str +="<option value=\"NONE\">-</option>";
			str +="<option value=\"cft_01\">Back Squat</option>";
			str +="<option value=\"cft_02\">Front Squat</option>";
			str += "<option value=\"cft_03\">Overhead Squat</option>";
			str +="<option value=\"cft_04\">Deadlift</option>";
			str +="<option value=\"cft_05\">SDLHP</option>";
			str += "<option value=\"cft_06\">Power Clean</option>"
			str +="<option value=\"cft_07\">Overhead Press</option>";
			str +="<option value=\"cft_08\">Push Press</option>";
			str +="<option value=\"cft_09\">Push Jerk</option>";
			str +="</select>";
		} else if($(this).text().indexOf("Open") > -1) {
			$("#chart_div").empty();
			console.log("CF Open Workouts");
			$("#wod_desc_hdr").html("Workout");
			$("#ldrbrd_hdr").html("Leaderboard");
			$("#leaderboard_headers").html("<th width=\"175\" height=\"25\">Name</th><th width=\"175\"> Place (Score) </th>");
			str += "<p></p>";
			str += "Year: <select id=\"open_year_selector\" name=\"open_year_selector\">";
			str +="<option value=\"NONE\">-</option>";
			str +="<option value=\"12\">2012</option>";
			str += "<option value=\"13\">2013</option>";
			str +="<option value=\"14\">2014</option>";
			str +="</select>";
			str += "Region: <select id=\"open_region_selector\" name=\"open_region_selector\">";
			str +="<option value=\"NONE\">-</option>";
			str +="<option value=\"Africa\">Africa</option>";
			str +="<option value=\"Asia\">Asia</option>";
			str += "<option value=\"Australia\">Australia</option>";
			str +="<option value=\"Canada East\">Canada East</option>";
			str +="<option value=\"Canada West\">Canada West</option>";
			str += "<option value=\"Central East\">Central East</option>"
			str +="<option value=\"Europe\">Europe</option>";
			str +="<option value=\"Latin America\">Latin America</option>";
			str +="<option value=\"Mid Atlantic\">Mid Atlantic</option>";
			str +="<option value=\"North Central\">North Central</option>";
			str += "<option value=\"North East\">North East</option>";
			str +="<option value=\"Northern California\">North California</option>";
			str +="<option value=\"North West\">North West</option>";
			str += "<option value=\"South Central\">South Central</option>"
			str +="<option value=\"South East\">South East</option>";
			str +="<option value=\"Southern California\">South California</option>";
			str +="<option value=\"South West\">South West</option>";
			str +="</select>";
			str +="<div id=\"div_and_wod\"></div>";
		}
		$('#comparisons_to_add').append(str);
    });
  }).trigger( "change" );
  
$( "#div_compare_by" ).on("change", "#wod_type_selector", function() {
	var second_str = "";
	console.log("WTS CHANGED");
    $( "#wod_type_selector option:selected" ).each(function() 
	{
		$('#wod_list').empty();
		$('#mixed_leaderboard_display').empty();
		$('#chart_div').empty();
		$('#tbl_body_leaderboard').empty();
		if($(this).text() == 'ALL') {
			console.log("ALL");
			
			second_str+="<h4><p id=\"display_workout\"></p></h4>";
        	second_str+="<table width=\"530\" rules=\"cols\" id=\"tbl_wod_list\">";
            second_str+="<tr id=\"wod_list_headers\">";  	
            second_str+="</tr>";
            second_str+="<tbody class=\"tbl_body_wod_list\" id=\"tbl_body_wod_list\">";
            second_str+="</tbody></table>";
			$('#wod_list').html(second_str);
			second_str = "<th width=\"80\" height=\"25\">Date</th>";
            second_str +="<th width=\"80\">Type of WOD</th>";
            second_str +="<th width=\"200\">Place</th>";
			$('#wod_list_headers').append(second_str);
		} else if($(this).text() == 'RFT') {
			console.log("RFT");
			type_of_wod_main = "RFT";
			second_str+="<h4><p id=\"display_workout\"></p></h4>";
        	second_str+="<table width=\"530\" rules=\"cols\" id=\"tbl_wod_list\">";
            second_str+="<tr id=\"wod_list_headers\">";  	
            second_str+="</tr>";
            second_str+="<tbody class=\"tbl_body_wod_list\" id=\"tbl_body_wod_list\">";
            second_str+="</tbody></table>";
			$('#wod_list').html(second_str);
			second_str = "<th width=\"80\" height=\"25\">Date</th>";
            second_str +="<th width=\"80\">Type of WOD</th>";
            second_str +="<th width=\"200\">Place</th>";
			$('#wod_list_headers').append(second_str);
		} else if($(this).text() == 'AMRAP') {
			console.log("AMRAP");
			type_of_wod_main = "AMRAP";
			second_str+="<h4><p id=\"display_workout\"> WORKOUT HERE </p></h4>";
        	second_str+="<table width=\"530\" rules=\"cols\" id=\"tbl_wod_list\">";
            second_str+="<tr id=\"wod_list_headers\">";  	
            second_str+="</tr>";
            second_str+="<tbody class=\"tbl_body_wod_list\" id=\"tbl_body_wod_list\">";
            second_str+="</tbody></table>";
			$('#wod_list').html(second_str);
			second_str = "<th width=\"80\" height=\"25\">Date</th>";
            second_str +="<th width=\"80\">Type of WOD</th>";
            second_str +="<th width=\"200\">Place</th>";
			$('#wod_list_headers').append(second_str);
		} else if($(this).text() == 'MIXED') {
			console.log("MIXED");
			type_of_wod_main = "MIXED";
			second_str+="<h4><p id=\"display_workout\"> WORKOUT HERE </p></h4>";
        	second_str+="<table width=\"530\" rules=\"cols\" id=\"tbl_wod_list\">";
            second_str+="<tr id=\"wod_list_headers\">";  	
            second_str+="</tr>";
            second_str+="<tbody class=\"tbl_body_wod_list\" id=\"tbl_body_wod_list\">";
            second_str+="</tbody></table>";
			$('#wod_list').html(second_str);
			second_str = "<th width=\"80\" height=\"25\">Date</th>";
            second_str +="<th width=\"80\">Type of WOD</th>";
            second_str +="<th width=\"200\">Place</th>";
			$('#wod_list_headers').append(second_str);
		}
		
    });
  }).trigger( "change" );
  
 $( "#div_compare_by" ).on("change", "#open_year_selector", function() {
    var str = "";
	var second_str = "";
	console.log("OPEN YEAR selector CHANGED");
	$("#div_and_wod").empty();
    $( "#open_year_selector option:selected" ).each(function() 
	{
		if($(this).text() == '2011' || $(this).text() == '2012') {
			str += "Division: <select id=\"open_division_selector\" name=\"open_division_selector\">";
			str +="<option value=\"NONE\">-</option>";
			str +="<option value=\"Individual Men\">Individual Men</option>";
			str +="<option value=\"Individual Women\">Individual Women</option>";
			str += "<option value=\"Masters Men 45-49\">Masters Men 45-49</option>";
			str +="<option value=\"Masters Women 45-49\">Masters Women 45-49</option>";
			str +="<option value=\"Masters Men 50-54\">Masters Men 50-54</option>";
			str += "<option value=\"Masters Women 50-54\">Masters Women 50-54</option>"
			str +="<option value=\"Masters Men 55-59\">Masters Men 55-59</option>";
			str +="<option value=\"Masters Women 55-59\">Masters Women 55-59</option>";
			str +="<option value=\"Masters Men 60+\">Masters Men 60+</option>";
			str +="<option value=\"Masters Women 60+\">Masters Women 60+</option>";
			str += "<option value=\"Team\">Team</option>";
			str +="</select><br/>";
		} else {
			str += "Division: <select id=\"open_division_selector\" name=\"open_division_selector\">";
			str +="<option value=\"NONE\">-</option>";
			str +="<option value=\"Individual Men\">Individual Men</option>";
			str +="<option value=\"Individual Women\">Individual Women</option>";
			str += "<option value=\"Masters Men 40-44\">Masters Men 40-44</option>";
			str +="<option value=\"Masters Women 40-44\">Masters Women 40-44</option>";
			str += "<option value=\"Masters Men 45-49\">Masters Men 45-49</option>";
			str +="<option value=\"Masters Women 45-49\">Masters Women 45-49</option>";
			str +="<option value=\"Masters Men 50-54\">Masters Men 50-54</option>";
			str += "<option value=\"Masters Women 50-54\">Masters Women 50-54</option>"
			str +="<option value=\"Masters Men 55-59\">Masters Men 55-59</option>";
			str +="<option value=\"Masters Women 55-59\">Masters Women 55-59</option>";
			str +="<option value=\"Masters Men 60+\">Masters Men 60+</option>";
			str +="<option value=\"Masters Women 60+\">Masters Women 60+</option>";
			str += "<option value=\"Team\">Team</option>";
			str +="</select><br/>";
		}
		str += "WOD: <select id=\"open_wod_selector\" name=\"open_wod_selector\">";
		str +="<option value=\"NONE\">-</option>";
		str +="<option value=\""+$(this).val()+"1\">"+$(this).val()+".1</option>";
		str +="<option value=\""+$(this).val()+"2\">"+$(this).val()+".2</option>";
		str +="<option value=\""+$(this).val()+"3\">"+$(this).val()+".3</option>";
		str +="<option value=\""+$(this).val()+"4\">"+$(this).val()+".4</option>";
		str +="<option value=\""+$(this).val()+"5\">"+$(this).val()+".5</option>";
		str +="</select>";
	});
	$('#div_and_wod').append(str);
});

$( "#div_compare_by" ).on("change", "#core_type_selector", function() {
	var second_str = "";
	console.log("CTS CHANGED");
    $( "#core_type_selector option:selected" ).each(function() 
	{
		$('#wod_list').empty();
		if($(this).text() == 'Back Squat') {
			console.log("BS");
			$("#wod_desc_hdr").html("Back Squat");	
		} else if($(this).text() == 'Front Squat') {
			console.log("FS");
			$("#wod_desc_hdr").html("Front Squat");	
		} else if($(this).text() == 'Overhead Squat') {
			console.log("OHS");
			$("#wod_desc_hdr").html("Overhead Squat");	
		} else if($(this).text() == 'Deadlift') {
			console.log("DL");
			$("#wod_desc_hdr").html("Deadlift");	
		} else if($(this).text() == 'SDLHP') {
			console.log("SD");
			$("#wod_desc_hdr").html("SDLHP");	
		} else if($(this).text() == 'Power Clean') {
			console.log("PC");
			$("#wod_desc_hdr").html("Power Clean");	
		} else if($(this).text() == 'Overhead Press') {
			console.log("OHP");
			$("#wod_desc_hdr").html("Overhead Press");	
		} else if($(this).text() == 'Push Press') {
			console.log("PP");
			$("#wod_desc_hdr").html("Push Press");	
		} else {
			console.log("PJ");
			$("#wod_desc_hdr").html("Push Jerk");
		}		
    });
  }).trigger( "change" );
  
  $( "#wod_list" ).on("click", ".date_link", function() {
		$('#mixed_leaderboard_display').empty();
		var date = document.getElementById($(this).attr("id")).text;
		console.log($(this).attr("id") + ", VALUE: " + document.getElementById($(this).attr("id")).text);
		var result = date.replace(/-/g, "");
		$("#test_button").remove();
		getLeaderBoardData(result);
		return false;
  });
  
$("#leaderboard").on("change", "#mixed_wod_selector", function() {
	var part = $(this).val();
	var thisID = $(this).attr("id");
	console.log("What is my ID? " + thisID + ", What's my value? " + part);
	myTestCalculator(part);
});

$( "#div_compare_by" ).on("change", "#my_compare_selector", function() {
    var str = "";
	var second_str = "";
	console.log("MY Compare selector CHANGED");
    $( "#my_compare_selector option:selected" ).each(function() {
		$("#vs_txt_area").html('<p class="indent_me">'+$(this).text()+'</p>');
		console.log($(this).val());
		$("#vs_what_to_compare").html(loadWhatFilterBasedOnVSOption($(this).val()));
		
		
	});
});

$( "#div_compare_by" ).on("change", "#vs_compare_selector", function() {
    var str = "";
	var second_str = "";
	console.log("VS Compare selector CHANGED");
    $( "#vs_compare_selector option:selected" ).each(function() {
		console.log($(this).val());
		$("#vs_who_to_compare").html(loadWhoFilterBasedOnVSOption($(this).val()));
	});
});

$("#modal_content").on("click", ".athleteOpenSearchRow", function() {
	var toParse = $(this).attr("href");
	console.log(toParse);
	var athlete = new Array();
	
	var ath_name = toParse.substring(0,toParse.indexOf("^"));
	toParse = toParse.substring(toParse.indexOf("^")+1);
	
	var overall = toParse.substring(0,toParse.indexOf("^"));
	overall = overall.substring(0,overall.indexOf("(")).trim();
	toParse = toParse.substring(toParse.indexOf("^")+1);
	
	var division = toParse.substring(0,toParse.indexOf("^"));
	toParse = toParse.substring(toParse.indexOf("^")+1);
	
	var region = toParse.substring(0,toParse.indexOf("^"));
	toParse = toParse.substring(toParse.indexOf("^")+1);
	
	var wodOne = toParse.substring(0,toParse.indexOf("^"));
	wodOne = wodOne.substring(wodOne.indexOf("(")+1,wodOne.indexOf(")")).trim();
	toParse = toParse.substring(toParse.indexOf("^")+1);
	
	var wodTwo = toParse.substring(0,toParse.indexOf("^"));
	wodTwo = wodTwo.substring(wodTwo.indexOf("(")+1,wodTwo.indexOf(")")).trim();
	toParse = toParse.substring(toParse.indexOf("^")+1);
	
	var wodThree = toParse.substring(0,toParse.indexOf("^"));
	wodThree = wodThree.substring(wodThree.indexOf("(")+1,wodThree.indexOf(")")).trim();
	toParse = toParse.substring(toParse.indexOf("^")+1);
	
	var wodFour = toParse.substring(0,toParse.indexOf("^"));
	wodFour = wodFour.substring(wodFour.indexOf("(")+1,wodFour.indexOf(")")).trim();
	toParse = toParse.substring(toParse.indexOf("^")+1);
	
	var wodFive = toParse;
	wodFive = wodFive.substring(wodFive.indexOf("(")+1,wodFive.indexOf(")")).trim();
	
	athlete.push({name:"ath_name", value:ath_name});
	athlete.push({name:"overall", value:overall});
	athlete.push({name:"division", value:division});
	athlete.push({name:"region", value:region});
	athlete.push({name:"wodOne", value:wodOne});
	athlete.push({name:"wodTwo", value:wodTwo});
	athlete.push({name:"wodThree", value:wodThree});
	athlete.push({name:"wodFour", value:wodFour});
	athlete.push({name:"wodFive", value:wodFive});
	/*
	$.each(athlete, function(i, field) {
		console.log(field.name + " : " + field.value);
	});
	*/
	submitFoundAthleteOpenData(athlete);
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

function getLeaderBoardData(data) {
	console.log("get leader board data: " + data);
	$('#avg_score').empty();
	$('#wod_actual_description').empty();
	$('#tbl_body_leaderboard').empty();
	$('#chart_div').empty();
	var comma_index = data.indexOf(",");
	var temp_str = "";
	var date = "";
	var gender = "";
	var level = "";
	if(comma_index > -1) {
		date = data.substring(0, comma_index);
		temp_str = data.substring(comma_index+1);
		comma_index = temp_str.indexOf(",");
		gender = temp_str.substring(0, comma_index);
		temp_str = temp_str.substring(comma_index);
		level = temp_str.substring(comma_index);
		
	} else {
		date = data;
	}
	console.log("date: " + date + " gender: "+gender+" level: "+level);
	//pass this data to php file
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getLeaderboardContent.php",         
	  data: {"date" : date, 
			"gender" : gender,
			"level" : level}, //insert arguments here to $_POST
	  dataType: "json",  //data format      
	  success: function(response) //on receive of reply
	  {
		json_object = [];
		console.log("got leaderboard content!!!");
		type_of_wod_main = response[0].type_of_wod;
		loadLeaderBoardData(response);
		console.log(type_of_wod_main);
		returnRatings(response, type_of_wod_main);
		if(type_of_wod_main == "MIXED") {
			loadTestdata(response, type_of_wod_main);
		}
	  },
  	  error: function(error){
    		console.log('error receiving leaderboard content!' + error);
  		}
	});
	
}
/******************************************************/
function serializeForm() {
	console.log("SERIALIZE");
	myScore = -1;
	var compare_data = $("#what_to_compare_form").serializeArray();
	$('#display_workout').empty();
	var redirect_string = "search";
	$.each(compare_data, function(i, field){
    	//console.log("FORM DATA: " +field.name + ":" + field.value + " ");
		if(field.name == "core_type_selector") {
			redirect_string = "core";
		} else if(field.name.indexOf("open") > -1) {
			redirect_string = "open";
			if(field.name.indexOf("open_wod_") > -1) {
				field.value = field.value.substring(0, 2) + "." + field.value.substring(2);
			}
		}
  	});
	
	if(redirect_string == "core") {
		searchForCoreLifts();
	} else if(redirect_string == "open") {
		searchForOpen(compare_data);
	} else { 
		search();
	}
}

function search() {
	console.log("SEARCHING");
	myScore = -1;
	var compare_data = $("#what_to_compare_form").serializeArray();
	$('#display_workout').empty();
	$.each(compare_data, function(i, field){
    	//console.log("FORM DATA: " +field.name + ":" + field.value + " ");
  	});
	
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"compare_query.php",         
	  data: compare_data, //insert argumnets here to pass to getAdminWODs
	  dataType: "json",                //data format      
	  success: function(response) //on recieve of reply
	  {
		  //console.log("response_wods: " + response);
		  loadCompareData(response);
	  },
  	  error: function(error){
    		console.log('error loading wods!' + error);
  		}
	});
}

function today_compare() {
	console.log("COMPARE TODAY");
	var compare_data = $("#what_to_compare_form").serializeArray();
	$('#display_workout').empty();
	$.each(compare_data, function(i, field){
    	console.log("FORM DATA: " +field.name + ":" + field.value + " ");
  	});
	
	compare_data.push({ name: "date", value: today });
	
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"compare_getWOD.php",         
	  data: compare_data, //insert argumnets here to pass to getAdminWODs
	  dataType: "json",                //data format      
	  success: function(response) //on recieve of reply
	  {
		  console.log("response today compare: " + response);
		  loadTodayWOD(response);
	  },
  	  error: function(error){
    		console.log('error loading todays wod!' + error);
  		}
	});
}

function searchForCoreLifts() {
	
	console.log("SEARCHING FOR CORE ");
	var compare_data = $("#what_to_compare_form").serializeArray();

	$.each(compare_data, function(i, field){
    	//console.log("FORM DATA: " +field.name + ":" + field.value + " ");
  	});
	
	$.ajax(
	{ 
		type:"POST",                                     
		url:"getUserCompareCoreBmks.php",         
		data: compare_data, //insert arguments here to pass to getAdminWODs
		dataType: "json",                //data format      
		success: function(response) //on receive of reply
		{
		  //console.log("response searchForCoreLifts: " + response);
		  loadBenchmarkData(response);
		  //graphCoreBMData(response);
		},
		error: function(error){
			console.log('error loading selected benchmark! ' + error);
		}
	});
	
}


/******************** Load the tables ************************/
  
function loadCompareData(data_wods) {
	var t_data = data_wods;
	
	var html_sec1 = "";
	var sec1_classID = "wod_sec1_data"; 
	var date_link_id = "date_link_";
	var dow = "";
	var name;
	var level = "";
	var descrip = "";
	var time = "";
	/*console.log("loadPastWODS PRE-FOR LOOP");
	console.log("DATA: " + data_wods);
	console.log("t_DATA: " + t_data);*/
	for(var i = 0; i < data_wods.length; i++) {
		console.log("data[i].dateofwod: " +data_wods[i].date_of_wod);
		console.log("data[i].type_of_wod: " + data_wods[i].type_of_wod);
		console.log("data[i].rx_description: " + data_wods[i].rx_descrip);
		console.log("data[i].time: " + data_wods[i].time);
		console.log("data[i].rounds: " + data_wods[i].rounds);
		
		tow = data_wods[i].type_of_wod;		
		dow = data_wods[i].date_of_wod;
		level = data_wods[i].level_perf;
		
		descrip = data_wods[i].rx_descrip;
		
		time =  data_wods[i].time;
		rounds = data_wods[i].rounds;
		date_link_id = "date_link_"+i;
		console.log("date link id: " + date_link_id);
		//header_wod = "Past WODs";
		var temp_descrip = "";
		if(descrip.length > 42) {
			temp_descrip = descrip.substring(0, 39);
			console.log(temp_descrip);
			descrip = temp_descrip + "...";
		}
		
		html_sec1 += "<tr class="+sec1_classID+">";
		html_sec1 += "<td><div class=\"tdDivBox\" id=\"tdDivBox\"><a class=\"date_link\" id=\""+date_link_id+"\" href=\"#\">"+dow+"</a></div></td>";
		html_sec1 +="<td class=\"pastwod_descrip\">"+tow+"</td>";
		html_sec1 += "<td>EMPTY</td>";
		html_sec1 += "</tr>";
	}
	//Update html content
	//alert("HTML: " + html);
	$('#display_workout').empty();
	//$('#display_workout').append(header_wod);
	$('.tbl_body_wod_list').empty();
	$('.tbl_body_wod_list').html(html_sec1);
	header_wod = "";
}
 
function loadLeaderBoardData(data_leaders) {
	var t_data = data_leaders;
	var html_sec1 = "";
	var sec1_classID = "leaderboard_data"; 
	var date_link_id = "leader_";
	var score = "";
	var name = "";
	var descrip = "";
	var t_wod_type = "";
	var mix_index = 0;
	console.log("loadLeaderboardData PRE-FOR LOOP");
	
	var tempTimeArray = new Array();
	
	if(data_leaders.length > 0) {
		t_wod_type = data_leaders[0].type_of_wod;
	}
	
	for(var i = 0; i < data_leaders.length; i++) {
		
		if(typeof data_leaders[i].descrip != undefined) {
			//console.log("data[i].descrip: " +data_leaders[i].descrip);
			//console.log("userid data[i]: " + data_leaders[i].user_id);
			//console.log("user id php: " + <?php echo $_SESSION['MM_UserID']; ?>);
			
			main_description = data_leaders[i].descrip;
			name = data_leaders[i].name;
			score = data_leaders[i].score;
			
			if(t_wod_type == "MIXED") {
				mix_index = score.indexOf("Final");
				if(mix_index > -1) {
					score = score.substring(mix_index+6);
				}	
			}
			
			html_sec1 += "<tr class="+sec1_classID+" id=\"leader_"+i+"\">";
			
			if(data_leaders[i].user_id == "<?php echo $_SESSION['MM_UserID']; ?>") {
				//console.log("user id = " + data_leaders[i].user_id);
				html_sec1 += "<td><div class=\"tdDivNameOfAthlete\" id=\"tdDivBox\" style=\"background-color:yellow\">"+name+"</div></td>";
				html_sec1 +="<td class=\"tdDivScore\" style=\"background-color:yellow\">"+score+"</td>";
			} else {
				html_sec1 += "<td><div class=\"tdDivNameOfAthlete\" id=\"tdDivBox\">"+name+"</div></td>";
				html_sec1 +="<td class=\"tdDivScore\">"+score+"</td>";
			}
			html_sec1 += "</tr>";
		} else {
			console.log("No data for leaderboard");
		}
		
	}
	
	//Update html content
	$("#wod_actual_description").empty();
	$("#wod_actual_description").html(main_description);
	$('.tbl_body_leaderboard').empty();
	$('.tbl_body_leaderboard').html(html_sec1);
}
/*
 * Today's wod results
 */
function loadTodayWOD(data_wods) {
	var html_sec1 = "";
	var sec1_classID = "wod_sec1_data"; 
	var name;
	var descrip = "";
	var score = "";

	console.log("data[0].descrip: " +data_wods[0].descrip);
	console.log("data[0].descrip: " +data_wods[0].descrip);
	descrip = data_wods[0].descrip;

	html_sec1 += descrip;
		
	//Update html content
	$('#wod_list').empty();
	$('#wod_list').html(html_sec1);
	var result = today.replace(/-/g, "");
	var gender_type = $('input[name=gender_to_compare]:checked').val()
	var e = document.getElementById("today_compare_selector");
	var level = e.options[e.selectedIndex].value;
	console.log("Gender: " + gender_type);
	console.log("Level selector: " + level);
	if(typeof gender_type !== "undefined" && gender_type != "A") {
		result += ","+gender_type;
	}
	else {
		result += ", ";
	}
	result += ","+level;
	console.log("Result: " + result);
	getLeaderBoardData(result);
}

var myScore = 0;
function loadBenchmarkData(data) {
	var t_data = data;
	var html_sec1 = "";
	var sec1_classID = "leaderboard_data"; 
	var score = "";
	var name = "";

	console.log("loadBenchmarkData PRE-FOR LOOP");
	for(var i = 0; i < data.length; i++) {
		
		if(typeof data[i].date_achieved != undefined) {
			//console.log("data[i].name: " +data[i].name);
			//console.log("data[i].weight: " + data[i].score);
			//console.log("user id php: " + <?php echo $_SESSION['MM_UserID']; ?>);
			
			name = data[i].name;
			score = data[i].score;

			html_sec1 += "<tr class="+sec1_classID+" id=\"bm_"+i+"\">";
			
			if(data[i].user_id == "<?php echo $_SESSION['MM_UserID']; ?>") {
				//console.log("user id = " + data[i].user_id);
				html_sec1 += "<td><div class=\"tdDivBM\" id=\"tdDivZ\" style=\"background-color:yellow\">"+name+"</div></td>";
				html_sec1 +="<td class=\"tdDivScore\" style=\"background-color:yellow\">"+score+"</td>";
				myScore = score;
			} else {
				html_sec1 += "<td><div class=\"tdDivBM\" id=\"tdDivZ\">"+name+"</div></td>";
				html_sec1 +="<td class=\"tdDivScore\">"+score+"</td>";
			}
			
			html_sec1 += "</tr>";
		} else {
			console.log("No data for leaderboard");
		}
		
	}
	
	//Update html content
	$("#wod_actual_description").empty();
	$("#wod_actual_description").html(main_description);
	$('.tbl_body_leaderboard').empty();
	$('.tbl_body_leaderboard').html(html_sec1);
	
	openCustomModal(data, true);
}


/***************************** Generate ratings ****************************************/
var arrayToSend = new Array();
function returnRatings(data_for_array, wod_type) {
	var avg = 0;
	var sum = 0;
	var t_count = 0;
	var tempRate = 0;
	var name = "";
	var score = 0;
	var rating = 0;
	
	console.log("type pf wod: "+wod_type+", Scores: ");
	if(wod_type == "AMRAP" || wod_type == "amrap") {
		avg = calculateAMRAPRating(data_for_array);
	} else if(wod_type == "MIXED" || wod_type == "mixed") {
		avg = calculateMixedRating(data_for_array);
	} else {
		avg = calculateRFTRating(data_for_array);
		
	}
	arrayCurrent(arrayToSend);
	loadGraphs(wod_type, arrayToSend);
	$('#avg_score').empty();
	$('#avg_score').html(avg);
}

function calculateRFTRating(rft_data) {
	var avg = 0;
	var total_sum = 0;
	var user_sum = 0;
	var t_sum = 0;
	var t_count = 0;
	var tempRate = 0;
	var name = "";
	var score = 0;
	var rating = 0;
	var scoreInSeconds = 0;
	var hours = 0;
	var minutes = 0;
	var seconds = 0;
	var user_time = "";
	
	console.log("Scores: ");
	for(var i = 0; i < rft_data.length; i++) {
		console.log(rft_data[i].score + " "+ rft_data[i].name);
		t_count = i+1;
	}
	console.log("Converting scores to seconds...");
	//console.log("Scores (in seconds): ");
	var tempScoreString = "";
	for(var i = 0; i < rft_data.length; i++) {
		tempScoreString = rft_data[i].score;
		var tempCount = 0;
		if(tempScoreString.substring(0,tempScoreString.indexOf(":")) != "00") {
			hours = parseInt(tempScoreString.substring(0,tempScoreString.indexOf(":")));
			hours = hours*(3600);
		}
		
		tempScoreString = tempScoreString.substring(3);
		if(tempScoreString.substring(0,tempScoreString.indexOf(":")) != "00") {
			minutes = parseInt(tempScoreString.substring(0,tempScoreString.indexOf(":")));
			minutes = minutes * 60;
		}
		
		if(tempScoreString.substring(tempScoreString.indexOf(":")+1) != "00") {
			seconds = parseInt(tempScoreString.substring(tempScoreString.indexOf(":")+1));
		}
		
		user_sum = hours + minutes + seconds
		total_sum = total_sum + user_sum;
		console.log("Hours: "+hours);
		console.log("Minutes: "+minutes);
		console.log("seconds: "+seconds);
		console.log("User Score: " + user_sum);
		console.log("Total: " + total_sum);
		rft_data[i].temporary_score = user_sum;		
		user_sum = 0;
	}
	console.log("Scores (in seconds): ");
	for(var i = 0; i < rft_data.length; i++) {
		
		if(rft_data[i].score.substring(0, 3) == "00:") {
				console.log(rft_data[i].score.substring(3));
				rft_data[i].score = rft_data[i].score.substring(3);
		}
		console.log(rft_data[i].score + " "+ rft_data[i].name + ", temp: " + rft_data[i].temporary_score);
		var tempArray = new Array();
		name = rft_data[i].name;
		score = rft_data[i].temporary_score;
		user_time = rft_data[i].score;
		avg = (total_sum/t_count);
		console.log("Avg: "+avg);
		tempRate = ((score/avg)*100)-100;
		console.log("temprate: "+tempRate)
		
		rating = (100-getRating(tempRate, score, avg));
		console.log( name + ", "+ score+", "+rating);
		tempArray.push(name);
		tempArray.push(user_time);
		tempArray.push(score);
		tempArray.push(parseFloat(rating).toFixed(2));
		for(var t= 0; t< tempArray.length; t++) {
			console.log("tempArray values:" + tempArray[t]);
		}
		arrayToSend[i] = tempArray;
	}
	var returnString= "";
	var tempString = "";
	var tempInt = 0;
	if(avg < 3600) {
		tempString = parseFloat(avg/60).toFixed(2);
		returnString = tempString.substring(0, tempString.indexOf("."));
		tempInt = parseInt(tempString.substring(tempString.indexOf(".")+1));
		tempInt = tempInt/100;
		console.log("tempInt: " + tempInt);
		tempInt = parseInt(tempInt * 60);
		returnString += ":"+tempInt;
	}
	return returnString;
}


function calculateAMRAPRating(amrap_data, test_val) {
	var avg = 0;
	var sum = 0;
	var t_count = 0;
	var tempRate = 0;
	var name = "";
	var score = 0;
	var rating = 0;
	
	console.log("Scores: ");
	for(var i = 0; i < amrap_data.length; i++) {
		console.log(amrap_data[i].score + " "+ amrap_data[i].name);
		sum = sum + parseInt(amrap_data[i].score);
		t_count = i+1;
	}
	console.log("sum: "+sum);
	avg = (sum/t_count);
	console.log(avg);
	for(var i = 0; i < amrap_data.length; i++) {
		var tempArray = new Array();
		name = amrap_data[i].name;
		score = amrap_data[i].score;
		
		tempRate = ((score/avg)*100)-100;
		console.log("temprate: "+tempRate)
		
		var opt_value = (typeof test_val === "undefined") ? "F" : test_val;
		if(opt_value == "F") {
			rating = getRating(tempRate, score, avg);
		} else {
			rating = (100-getRating(tempRate, score, avg));
		}
		console.log( name + ", "+ score+", "+rating);
		tempArray.push(name);
		tempArray.push(score);
		tempArray.push(parseFloat(rating).toFixed(2));
		for(var t= 0; t< tempArray.length; t++) {
			console.log("tempArray values:" + tempArray[t]);
		}
		arrayToSend[i] = tempArray;
	}
	return avg;
}

function calculateMixedRating(mixed_data) {
	var avg = 0;
	var sum = 0;
	var t_count = 0;
	var tempRate = 0;
	var name = "";
	var score = 0;
	var t_score = 0;
	var rating = 0;
	var mix_index_comma = 0;
	var mix_index_underscore = 0;	
	var countForScores = 0;
	var t_string = mixed_data[0].score;
	
	while(t_string.indexOf(",") > -1) {
		console.log(t_string.indexOf(","));
		console.log("T_STRING: "+t_string);
		t_string = t_string.substring(mixed_data[0].score.indexOf(",")+1, t_string.length);
		countForScores++;
	}
	countForScores = countForScores - 1;
	console.log("Count for num of scores: "+countForScores+", Scores: ");
	for(var j = 0; j < countForScores; j++) {
		for(var i = 0; i < mixed_data.length; i++) {
			mix_index_comma = mixed_data[i].score.indexOf(",");
			mix_index_underscore = mixed_data[i].score.indexOf("_");
			if(mix_index_comma > -1) {
				t_score = mixed_data[i].score.substring(mix_index_underscore+1, mix_index_comma);
			}	
			console.log(t_score + " "+ mixed_data[i].name);
			sum = sum + parseInt(t_score);
			t_count = i+1;
		}
		
		console.log("sum: "+sum);
		avg = (sum/t_count);
		console.log(avg);
		for(var i = 0; i < mixed_data.length; i++) {
			var tempArray = new Array();
			mix_index_comma = mixed_data[i].score.indexOf("Final");
			if(mix_index_comma > -1) {
				score = parseInt(mixed_data[i].score.substring(mix_index_comma+6));
			}	
			name = mixed_data[i].name;
			
			tempRate = ((score/avg)*100)-100;
			console.log("temprate: "+tempRate)
			
			rating = getRating(tempRate, score, avg);
			console.log( name + ", "+ score+", "+rating);
			tempArray.push(name);
			tempArray.push(score);
			tempArray.push(parseFloat(rating).toFixed(2));
			for(var t= 0; t< tempArray.length; t++) {
				console.log("tempArray values:" + tempArray[t]);
			}
			arrayToSend[i] = tempArray;
		}
	}
	return parseFloat(avg).toFixed(2);
}

function getRating(tempRate, score, average) {
	var rating = 0;
	if(tempRate < 0) {
		tempRate = (tempRate*(-1));
	}
	if(score < average) {
		if(tempRate > 50) { 
			rating = 1;
		} else {
			rating = 50 - tempRate;
		}
	} else {
		if(tempRate > 50) {
			rating = 100;
		} else {
			rating = 50 + tempRate;
		}
	}
	return rating;
}

function arrayCurrent(arrayToSee) {
	for(var i = 0; i < arrayToSee.length; i++) {
		console.log("Current Data: " + arrayToSee[i][0]+ ", " +arrayToSee[i][1]  + ", " +arrayToSee[i][2]);
	}
}

/********************************* Load and draw graph functions***********************************************/

function loadGraphs(wod_type, data_array, test_val)
{
	console.log("load graphs: " + wod_type);
	console.log(test_val);
	var opt_value = (typeof test_val === "undefined") ? "F" : test_val;
	console.log(opt_value);
	drawComparisonAMRAPChart(data_array, wod_type, opt_value);
}
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawComparisonAMRAPChart(scores, wod_type, test_val) {

	var opt_value = (test_val === "T") ? "T" : test_val;
	// Create the data table.

	var data = new google.visualization.DataTable();
	var min = 9999;
	var max = 0;
	var vAxisTitle = "";
	console.log("SCORES: " );
	
	
	if(wod_type == "AMRAP" || wod_type == "amrap") {
	data.addColumn('number', 'Rating');
	data.addColumn('number', 'Score');
	data.addColumn({type:'string',role:'tooltip'});
		for(var i = 0; i < scores.length; i++) {
			var name = String(scores[i][0]);
			var parsedScore = parseInt(scores[i][1]);
			var parsedRating = parseFloat(scores[i][2]);
			console.log(name +" " +parsedScore + " "+ parsedRating);
			data.addRows([
				[parsedRating, parsedScore, name+ " \nScore: "+parsedScore+" \nRating: " +parsedRating ],
			]);
			if(parsedScore < min) {
				min = parsedScore;
			}
			if(parsedScore > max) {
				max = parsedScore;
			}
		}
		vAxisTitle = "Scores";
		var options = {
			title: 'Athlete Rating',
			vAxis: {title: vAxisTitle, minValue: (min*.8), maxValue: (max*1.2)},
			hAxis: {title:'Rating',minValue: 0, maxValue:  100},
			'width':500,
			'height':400, 
			'chartArea': {'width': '80%', 'height': '80%'},
			'legend': {'position': 'bottom'}
		};
	} else if(wod_type == "MIXED" || wod_type == "MIXED") {
	data.addColumn('number', 'Rating');
	data.addColumn('number', 'Score');
	data.addColumn({type:'string',role:'tooltip'});
		for(var i = 0; i < scores.length; i++) {
			var name = String(scores[i][0]);
			var parsedScore = parseInt(scores[i][1]);
			var parsedRating = parseFloat(scores[i][2]);
			console.log(" " +parsedScore + " "+ parsedRating);
			data.addRows([
				[parsedScore, parsedScore, name+ " \nScore: "+parsedScore+" \nRating: " +parsedRating ],
			]);
			if(parsedScore < min) {
				min = parsedScore;
			}
			if(parsedScore > max) {
				max = parsedScore;
			}
		}
		console.log("MAX: " + max + " MIN: " + min);
		vAxisTitle = "Scores";
		var options = {
			title: 'Athlete Rating',
			vAxis: {title: vAxisTitle, minValue: (min*.8), maxValue: (max*1.2)},
			hAxis: {title:'Rating',minValue: (min*.8), maxValue:  (max*1.2)},
			'width':500,
			'height':400, 
			'chartArea': {'width': '80%', 'height': '80%'},
			'legend': {'position': 'bottom'}
		};
	} else {
	data.addColumn('number', 'Rating');
	data.addColumn('number', 'Score');
	data.addColumn({type:'string',role:'tooltip'});
		for(var i = 0; i < scores.length; i++) {
			var name = String(scores[i][0]);
			
			var hours;
			var minutes;
			var seconds;
			var times = "";
			console.log("opt_value:" + opt_value);
			if(opt_value == "T") {
				//reverse engineer the time
				var t_score = scores[i][1];
				
				console.log("T-SCORE:" + t_score);
				
				var hrs = ~~(parseInt(t_score) / 3600);
				var mins = ~~((parseInt(t_score) % 3600) / 60);
				var secs = parseInt(t_score) % 60;
				
				if(hrs > 0) 
				{
					times = hrs + ":";
				} 
				if(mins > 0) {
					times += mins + ":";
				} else {
					times += "00:";
				}
				if(secs > 0) {
					times += secs;
				} else {
					times += "00";
				}
				
				console.log(times);
				
				var parsedSeconds = parseInt(scores[i][1]);
				var parsedRating = parseFloat(scores[i][2]);
				
				console.log(name +" " +parsedScore + " "+ parsedRating);
				data.addRows([
					[parsedRating, parsedSeconds, name+ " \nTime: " +times + " \nRating: " + parsedRating ],
				]);
				
			} else {
				var parsedScore = parseInt(scores[i][1]);
				var parsedSeconds = parseInt(scores[i][2]);
				var parsedRating = parseFloat(scores[i][3]);
				
				console.log(name +" " +parsedScore + " "+ parsedRating);
				data.addRows([
					[parsedRating, parsedSeconds, name+ " \nTime: " +scores[i][1] + " \nRating: " + parsedRating ],
				]);
				
			}
			
			if(parsedSeconds < min) {
				min = parsedSeconds;
			}
			if(parsedSeconds > max) {
				max = parsedSeconds;
			}
		}
		vAxisTitle = "Time (in seconds)";
		var options = {
			title: 'Athlete Rating',
			vAxis: {title: vAxisTitle, minValue: (min*.8), maxValue: (max*1.2)},
			hAxis: {title:'Rating',minValue: 0, maxValue:  100},
			'width':500,
			'height':400, 
			'chartArea': {'width': '80%', 'height': '80%'},
			'legend': {'position': 'bottom'}
		};
	}

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
	chart.draw(data, options);
	
	//clear data from the main array
	while(arrayToSend.length > 0){
		arrayToSend.pop();
	}
}
 
function graphCoreBMData() {

}
 
function loadTestdata(test_array, wod) {
	my_test_array = new Array();
	var t_string = "";
	var countForScores = 0;
	var label = "";
	
	console.log("TEST DATA: ");
	for(var i = 0; i < test_array.length; i++) {
		//console.log("Name: " + test_array[i].name +" Score: " +test_array[i].score+" User ID: " +test_array[i].user_id);
		
		item = {};
		t_string = test_array[i].score;
		
		item["name"] = test_array[i].name;
		item["id"] = test_array[i].user_id;
		item["scores"] = {};
		while(t_string.indexOf(",") > -1) {
			console.log(t_string.indexOf(","));
			//console.log("T_STRING: "+t_string);
			//console.log("SUBSTRING: " + t_string.substring(0, t_string.indexOf(",")));
			
			if(t_string.substring(0, t_string.indexOf(",")).length > 0) {
				label = "score_" + countForScores;
				item_two = t_string.substring(0, t_string.indexOf(","));
				item["scores"][countForScores] = item_two;
				countForScores++;
			}
			t_string = t_string.substring(test_array[i].score.indexOf(",")+1, t_string.length);
		}
		json_object.push(item);
		dynCount = countForScores;
		countForScores = 0;
	}
	console.log("FIN TEST DATA");
	
	var html = buildSelectorFromMixedWOD(dynCount);
	
	$("#mixed_leaderboard_display").append('<p></p><input onclick="myTestHistogram();" type="button" id="test_button" value="Test Histogram"/>');
	$("#mixed_leaderboard_display").append(html);
	//console.log("JSON STRINGIFY: " + JSON.stringify(json_object));
}
 
function myTestCalculator(partToGraph) {
	console.log("Part to graph: " + partToGraph);
	var type_of_score = "";
	var avg = 0;
	//for(var j = 0; j < dynCount; j++) {
		var temp_score_array = [];
		for(var i = 0; i < json_object.length; i++) {
			console.log("Name: "+json_object[i].name+" ID: "+json_object[i].id +" Score "+partToGraph+": "+json_object[i].scores[partToGraph]);
			item = {};
			item["name"] = json_object[i].name;
			item["id"] = json_object[i].user_id;
			item["score"] = json_object[i].scores[partToGraph].substring(json_object[i].scores[partToGraph].indexOf("_")+1);
			type_of_score = json_object[i].scores[partToGraph].substring(0,1);
			temp_score_array.push(item);
		}
		console.log("JSON STRINGIFY: " + JSON.stringify(temp_score_array));
		if(type_of_score == "A") {
			console.log("Send to AMRAP score calc");
			//pass to method that returns an ordered array based on score
			avg = calculateAMRAPRating(temp_score_array);
			arrayCurrent(arrayToSend);
			loadGraphs("AMRAP", arrayToSend);
			$('#avg_score').empty();
			$('#avg_score').html(avg);
		} else if(type_of_score == "R") {
			console.log("Send to RFT score calc");
			//pass to method that returns an ordered array based on score
			avg = calculateAMRAPRating(temp_score_array, "T");
			arrayCurrent(arrayToSend);
			loadGraphs("RFT", arrayToSend, "T");
			$('#avg_score').empty();
			$('#avg_score').html(avg);
		}
}

function buildSelectorFromMixedWOD(numberOfParts) {
	var html = "";
	
	html = '<select id="mixed_wod_selector" name="mixed_wod_selector" class="selector">';
	html += '<option value="NONE"> - </option>';
	for(var i = 0; i < numberOfParts; i++) {
			html += '<option value="'+i+'"> Part '+(i+1)+' </option>';
	}
	html += '</select>';
	return html;
}

function openCustomModal(data, dialog) {

	var temp_data = new Array();
	
	var min = 9999;
	var max = 0;
	var bins = 1;
	var t_score = 0;
	var lowBand = 9999;
	var highBand = 0;
	var dataLength = data.length;
	if(type_of_wod_main == "AMRAP") {
		for(var i = 0; i < data.length; i++) {
			t_score = parseInt(data[i].score);
			temp_data[i] = data[i].score;

			if(t_score < min) {
				min = t_score;
			}
			
			if(t_score > max) {
				max = t_score;
			}
		}
		if(dataLength < 10) {
			bins = ~~(dataLength / 2)
		} else if(dataLength < 25) {
			bins = ~~(dataLength / 5)	
		} else if(dataLength < 50) {
			bins = ~~(dataLength / 8)	
		} else if(dataLength < 100) {
			bins = ~~(dataLength / 10)	
		} else if(dataLength < 200) {
			bins = ~~(dataLength / 20)
		} else if(dataLength < 300) {
			bins = ~~(dataLength / 30)
		} else if(dataLength < 400) {
			bins = ~~(dataLength / 40)
		} else if(dataLength < 500) {
			bins = ~~(dataLength / 50)
		} else if(dataLength < 600) {
			bins = ~~(dataLength / 60)
		} else if(dataLength < 700) {
			bins = ~~(dataLength / 70)
		} else if(dataLength < 800) {
			bins = ~~(dataLength / 80)
		} else if(dataLength < 900) {
			bins = ~~(dataLength / 90)
		} else if(dataLength < 1000) {
			bins = ~~(dataLength / 100)
		} else {
			bins = ~~(dataLength / 200)
		}
		
		if(min === 1) {
			lowBand = min;
		} else {
			lowBand = min;
		}
		
		highBand = max;
	} else {
		for(var i = 0; i < data.length; i++) {
			t_score = data[i].conScore;
			temp_data[i] = data[i].conScore;
			//console.log(temp_data[i] + ", " + t_score);
			if(t_score < min) {
				min = t_score;
			}
			
			if(t_score > max) {
				max = t_score;
			}
		}
		
		if(dataLength < 10) {
			bins = ~~(dataLength / 2)
		} else if(dataLength < 25) {
			bins = ~~(dataLength / 5)	
		} else if(dataLength < 50) {
			bins = ~~(dataLength / 8)	
		} else if(dataLength < 100) {
			bins = ~~(dataLength / 10)	
		} else if(dataLength < 200) {
			bins = ~~(dataLength / 20)
		} else if(dataLength < 300) {
			bins = ~~(dataLength / 30)
		} else if(dataLength < 400) {
			bins = ~~(dataLength / 40)
		} else if(dataLength < 500) {
			bins = ~~(dataLength / 50)
		} else if(dataLength < 600) {
			bins = ~~(dataLength / 60)
		} else if(dataLength < 700) {
			bins = ~~(dataLength / 70)
		} else if(dataLength < 800) {
			bins = ~~(dataLength / 80)
		} else if(dataLength < 900) {
			bins = ~~(dataLength / 90)
		} else if(dataLength < 1000) {
			bins = ~~(dataLength / 100)
		} else {
			bins = ~~(dataLength / 200)
		}
		
		if(min === 1) {
			lowBand = min;
		} else {
			lowBand = min;
		}
		
		highBand = max;
	}
	console.log("BINS: "+bins+", lowBand: " + lowBand + ", highBand: " + highBand + ", type of wod: "+ type_of_wod_main);
	var width = 500, height = 400;
	var barPadding = 1;  
	//Create SVG element
	if(dialog == false || typeof dialog === "undefined") {
		$( "#dialog-modal" ).dialog({
		  height: 500,
		  width: 600,
		  modal: true
		});
		d3.select('.chart')
		.datum(temp_data)
		.call(histogramChart()
			.width(width)
			.height(height)
			.lowerBand(lowBand)
			.upperBand(highBand)
			.bins(10)
			.yAxisLabel("# of Orgs")
			.xAxisLabel("# of FooBars")  
		);
		$( "#dialog-modal" ).dialog();
	} else {
		
		d3.select('#chart_div')
		.datum(temp_data)
		.call(histogramChart()
			.title("Scores")
			.width(width)
			.height(height)
			.lowerBand(lowBand)
			.upperBand(highBand)
			.bins(bins)
			.yAxisLabel("# of Athletes")
			.xAxisLabel("Score")  
		);
	}
	//myScore = 230;
	var range = 0;

	d3.selectAll('.tick')
		.each(function(d, i) {
			
			console.log("D: " + d +" I: " + i);	
			if(i == 0) {
				range = d;
			} else if(i==1){
				range = d - range;
				d = "TEST";
			}
		}
	);
	console.log("Stop here");
	var t_myScore = String(myScore);
	if(t_myScore.indexOf("0") > -1) {
		myScore++;
	}
	d3.selectAll('.tick')
		.each(function(d, i) {
			//console.log("D: " + d +" I: " + i);
			var temp_score = 0;
			for(var t = 0; t < range; t++) {
				temp_score = myScore - t;
				if(temp_score == d) {
					d3.selectAll("#rect_"+i+"").style("fill", "red");
					break;
				}
			}
			
		}
	);
}

function histogramChart(){
    var margin = {
        top: 64,
        right: 32,
        bottom: 72,
        left: 32,
        labels: 32
    };
    //defaults
    var height = 200;
    var width = 500;
    var lowerBand = 0;
    var upperBand = 100;
    var bins = 10;
    var chartTitle = ["test"];
    var yAxisLabel = "y axis label";
    var xAxisLabel = "x axis label";
	
    var xformat = function(d){
						var timeString = "";
						if(type_of_wod_main == "RFT"){
							var hours = Math.floor(d / 3600);
							d %= 3600;
							minutes = Math.floor(d / 60);
							seconds = d % 60;
							if(hours < 10) {
								timeString = "0"+hours;
							} else {
								timeString = hours;
							}
							
							if(timeString.length > 0) {
								if(minutes > 0 && minutes < 10) {
									timeString += ":0"+minutes+":";
								} else if(minutes > 9 && minutes < 60) {
									timeString += ":"+minutes+":";
								} else {
									timeString += ":00:";
								}
								if(seconds > 0 && seconds < 10) {
									timeString += "0"+seconds;
								} else if(seconds > 9 && seconds < 60) {
									timeString += ""+seconds;
								} else {
									timeString += "00";
								}
							} else {
								if(minutes > 0 && minutes < 10) {
									timeString = "0"+minutes+":";
								} else if(minutes > 9 && minutes < 60) {
									timeString = ""+minutes+":";
								} else {
									timeString = "00:";
								}
								if(seconds > 0 && seconds < 10) {
									timeString += "0"+seconds;
								} else if(seconds > 9 && seconds < 60) {
									timeString += ""+seconds;
								} else {
									timeString += "00";
								}
							}
							return timeString;
						} else {
							return d;
						}
					};
    var formatCount = d3.format(",.0f");
    
    function chart(selection) {
        var maxBarHeight = height - (margin.top + margin.bottom);
        var chartWidth = width - margin.right - margin.left;
        
        selection.selectAll('svg').remove();//remove old charts
        
        selection.each(function(values) {
			
			//fill the chart width, with 1px spacing between
           
			var x = d3.scale.linear()
				.domain([
					lowerBand,
					upperBand 
				])
				.range([margin.labels, chartWidth]);

			console.log(x.domain());
			console.log(x.range());
			
			//generate my own tick values, and rangeRound to make the numbers "neat"
			tempScale = d3.scale.linear().domain([0, bins]).rangeRound([lowerBand, upperBand]);
			tickArray = d3.range(bins + 1).map(tempScale);
			console.log(tempScale.range());
			console.log(tickArray);
			
			var data = d3.layout.histogram()
                .bins(tickArray)
                (values);
				
			console.log("Trying to figure out some stuff...margin.labels: " + margin.labels + ", chartWidth: " + chartWidth +
			", values: " + values + ", data: " + data + ", ****data.length****: "+ data.length);
			
            var numBins = data.length;
            var barWidth = parseInt((chartWidth-margin.labels)/numBins);
				
			//console.log("Measurements... barWidth: " + barWidth +
			//", chartWidth-margin.labels: " + parseInt(chartWidth-margin.labels) + ", numBins: " + numBins);	
				
            var y = d3.scale.linear()
                .domain([0, d3.max(data, function(d) { return d.y; })])
                .range([maxBarHeight, 0]);
            
			
            var xAxis = d3.svg.axis()
                .scale(x)
                .orient("bottom")
				.tickValues(tickArray)
				.tickFormat(xformat);
                
            var svgContainer = d3.select(this).append("svg")
                .attr("class", "chart mini-column-chart")
                .attr("width", width)
                .attr("height", height)
               .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            var bar = svgContainer.selectAll(".bar")
                .data(data)
              .enter().append("g")
                .attr("class", "bar")
                .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });
            
			//rotate x axis text ticks 65 degrees if numBins over 16
			//otherwise, keep the ticks flat
			if(type_of_wod_main == "RFT") {
				var xAxisG = svgContainer.append("g")
					.attr("class", "x axis")
					.attr("transform", "translate( 0," + (height - margin.top - margin.bottom) + ")")
					.call(xAxis)
					.selectAll("text")
						.style("text-anchor", "end")
						.attr("dx", "-.8em")
						.attr("dy", ".15em")
						.attr("transform", function(d) {
							return "rotate(-65)" 
							});
			}
			else {
				if(numBins < 16) { 
					var xAxisG = svgContainer.append("g")
						.attr("class", "x axis")
						.attr("transform", "translate( 0," + (height - margin.top - margin.bottom) + ")")
						.call(xAxis);
						
				} else {
					var xAxisG = svgContainer.append("g")
						.attr("class", "x axis")
						.attr("transform", "translate( 0," + (height - margin.top - margin.bottom) + ")")
						.call(xAxis)
						.selectAll("text")
							.style("text-anchor", "end")
							.attr("dx", "-.8em")
							.attr("dy", ".15em")
							.attr("transform", function(d) {
								return "rotate(-65)" 
								});
				}
			}
                
            var header = svgContainer.append("text")
                .attr("class", "chart-title")
                .attr("x", width/2)
                .attr("text-anchor", "middle")
                .attr("dy", -32)
                .text(chartTitle);

			
			console.log("BINS: " + numBins)
			var dataset = new Array();
			for(var f = 0; f< numBins; f++) {
				dataset.push({"value":f});
			}
            bar.append("rect")
                .attr("x", 1)
                .attr("width", barWidth)
                .attr("height", function(d) { return maxBarHeight - y(d.y); })
				.attr("id", function(d, i) { return "rect_"+dataset[i].value; });

            bar.append("text")
                .attr("class", "axis-label")
                .attr("dy", "-.75em")
                .attr("y", 6)
                .attr("x", barWidth / 2)
                .attr("text-anchor", "middle")
                .text(function(d) { return formatCount(d.y); });

            xAxisG.append("text")
                .attr("class", "axis-label")
                .attr("x", margin.left)
                .attr("dy", 56)
                .text(xAxisLabel);

            svgContainer.append("g")
                .attr("class", "y axis")
                .append("text")
                .attr("class", "axis-label")
                .attr("transform", "rotate(-90)")
                .attr("y", 8)
                .attr("x", -(height-margin.top-margin.bottom))
                .style("text-anchor", "start")
                .text(yAxisLabel);
        
        });
    }


    chart.title = function(_) {
        if (!arguments.length) return chartTitle;
        chartTitle = _;
        return chart;
    };


    chart.lowerBand = function(_) {
        if (!arguments.length) return lowerBand;
        lowerBand = _;
        return chart;
    };

    chart.upperBand = function(_) {
        if (!arguments.length) return upperBand;
        upperBand = _;
        return chart;
    };

    chart.width = function(_) {
        if (!arguments.length) return width;
        width = _;
        return chart;
    };

    chart.height = function(_) {
        if (!arguments.length) return height;
        height = _;
        return chart;
    };

    chart.bins = function(_) {
        if (!arguments.length) return bins;
        bins = _;
        return chart;
    };
    
    chart.xformat = function(_) {
        if (!arguments.length) return xformat;
        xformat = _;
        return chart;
    };
    
    chart.yAxisLabel = function(_) {
        if (!arguments.length) return yAxisLabel;
        yAxisLabel = _;
        return chart;
    };

    chart.xAxisLabel = function(_) {
        if (!arguments.length) return xAxisLabel;
        xAxisLabel = _;
        return chart;
    };

    chart.focusLabel = function(_) {
        if (!arguments.length) return focusLabel;
        focusLabel = _;
        return chart;
    };

    chart.focusValue = function(_) {
        if (!arguments.length) return focusValue;
        focusValue = _;
        return chart;
    };

    return chart;
}

/***************** Crossfit Open Methods *******************************/


function whatIfModal() {
	//Load user scores from DB
	//loadUserScoresFromDB();
	
	$( "#new_open_data" ).dialog({
      height: 1000,
	  width: 800,
      modal: true
    });
	
	$( "#new_open_data" ).dialog();
	setWhatIfDivHeight("2012");
	checkForUserOpenScores();
}

$( this ).focusout(function (event) {
	var id = event.target.id;
	var value = "";
	var weightReg = /^[0-9\:]*$/;
	var timeReg = /^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/;

	
	if ( id.indexOf("input") >= 0 )
	{	
		
		value = $("#" + id).val();
		console.log("ID: " + id + ", value: " + value);
		if(!weightReg.test(value))
		{
			console.log("ERROR!");
			$("#"+id+"").addClass("input_error");
			$("#"+id+"").qtip({ 
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
					$("#"+id+"").addClass("input_error");
					$("#"+id+"").qtip({ 
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
					$("#"+id+"").removeClass("input_error");
					$("#"+id+"").qtip("destroy");
				}
			} else {
				$("#"+id+"").removeClass("input_error");
				$("#"+id+"").qtip("destroy");
			}
			
		}
	}
});

$("#tab_container").on("click", "#tab_list", function() {
	$("#division_selector").val("none");
	$("#region_selector").val("none");
	//disable submit button
	$("#yourOpenDataButton").attr("disabled","disabled");
});

$("#tab_container").on("change", "#division_selector", function() {
	var html = "";
    $( "#division_selector option:selected" ).each(function() {
		var year = $("#tab_container").find('li.active').text();
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
		$("#yourOpenDataButton").removeAttr("disabled");
		setWhatIfDivHeight(year);
		checkForUserOpenScores();
	});
});

function submitNewOpenData(year) {
	console.log("Year:" + year);
	var data = $("#"+year+"_form").serializeArray();
	$.each(data, function(i, field) {
		console.log("Name: " + field.name + ", value: " + field.value);
	});

	data.push({name:"division", value: $( "#division_selector option:selected" ).text()});
	
	// CRUD/general/insertNewOpenWOD
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
}

function submitFoundAthleteOpenData(data) {
	var year = $("#tab_container").find('li.active').text();
	var retVal = true;
	var count = 5;
	data.push({name:"date", value:today});
	data.push({name:"year", value: year});
	$.each(data, function(i, field) {
		console.log("Name: " + field.name + ", value: " + field.value);
	});
	
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"insertFoundAthleteOpenScore.php",         
	  data: data, 
	  dataType: "text",        
	  success: function(response) 
	  {
		console.log("insert OPEN WOD response: " + response);
		while(count > -1 && response.length > 0) {
			console.log("Response: " + response + ", response.substring(count): " + response.substring(count-1) + ", count: " + count);
			if(response.substring(count-1) == "1") {
				count = count - 1;
			} else {
				count = -1;
				retVal = false;
			}
			response = response.substring(0, (count));
		}
		if(retVal === true) {
			$("#dialog-modal").dialog('close');
			$( "#success-modal" ).dialog({
				height: 400,
				width: 300,
				modal: true
			});
			
			$( "#success-modal" ).dialog();
			$("#success_content").html("Successfully entered " + year + " Open data!");
			checkForUserOpenScores();
		}
	  },
  	  error: function(error){
    		console.log('error insert OPEN WOD!' + error);
  		}
	});
}

function searchForOpen(data) {
	$.each(data, function(i, field) {
		console.log("Name: " + field.name + ", value: " + field.value);
		if(field.name == "open_wod_selector") {
			$("#wod_desc_hdr").html(field.value);
			if(field.value == "14.5") {
				type_of_wod_main = "RFT";
			} else {
				type_of_wod_main = "AMRAP";
			}
		}
	});
	
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getOpenSearchResults.php",         
	  data: data, //insert arguments here to $_POST
	  dataType: "json",  //data format      
	  success: function(response) //on receive of reply
	  {
		//console.log("SEARCH OPEN WOD response: " + response + " LENGTH: "+ response.length);
		loadOpenSearchResults(response);
	  },
  	  error: function(error){
    		console.log('error search OPEN WOD!' + error);
  		}
	});
}

function loadOpenSearchResults(response_data) {
	var html_sec1 = "";
	var sec1_classID = "wod_sec1_data"; 
	var name;
	var score = "";
	var cScore = "";
	var average = "";
	var midrange = "";
	var range = "";
	var stdev = "";
	var variance = "";
	var isAggVal = false;
	var arrayToGraph = new Array();
	console.log("LENGTH: "+response_data.length);
	for(var i = 0; i < response_data.length; i++) {
		
		if(typeof response_data[i].name != undefined) {
			//console.log("data[i].descrip: " +data_leaders[i].descrip);
			//console.log("userid data[i]: " + data_leaders[i].user_id);
			//console.log("user id php: " + <?php echo $_SESSION['MM_UserID']; ?>);

			name = response_data[i].name;
			score = response_data[i].score;
			cScore = response_data[i].cScore;
			
			if(name == "average") {
				average = response_data[i].value;
				isAggVal = true;
			} else if(name == "midrange") {
				midrange = response_data[i].value;
				isAggVal = true;
			} else if(name == "range") {
				range = response_data[i].value;
				isAggVal = true;
			} else if(name == "stdev") {
				stdev = response_data[i].value;
				isAggVal = true;
			} else if(name == "vari") {
				variance = response_data[i].value;
				isAggVal = true;
			} else if(name == "open_wod_desc") {
				$("#wod_actual_description").html(response_data[i].value);
			}
			try {
				//console.log(score.substring((score.indexOf('(')+1),score.indexOf(')')));
				if(type_of_wod_main == "RFT") {
					if(cScore < 9999) {
						arrayToGraph.push( {name : "score" , score : score.substring((score.indexOf('(')+1),score.indexOf(')')), conScore : cScore} );
					}
				} else {
					arrayToGraph.push( {name : "score" , score : score.substring((score.indexOf('(')+1),score.indexOf(')')), conScore : cScore} );
				}
			}
			catch(err) {
				var text = "There was an error parsing the score to send to graph.\n\n";
				text += "Error description: " + err.message + "\n\n";
				text += "Score that was to be parsed:"+score+"\n\n";
				console.log(text);
			}
			
			//console.log("Aggregate values: " + average +" MR: "+midrange +" R: "+range +" STD: "+stdev+" VARI: "+ variance);
			
			html_sec1 += "<tr class="+sec1_classID+" id=\"leader_"+i+"\">";
			if(isAggVal == false) {
				if(response_data[i].name== "*%^&(JOSE") {
					//console.log("user id = " + response_data[i].name);
					html_sec1 += "<td><div class=\"tdDivNameOfAthlete\" id=\"tdDivBox\" style=\"background-color:yellow\">"+name+"</div></td>";
					html_sec1 +="<td class=\"tdDivScore\" style=\"background-color:yellow\">"+score+"</td>";
				} else {
					html_sec1 += "<td><div class=\"tdDivNameOfAthlete\" id=\"tdDivBox\">"+name+"</div></td>";
					html_sec1 +="<td class=\"tdDivScore\">"+score+"</td>";
				}
			}
			html_sec1 += "</tr>";
		} else {
			console.log("No data for leaderboard");
		}
		
	}
	var aggregates = "";
	
	aggregates += "<h4>Mean</h4>"+parseInt(average)+"";
	aggregates += "<h4>Mid Range</h4><p>"+midrange+"</p>";
	aggregates += "<h4>Range</h4><p>"+range+"</p>";
	aggregates += "<h4>Standard Deviation</h4><p>"+stdev+"</p>";
	aggregates += "<h4>Variance</h4><p>"+variance+"</p>";
	//Update html content
	$('.tbl_body_leaderboard').empty();
	$('.tbl_body_leaderboard').html(html_sec1);
	$('#wod_list').empty();
	$('#wod_list').html(aggregates);
	
	$('#avg_score').empty();
	$('#avg_score').html(parseInt(average));
	openCustomModal(arrayToGraph, "AS")
	
}

function setWhatIfDivHeight(year) {
	if(year == "2012") {
		$('#tab1').children('form').children('div').each(function (i, field) { 
			var select = $(this);
			var id = select.attr("id");
			console.log("ID: " + id);
			var t_id = "";
			if(id.indexOf("_container") > -1) {
				t_id = id.substring(0, 4);
				console.log(t_id + ", height: " + $("#"+id+"").height());
				$("#"+t_id+"whatIf").height($("#"+id+"").height());
			}
		});
	} else if(year == "2013") {
		$('#tab2').children('form').children('div').each(function (i, field) { 
			var select = $(this);
			var id = select.attr("id");
			console.log("ID: " + id);
			var t_id = "";
			if(id.indexOf("_container") > -1) {
				t_id = id.substring(0, 4);
				console.log(t_id + ", height: " + $("#"+id+"").height());
				$("#"+t_id+"whatIf").height($("#"+id+"").height());
			}
		});
	} else if(year == "2014") {
		$('#tab3').children('form').children('div').each(function (i, field) { 
			var select = $(this);
			var id = select.attr("id");
			console.log("ID: " + id);
			var t_id = "";
			if(id.indexOf("_container") > -1) {
				t_id = id.substring(0, 4);
				console.log(t_id + ", height: " + $("#"+id+"").height());
				$("#"+t_id+"whatIf").height($("#"+id+"").height());
			}
		});
	}
}

/*
	Checks our db for user entered scores
	Returns an array of wod ids and associated scores from the open
*/
var jsonObj = ""; 
var boolForOpenScore = false;
function checkForUserOpenScores() {
	var year = $("#tab_container").find('li.active').text();
	
	var dataToSend = year.substring(2);
	console.log("Check for user open scores year: " + year + " dataToSend: " + dataToSend);
	$.ajax({
		type: "POST",
		url: "getUserOpenWODScores.php",
		data: {yr:dataToSend},
		dataType: "text",
		success: function(response) {
			console.log(response);
			if(response.substring(0,1) == "1") {
				jsonObj = "";
				boolForOpenScore = false;
			} else {
				jsonObj = jQuery.parseJSON(response);
				console.log(jsonObj);
				boolForOpenScore = true;
			}
		}
	});
}
/*
* User must manually hit submit button to check What if scores and load them
*/
function toLoadUserOpenScores() {
	loadUserScoresFromDB(jsonObj, boolForOpenScore);
	searchForOpenWODPlace();
}

function loadUserScoresFromDB(data, bool) {
	var year = $("#tab_container").find('li.active').text();
	console.log(data.length);
	if(bool == true) {
		for(var i = 0; i < data.length; i++) {
			console.log(data[i].wod_id + " " + data[i].score);
			$("#"+data[i].wod_id+"input").val(data[i].score);
		}
		if(data.length < 5) {
			//send to prompt to search cf_open_YEAR_leaderboard
			if(data.length == 0) {
				promptToSearchLeaderboard("No data found for the year: ",year);
			} else {
				promptToSearchLeaderboard("Some data found for the year: ",year);
			}
		}
	} else {
			promptToSearchLeaderboard("No data found", $("#tab_container").find('li.active').text());
	}
	
}

function promptToSearchLeaderboard(stringVal,year) {
	console.log(year);
	
	var html = stringVal+ " " + year+"! Would you like us to search for your open scores in our database?<p></p>";
	html += '<input type="button" onclick="searchForOpenScores('+year+')" value="Yes"/>';
	html += '<input type="button" onclick="closeDialog()" value="No"/><p></p>';
	html += '<p id="side_note"></p>***Please note that there may exist several users with the same name! If this is the case, select from the list presented which is you***';
	
	$( "#dialog-modal" ).dialog({
	  height: 400,
	  width: 600,
	  modal: true
	});
	
	$( "#dialog-modal" ).dialog();
	$("#modal_content").html(html);
}

function searchForOpenScores(year) {

	console.log(year);
	
	$.ajax({
		type: "POST",
		url: "searchForUserOpenWODScores.php",
		data: {yr:year},
		dataType: "text",
		success: function(response) {
			if(response.substring(0,1) == "1" ||
				response.substring(0,1) == "2" ||
				response.substring(0,1) == "3" ||
				response.substring(0,1) == "4") {
				console.log(response);
				$("#new_open_data").dialog("close");
				$("#dialog-modal").dialog("close");
				
				alert("Search for open scores Error! " + response);
			} else {
				var jsonObj = jQuery.parseJSON(response);
				console.log(jsonObj);
				//load into table and display
				loadOpenAthleteSearchResults(jsonObj);
			}
		}
	});
}

function searchForOpenWODPlace() { 

	var data = new Array();
	var year = $("#tab_container").find('li.active').text();
	year = year.substring(2, year.length);
	var division = $( "#division_selector option:selected" ).text();
	var region = $( "#region_selector option:selected" ).text();
	console.log("Year: " + year.substring(2, year.length) + ", " + division + ", " + region);
	for(var i = 1; i < 6; i++) {
		data.push({name:i, value:$("#"+year+""+i+"input").val()});
	}
	data.push({name:"year", value:year});
	data.push({name:"division", value:division});
	data.push({name:"region", value:region});
	
	$.each(data, function(i, field) {
		console.log("DATA: " + field.name + " : " + field.value);
	});
	
	$.ajax({
		type: "POST",
		url: "getOpenOriginalPlaceBasedOnScore.php",
		data: data,
		dataType: "text",
		success: function(response) {
			if(response.substring(0,1) == "1" ||
				response.substring(0,1) == "2" ||
				response.substring(0,1) == "3" ||
				response.substring(0,1) == "4") {
				console.log(response);
				//$("#new_open_data").dialog("close");
				alert("Search for open WOD place Error! " + response);
			} else {
				var jsObj = jQuery.parseJSON(response);
				console.log(jsObj);
				//load into the appropriate inputs and display
				loadOpenAthletePlaceResults(jsObj);
			}
		}
	});
}

function loadOpenAthletePlaceResults(data) { 
	console.log("Load open place results: "+data.length);
	for(var i = 0; i < data.length; i++) {
		$("#"+data[i].wodNum+"place").val(data[i].place);
	}
}

function loadOpenAthleteSearchResults(data) {
	console.log("Load open search results: "+data.length);
	var html = "<br/><br/><table class=\"athleteOpenSearchTable\" ><tr>";
	html += "<th class=\"athleteOpenSearchHeaders\">Name</th><th class=\"athleteOpenSearchHeaders\">Place</th>";
	html += "<th class=\"athleteOpenSearchHeaders\">Division</th><th class=\"athleteOpenSearchHeaders\">Region</th>";
	html += "<th class=\"athleteOpenSearchHeaders\">WOD #1</th><th class=\"athleteOpenSearchHeaders\">WOD #2</th>";
	html += "<th class=\"athleteOpenSearchHeaders\">WOD #3</th><th class=\"athleteOpenSearchHeaders\">WOD #4</th>";
	html += "<th class=\"athleteOpenSearchHeaders\">WOD #5</th></tr>";
	var ath_id = "ath_link_";
	var athlete_string = "";
	for(var i = 0; i < data.length; i++) {
		athlete_string = data[i].athlete;
		athlete_string += "^"+data[i].place;
		athlete_string += "^"+data[i].division;
		athlete_string += "^"+data[i].region;
		athlete_string += "^"+data[i].wodOne;
		athlete_string += "^"+data[i].wodTwo;
		athlete_string += "^"+data[i].wodThree;
		athlete_string += "^"+data[i].wodFour;
		athlete_string += "^"+data[i].wodFive;
		html += "<tr class=\"athleteOpenSearchRow\" href=\""+athlete_string+"\">";
		html += "<td>"+data[i].athlete+"</td>";
		html += "<td>"+data[i].place+"</td>";
		html += "<td>"+data[i].division+"</td>";
		html += "<td>"+data[i].region+"</td>";
		html += "<td>"+data[i].wodOne+"</td>";
		html += "<td>"+data[i].wodTwo+"</td>";
		html += "<td>"+data[i].wodThree+"</td>";
		html += "<td>"+data[i].wodFour+"</td>";
		html += "<td>"+data[i].wodFive+"</td>";
		html += "</tr>";
	}
	html += "</table>";
	$("#modal_content").append(html);
}

function submitWhatIfData(year) {
	var division = $( "#division_selector option:selected" ).text();
	var region = $( "#region_selector option:selected" ).text();
	var data = $("#"+year+"_form").serializeArray();
	year = year.substring(2, year.length);
	var dataToSend = new Array();
	
	$.each(data, function(i, field) {
		if(field.name.indexOf("whatifinput") > -1) {
			if(field.value.length > 0) {
				console.log("TO SEND: " + field.name + " : " + field.value + " : at index: " + i);
				dataToSend.push({name:field.name, value:field.value});
			}
		} 
	});
	
	dataToSend.push({name:"div", value:division});
	dataToSend.push({name:"reg", value:region});
	dataToSend.push({name:"yr", value:year});

	$.ajax({
		type: "POST",
		url: "calculateWhatIfScores.php",
		data: dataToSend,
		dataType: "text",
		success: function(response) {
			if(response.substring(0,1) == "1" ||
				response.substring(0,1) == "2" ||
				response.substring(0,1) == "3" ||
				response.substring(0,1) == "4") {
				console.log(response);
				alert("Error! " + response);
			} else {
				var jsObj = jQuery.parseJSON(response);
				console.log(jsObj);
				loadWhatIfScores(jsObj);
			}
		}
	});	
}

function loadWhatIfScores(data) {
	for(var i = 0; i < data.length; i++) {
		$("#"+data[i].wod_id+"whatifplace").val(data[i].place.trim());
		console.log(parseInt(data[i].place.trim()) +" <>= " + parseInt($("#"+data[i].wod_id+"place").val() + "?"));
		if(parseInt(data[i].place.trim()) < parseInt($("#"+data[i].wod_id+"place").val() + "?")) {
			$("#"+data[i].wod_id+"whatifplace").addClass('whatIfBetter');
		} else {
			$("#"+data[i].wod_id+"whatifplace").addClass('whatIfWorse');
		}
	}
}

/**************** UTILITY METHODS ***********************/
function clearAll() {
	$('#avg_score').empty();
	$('#wod_actual_description').empty();
	$('#tbl_body_leaderboard').empty();
	$('#wod_list').empty();
	$("#wod_desc_hdr").empty();
	$("#ldrbrd_hdr").empty();
	$("#leaderboard_headers").empty();
	$("#leaderboard").empty();
	$("#chart_div").empty();
}

function closeDialog() {
	$("#dialog-modal").dialog("close");
}

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