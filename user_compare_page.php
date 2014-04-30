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
    <li id="home" ><?php echo "<a href='$link' >"; ?>HOME</a></li> 
	<li id="compare" class="active"><a href="#" >COMPARE</a></li> 
	<li id="wod" ><a href="user_wod_page.php" >WOD</a></li> 
	<li id="progress" ><a href="User_progress_page.php" >PROGRESS</a></li> 
	<li id="account" ><a href="#" >ACCOUNT</a></li> 
  </ul> 
</div>


    <div id="image_div">
        <img src="images/compare_page/COMPARE_PAGE_CFAPP2.jpg" width="1224" height="792" alt=""/>
    </div>
    
    <div id="my_data_container">
        <div id="what_to_compare">
            <h4>Compare by: </h4>
            <form id="what_to_compare_form">
                Box <input type="radio" name="area_to_compare" class="radio_butts" value="box">
                Region <input type="radio" name="area_to_compare" class="radio_butts" value="reg">
                Country <input type="radio" name="area_to_compare" class="radio_butts" value="cou"> <br><br>
                <select id="compare_selector" name="compare_selector" class="selector">
				  <option value="WOD">WODS</option>
				  <option value="AMRAP">Core Lifts</option>
				  <option value="TABATA">Olympic</option>
				  <option value="GIRLS">Powerlifting</option>
				  <option value="HERO">Girls</option>
				  <option value="HERO">Heroes</option>
				  <option value="HERO">Open</option>
				  <option value="HERO">Regionals</option>
				  <option value="HERO">Games</option>
				</select><br>
                <!--Date: <input type="text" name="date" class="datepicker" id="datepicker"/> -->
				<div id="div_compare_by">
				</div>
				<p></p>
				<input onclick="compare(this.form);" type="button" id="compare_but" value="Compare" />
            </form>
            <h4>See today's results: </h4>
            <form id="todays_wod_form">
                Male <input type="radio" name="gender_to_compare" class="radio_butts" value="box">
                Female <input type="radio" name="gender_to_compare" class="radio_butts" value="reg">
                All <input type="radio" name="gender_to_compare" class="radio_butts" value="cou"> <br><br>
                <select id="today_compare_selector" name="compare_selector" class="selector">
				  <option value="RX">RX</option>
				  <option value="INTER">Intermediate</option>
				  <option value="NOV">Novice</option>
				</select><br>
				<input onclick="today_compare(this.form);" type="button" id="compare_but" value="Compare" />
            </form>
        </div> <!-- END WHAT_TO_COMPARE -->
        
        <div id="wod_list">
			<h4><p id="display_workout"></p></h4>
        	<table width="538" rules="cols" id="tbl_wod_list">
            	<tr id="wod_list_headers">
                	
                </tr>
                <tbody class="tbl_body_wod_list" id="tbl_body_wod_list">
                </tbody>
        	</table>
        </div> <!-- END WOD_LIST -->
        
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
	console.log("READY!!!");
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
  
