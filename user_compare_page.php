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
else if ($_SESSION['MM_Admin'] == "1") {$link = "Admin_home_page.php";} // COMMENT 
	
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
	<li id="home" >
	<?php echo "<a href='$link' >"; ?>HOME</a></li> 
	<li id="compare" class="active"><a href="user_compare_page.php" >COMPARE</a></li> 
	<li id="wod"><a href="user_wod_page.php" >WOD</a></li> 
	<li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li> 
	<li id="account" ><a href="#" >ACCOUNT</a></li> 
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
			<h2 style="color: #FFF">WOD Description</h2>
			<div id="wod_actual_description">Description of wod goes here. It should wrap around and not extend the page.
			</div>
		</div>
        <div id="leaderboard">
			<h2 style="color: #FFF">Leaderboard</h2>
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
		</div>
		
		<div id="average_user_score">
			<h2 id="avg_score_header">Average Score</h2>
			<p></p>
			<div id="avg_score">
			</div>
		</div>
    </div> <!-- END DATA_CONTAINER -->
	<div id="chart_div"></div>
</div> <!-- END CONTAINER -->

<!-- JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="dist/js/jquery.plugin.min.js"></script>
<script src="dist/js/jquery.datepick.min.js"></script>

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
  });
  
  $( "#initial_selector" ).change(function() {
    var str = "";
	var second_str = "";
	//console.log("INITIAL CHANGED");
    $( "#initial_selector option:selected" ).each(function() 
	{
		$('#div_compare_by').empty();
		if($(this).text() == "Today's Results") {
			console.log("TODAY");
            str += 'Male <input type="radio" name="gender_to_compare" class="radio_butts" value="M">';
            str += 'Female <input type="radio" name="gender_to_compare" class="radio_butts" value="F">';
            str += 'All <input type="radio" name="gender_to_compare" class="radio_butts" value="A"> <br><br>';
            str += '<select id="today_compare_selector" name="compare_selector" class="selector">';
			str += "<option value=\"ALL\"> - </option>";
			str += '<option value="RX">RX</option>';
			str += '<option value="INTER">Intermediate</option>';
			str += '<option value="NOV">Novice</option>';
			str += '</select><br>';
			str += '<input onclick="today_compare(this.form);" type="button" id="compare_but" value="today" />';
			//$('#div_compare_by').append(str);
		} else if($(this).text() == "Search") {
			console.log("Search");
                /*<!--Box <input type="radio" name="area_to_compare" class="radio_butts" value="box">
                Region <input type="radio" name="area_to_compare" class="radio_butts" value="reg">
                Country <input type="radio" name="area_to_compare" class="radio_butts" value="cou"> <br><br>-->*/
            str += '<select id="compare_selector" name="compare_selector" class="selector">';
			str += "<option value=\"ALL\"> - </option>";
			str += '<option value="WOD">WODS</option>';
			str += '<option value="AMRAP">Core Lifts</option>';
			str += '<option value="TABATA">Olympic</option>';
			str += '<option value="GIRLS">Powerlifting</option>';
			str += '<option value="HERO">Girls</option>';
			str += '<option value="HERO">Heroes</option>';
			str += '<option value="HERO">Open</option>';
			str += '<option value="HERO">Regionals</option>';
			str += '<option value="HERO">Games</option>';
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
			str += '<input onclick="search(this.form);" type="button" id="search_but" value="search" />';
			
		}
		$('#div_compare_by').append(str);
    });
  }).trigger( "change" );
  
