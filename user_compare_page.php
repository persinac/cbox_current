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
	//console.log("READY!!!");
});

var header_wod = "";


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
  
  $( "#initial_selector" ).change(function() {
    var str = "";
	var second_str = "";
	//console.log("INITIAL CHANGED");
    $( "#initial_selector option:selected" ).each(function() 
	{
		$('#div_compare_by').empty();
		if($(this).text() == "Today's Results") {
			console.log("TODAY");
            str += 'Male <input type="radio" name="gender_to_compare" class="radio_butts" value="box">';
            str += 'Female <input type="radio" name="gender_to_compare" class="radio_butts" value="reg">';
            str += 'All <input type="radio" name="gender_to_compare" class="radio_butts" value="cou"> <br><br>';
            str += '<select id="today_compare_selector" name="compare_selector" class="selector">';
			str += "<option value=\"ALL\"> - </option>";
			str += '<option value="RX">RX</option>';
			str += '<option value="INTER">Intermediate</option>';
			str += '<option value="NOV">Novice</option>';
			str += '</select><br>';
			str += '<input onclick="today_compare(this.form);" type="button" id="compare_but" value="Compare" />';
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
			str += '<input onclick="search(this.form);" type="button" id="search_but" value="Today" />';
			
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
	
	//pass this data to php file
	$.ajax(
	{ 
	  type:"POST",                                     
	  url:"getLeaderboardContent.php",         
	  data: {"date" : data}, //insert argumnets here to pass to getAdminWODs
	  dataType: "json",                //data format      
	  success: function(response) //on recieve of reply
	  {
		  console.log("response_wods: " + response);
		  loadLeaderBoardData(response);
	  },
  	  error: function(error){
    		console.log('error loading wods!' + error);
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
	/*console.log("loadPastWODS PRE-FOR LOOP");
	console.log("DATA: " + data_wods);
	console.log("t_DATA: " + t_data);*/
	for(var i = 0; i < data_leaders.length; i++) {
		console.log("data[i].dateofwod: " +data_leaders[i].rx_descrip);
		console.log("data[i].type_of_wod: " + data_leaders[i].name);
		console.log("data[i].rx_description: " + data_leaders[i].score);
		
		descrip = data_leaders[i].rx_descrip;
		name = data_leaders[i].name;
		score = data_leaders[i].score;
		
		html_sec1 += "<tr class="+sec1_classID+">";
		//html_sec1 += "<td>"+dow+"</td>";
		html_sec1 += "<td><div class=\"tdDivNameOfAthlete\" id=\"tdDivBox\">"+name+"</div></td>";
		html_sec1 +="<td class=\"tdDivScore\">"+score+"</td>";
		html_sec1 += "</tr>";
		console.log("pre_undefined");
		/*if(typeof (data_wods[i+1].type_of_wod) == 'undefined') {
			i++;
			console.log("undefined");
		}*/
		console.log("post_undefined");
	}
	//Update html content
	$('.tbl_body_leaderboard').empty();
	$('.tbl_body_leaderboard').html(html_sec1);
}
 
</script>

</body>
</html>