$( "#compare_selector" ).change(function() {
    var str = "";
	var second_str = "";
	console.log("CHANGED");
    $( "#compare_selector option:selected" ).each(function() 
	{
		$('#div_compare_by').empty();
		if($(this).text() == 'WODS') {
			console.log("WODS");
			str += 'Date: <input type="text" name="date" class="datepicker" id="datepicker"/>';
			str +="<p></p>";
			str += "Level Performed: <select id=\"level_selector\" name=\"level_selector\">";
			str +="<option value=\"RX\">RX</option>";
			str += "<option value=\"INTER\">Intermediate</option>";
			str +="<option value=\"NOV\">Novice</option>";
			str +="<option value=\"CUS\">Custom</option>";
			str +="</select>";
			str +="<p></p>";
			str += "Type of WOD: <select id=\"wod_type_selector\" name=\"wod_type_selector\">";
			str +="<option value=\"RFT\">RFT</option>";
			str += "<option value=\"AMRAP\">AMRAP</option>";
			str +="<option value=\"TABATA\">TABATA</option>";
			str += "<option value=\"GIRLS\">GIRLS</option>"
			str +="<option value=\"HERO\">HEROES</option>";
			str +="</select>";
			str +="<p></p>";
		}
		$('#div_compare_by').append(str);
    });
  }).trigger( "change" );

  $( "#wod_type_selector" ).change(function() {
	var second_str = "";
	console.log("WTS CHANGED");
    $( "#wod_type_selector option:selected" ).each(function() 
	{
		$('#wod_list').empty();
		if($(this).text() == 'RFT') {
			console.log("RFT");
			
			second_str+="<h4><p id=\"display_workout\"></p></h4>";
        	second_str+="<table width=\"538\" rules=\"cols\" id=\"tbl_wod_list\">";
            second_str+="<tr id=\"wod_list_headers\">";  	
            second_str+="</tr>";
            second_str+="<tbody class=\"tbl_body_wod_list\" id=\"tbl_body_wod_list\">";
            second_str+="</tbody></table>";
			$('#wod_list').html(second_str);
			second_str = "<th width=\"90\" height=\"25\">Name</th>";
            second_str +="<th width=\"220\">Time</th>";
            second_str +="<th width=\"34\">Place</th>";
			$('#wod_list_headers').append(second_str);
		} else if($(this).text() == 'AMRAP') {
			console.log("AMRAP");
			
			second_str+="<h4><p id=\"display_workout\"> WORKOUT HERE </p></h4>";
        	second_str+="<table width=\"538\" rules=\"cols\" id=\"tbl_wod_list\">";
            second_str+="<tr id=\"wod_list_headers\">";  	
            second_str+="</tr>";
            second_str+="<tbody class=\"tbl_body_wod_list\" id=\"tbl_body_wod_list\">";
            second_str+="</tbody></table>";
			$('#wod_list').html(second_str);
			second_str = "<th width=\"90\" height=\"25\">Name</th>";
            second_str +="<th width=\"220\">Total Reps</th>";
            second_str +="<th width=\"34\">Place</th>";
			$('#wod_list_headers').append(second_str);
		}
    });
  }).trigger( "change" );
  
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

function compare() {
	console.log("COMPARE");
	var compare_data = $("#what_to_compare_form").serializeArray();
	$('#display_workout').empty();
	$.each(compare_data, function(i, field){
    	console.log("DATA: " +field.name + ":" + field.value + " ");
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
	var dow = "";
	var name;
	var level = "";
	var descrip = "";
	var time = "";
	/*console.log("loadPastWODS PRE-FOR LOOP");
	console.log("DATA: " + data_wods);
	console.log("t_DATA: " + t_data);*/
	for(var i = 0; i < data_wods.length; i++) {
		console.log("data[i].name: " +data_wods[i].name);
		console.log("data[i].date_of_wod: " + data_wods[i].date_of_wod);
		console.log("data[i].level_perf: " + data_wods[i].level_perf);
		name = data_wods[i].name;
		dow = data_wods[i].date_of_wod;
		level = data_wods[i].level_perf;
		if(data_wods[i].type_of_wod == "RFT"){
			 
			time = data_wods[i].time_comp;
			console.log("type of wod = RFT, time: " + time);
		} else {
			time = data_wods[i].rounds_compl;
			console.log("type of wod = AMRAP, time: " + time);
		}
		
		if(level == "RX" || level == "rx") { 
			descrip = data_wods[i].rx_descrip;
		} else if (level == "INTER" || level == "inter") {
			descrip = data_wods[i].inter_descrip;
		} else {
			descrip = data_wods[i].nov_descrip;
		}
		header_wod = descrip;
		var temp_descrip = "";
		if(descrip.length > 42) {
			temp_descrip = descrip.substring(0, 39);
			console.log(temp_descrip);
			descrip = temp_descrip + "...";
		}
		
		html_sec1 += "<tr class="+sec1_classID+">";
		//html_sec1 += "<td>"+dow+"</td>";
		html_sec1 += "<td>"+name+"</td>";
		html_sec1 +="<td class=\"pastwod_descrip\">"+time+"</td>";
		html_sec1 += "<td>"+(i+1)+"</td>";
		html_sec1 += "</tr>";
	}
	//Update html content
	//alert("HTML: " + html);
	$('#display_workout').empty();
	$('#display_workout').append(header_wod);
	$('.tbl_body_wod_list').empty();
	$('.tbl_body_wod_list').html(html_sec1);
	header_wod = "";
}
  
</script>

</body>
</html>