$( "#div_compare_by" ).on("change", "#compare_selector", function() {
    var str = "";
	var second_str = "";
	console.log("CHANGED");
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
			str +="<option value=\"ALL\">ALL</option>";
			str +="<option value=\"RFT\">RFT</option>";
			str += "<option value=\"AMRAP\">AMRAP</option>";
			str +="<option value=\"TABATA\">TABATA</option>";
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
		if($(this).text() == 'ALL') {
			console.log("ALL");
			
			second_str+="<h4><p id=\"display_workout\"></p></h4>";
        	second_str+="<table width=\"530\" rules=\"cols\" id=\"tbl_wod_list\">";
            second_str+="<tr id=\"wod_list_headers\">";  	
            second_str+="</tr>";
            second_str+="<tbody class=\"tbl_body_wod_list\" id=\"tbl_body_wod_list\">";
            second_str+="</tbody></table>";
			$('#wod_list').html(second_str);
			second_str = "<th width=\"80\" height=\"25\">Date>/th>";
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
		}
    });
  }).trigger( "change" );
  
  $( "#wod_list" ).on("click", ".date_link", function() {
		var date = document.getElementById($(this).attr("id")).text;
		console.log($(this).attr("id") + ", VALUE: " + document.getElementById($(this).attr("id")).text);
		//parse the date - the value - and put the box_id in front of it
		//then grab all the athletes that completed that wod
		//and put the data into a table in the right side
		var result = date.replace(/-/g, "");
		//console.log("Result: "+result);
		getLeaderBoardData(result);
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

function getLeaderBoardData(data) {
	console.log("get leader board data: " + data);
	var comma_index = data.indexOf(",");
	var temp_str = "";
	var date = "";
	var gender = "";
	var level = "";
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
		console.log("get leaderboard content: " + response);
		loadLeaderBoardData(response);
		console.log(type_of_wod_main);
		if(type_of_wod_main == "AMRAP") {
			calculateRating(response, type_of_wod_main);
		}
	  },
  	  error: function(error){
    		console.log('error receiving leaderboard content!' + error);
  		}
	});
	
}
/******************************************************/
function search() {
	console.log("SEARCHING");
	var compare_data = $("#what_to_compare_form").serializeArray();
	$('#display_workout').empty();
	$.each(compare_data, function(i, field){
    	console.log("FORM DATA: " +field.name + ":" + field.value + " ");
  	});
	
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"compare_query.php",         
	  data: compare_data, //insert argumnets here to pass to getAdminWODs
	  dataType: "json",                //data format      
	  success: function(response) //on recieve of reply
	  {
		  console.log("response_wods: " + response);
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
		//main_description = descrip;
		
		time =  data_wods[i].time;
		rounds = data_wods[i].rounds;
		date_link_id = "date_link_"+i;
		console.log("date link id: " + date_link_id);
		header_wod = "Past WODs";
		var temp_descrip = "";
		if(descrip.length > 42) {
			temp_descrip = descrip.substring(0, 39);
			console.log(temp_descrip);
			descrip = temp_descrip + "...";
		}
		
		html_sec1 += "<tr class="+sec1_classID+">";
		//html_sec1 += "<td>"+dow+"</td>";
		html_sec1 += "<td><div class=\"tdDivBox\" id=\"tdDivBox\"><a class=\"date_link\" id=\""+date_link_id+"\" href=\"#\">"+dow+"</a></div></td>";
		html_sec1 +="<td class=\"pastwod_descrip\">"+tow+"</td>";
		html_sec1 += "<td>EMPTY</td>";
		html_sec1 += "</tr>";
		console.log("pre_undefined");
		/*if(typeof (data_wods[i+1].type_of_wod) == 'undefined') {
			i++;
			console.log("undefined");
		}*/
		console.log("post_undefined");
	}
	//Update html content
	//alert("HTML: " + html);
	$('#display_workout').empty();
	$('#display_workout').append(header_wod);
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
	console.log("loadLeaderboardData PRE-FOR LOOP");
	
	for(var i = 0; i < data_leaders.length; i++) {
		
		if(typeof data_leaders[i].descrip != undefined) {
			//console.log("data[i].descrip: " +data_leaders[i].descrip);
			//console.log("userid data[i]: " + data_leaders[i].user_id);
			//console.log("user id php: " + <?php echo $_SESSION['MM_UserID']; ?>);
			
			main_description = data_leaders[i].descrip;
			name = data_leaders[i].name;
			score = data_leaders[i].score;
			
			if(score.substring(0, 3) == "00:") {
				console.log(score.substring(3));
				score = score.substring(3);
			}
				
			html_sec1 += "<tr class="+sec1_classID+" id=\"leader_"+i+"\">";
			
			if(data_leaders[i].user_id == "<?php echo $_SESSION['MM_UserID']; ?>") {
				console.log("user id = " + data_leaders[i].user_id);
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

/***************************** Generate ratings ****************************************/
var arrayToSend = new Array();
function calculateRating(data_for_array, wod_type) {
	var avg = 0;
	var sum = 0;
	var t_count = 0;
	var tempRate = 0;
	var name = "";
	var score = 0;
	var rating = 0;
	
	
	
	console.log("Scores: ");
	for(var i = 0; i < data_for_array.length; i++) {
		console.log(data_for_array[i].score + " "+ data_for_array[i].name);
		sum = sum + parseInt(data_for_array[i].score);
		t_count = i+1;
	}
	console.log("sum: "+sum);
	avg = (sum/t_count);
	console.log(avg);
	for(var i = 0; i < data_for_array.length; i++) {
		var tempArray = new Array();
		name = data_for_array[i].name;
		score = data_for_array[i].score;
		
		tempRate = ((score/avg)*100)-100;
		console.log("temprate: "+tempRate)
		
		if(tempRate < 0) {
			tempRate = (tempRate*(-1));
		}
		if(score < avg) {
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
		console.log( name + ", "+ score+", "+rating);
		tempArray.push(name);
		tempArray.push(score);
		tempArray.push(parseFloat(rating).toFixed(2));
		for(var t= 0; t< tempArray.length; t++) {
			console.log("tempArray values:" + tempArray[t]);
		}
		arrayToSend[i] = tempArray;
	}

	arrayCurrent(arrayToSend);
	loadGraphs(wod_type, arrayToSend);
	$('#avg_score').empty();
	$('#avg_score').html(avg);
}

function arrayCurrent(arrayToSee) {
	for(var i = 0; i < arrayToSee.length; i++) {
		console.log("Current Data: " + arrayToSee[i][0]+ ", " +arrayToSee[i][1]  + ", " +arrayToSee[i][2]);
	}
}

/********************************* Load and draw graph functions***********************************************/

function loadGraphs(wod_type, data_array)
{
	console.log("load graphs: " + wod_type);
	drawComparisonAMRAPChart(data_array);
}
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawComparisonAMRAPChart(scores) {
	// Create the data table.
	/********************** Old Stuff ******************
	var data = new google.visualization.DataTable();
	console.log("SCORES: " );
	data.addColumn('number', 'Athletes');
	data.addColumn('number', 'Score');
	var min = 9999;
	var max = 0;
	var range = 0;
	var incrementThreshold = 0;
	var incrementBy = 0;
	var incrementArray = new Array();
	for(var i = 0; i < scores.length; i++) {
		var parsedScore = parseInt(scores[i]);
		console.log(" " +parsedScore);
		data.addRows([
			[i, parsedScore],
		]);
		console.log("Scores[i]: " + parsedScore + " current min: " + min + " current max: " + max);
		if(parsedScore < min) {
			min = parsedScore;
		}
		if(parsedScore > max) {
			max = parsedScore;
		}
		incrementThreshold = i;
	}
	
	range = max - min;
	incrementBy = (range*1.10) / incrementThreshold;
	console.log("Min: " + min + ", Max: " + max + ", Range: "+range + ", increment thresh:"
				+incrementThreshold+", incrementby: "+ parseInt(incrementBy));
	
	var tempFrequency = 0;
	var tempMinIncrement = min;
	var tempMaxIncrement = min + parseInt(incrementBy);
	console.log("Variables before for loop: " +
					" tempMinIncrement: " + tempMinIncrement +
					" tempMaxIncrement: " + tempMaxIncrement +
					" tempFrequency: " + tempFrequency);
	for(var i = 0; i < incrementThreshold; i++) {
	
		for(var h = 0; h < scores.length; h++) {
			if(scores[h] >= tempMinIncrement && scores[h] < tempMaxIncrement) {
				console.log("Score["+h+"]: " + scores[h] +" tempMinIncrement: " + tempMinIncrement +
					" tempMaxIncrement: " + tempMaxIncrement);
				tempFrequency += 1;
			}
		}
		
		incrementArray[i] = tempFrequency;
		console.log("Variables in for loop: " +
					" incrementArray["+i+"]: " + incrementArray[i] +
					" tempMinIncrement: " + tempMinIncrement +
					" tempMaxIncrement: " + tempMaxIncrement +
					" tempFrequency: " + tempFrequency);
		tempMinIncrement = tempMaxIncrement;
		tempMaxIncrement += parseInt(incrementBy);
		
		tempFrequency = 0;
	}

	for(var k = 0; k < incrementArray.length; k++)
	{
		/*data.addRows([
			[String(i), incrementArray[k]],
		]);
		*****************************************************/
		
		// Create the data table.
	var data = new google.visualization.DataTable();
	var min = 9999;
	var max = 0;
	console.log("SCORES: " );
	data.addColumn('number', 'Rating');
	data.addColumn('number', 'Score');
	data.addColumn({type:'string',role:'tooltip'});
	for(var i = 0; i < scores.length; i++) {
		var name = String(scores[i][0]);
		var parsedScore = parseInt(scores[i][1]);
		var parsedRating = parseFloat(scores[i][2]);
		console.log(" " +parsedScore + " "+ parsedRating);
		data.addRows([
			[parsedRating, parsedScore, name+ " " +parsedRating ],
		]);
		if(parsedScore < min) {
			min = parsedScore;
		}
		if(parsedScore > max) {
			max = parsedScore;
		}
	}

	var options = {
		title: 'Athlete Rating',
		vAxis: {title:'Score', minValue: (min*.8), maxValue: (max*1.2)},
		hAxis: {title:'Rating',minValue: 0, maxValue: 100},
		'width':500,
		'height':400, 
		'chartArea': {'width': '80%', 'height': '80%'},
		'legend': {'position': 'bottom'}
	};

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
	chart.draw(data, options);
	
	//clear data from the main array
	while(arrayToSend.length > 0){
		arrayToSend.pop();
	}
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