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
mysql_select_db($database_cboxConn, $cboxConn);

###
# Defualt view is Crossfit->Foundamental benchmarks
###
$movement_id = "cft";
$query_getUserCFBenchmarks = "select bs.mvmnt_id, bs.weight, bs.date_achieved from benchmarks bs join (select user_id, mvmnt_id, max(weight) weight from benchmarks 
	group by user_id, mvmnt_id) bb on bs.mvmnt_id = bb.mvmnt_id AND bs.weight = bb.weight
WHERE bs.user_id = $colname_getUserBenchmarks AND bs.mvmnt_id LIKE '{$movement_id}%' 
ORDER BY bs.mvmnt_id ";
$getUserCFBenchmarks = mysql_query($query_getUserCFBenchmarks, $cboxConn) or die(mysql_error());
#$rows = mysql_fetch_assoc($getUserBenchmarks);
$totalRows_getUserBenchmarks = mysql_num_rows($getUserCFBenchmarks);


	if(!(isset($_SESSION['MM_Username'])))
	{
		header("Location: Error401UnauthorizedAccess.php");
	}
	
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
    
	
	<link rel="stylesheet" type="text/css" href="dist/css/jquery.datepick.css">

</head>


<body>
<div id="container">
<div id="rect_one"></div>
<div id="rect_two"></div>
<div id="navbar_main">
  <ul id="navbar_main_ul"> 
	<li id="home" >
	<?php echo "<a href='$link' >"; ?>HOME</a></li> 
	<li id="compare"><a href="user_compare_page.php" >COMPARE</a></li> 
	<li id="wod"><a href="/user_wod_page.php" >WOD</a></li> 
	<li id="progress" class="active"><a href="#" >PROGRESS</a></li> 
	<li id="account" ><a href="#" >ACCOUNT</a></li> 
	<li id="logout" ><a href="#" >LOGOUT</a></li>
  </ul> 
</div>

<div id="navbar_sub"> 
  <ul id="navbar_sub_ul"> 
	<li id="cft_lnk" ><a href="#" >CROSSFIT</a></li> 
	<li id="oly_lnk"><a href="#" >OLYMPIC</a></li> 
	<li id="pwr_lnk"><a href="#" >POWERLIFTING</a></li> 
	<li id="mis_lnk" ><a href="#" >MISC</a></li> 
  </ul> 
</div>

<div id="sidebar">
	<ul id="sidebar_ul">

    </ul>
</div>

<div id="first_time">
	<button id="first_time_button">Add some PR's</button>
</div> 

<div id="pr_modal" class="modal" style="display:none; ">
        <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>New PR's</h3>
        </div>
        <div class="modal-body">
        	<!-- Grab number of rows and place that many into here -->  
            <form method="POST" id="pr_form" class="pr_">
                <div id="pr_row">
                <h4>Fundamentals</h4>
                    <h5>Backsquat:</h5> 
                    <p></p>
                    Weight: <input type="text" name="backsquat_weight" class="_weight" id="weight_0"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="bs_datepicker"/>
                    <p></p>
                    <p></p>
                    <h5>Frontsquat:</h5> 
                    <p></p>
                    Weight: <input type="text" name="frontsquat_weight" class="_weight" id="weight_1"/>
                    <p></p>
                    Date Achieved:  <input type="text" name="date" class="_datepicker" id="fs_datepicker"/> 
                    <p></p>
                    <h5>Overhead Squat:</h5> 
                    <p></p>
                    Weight: <input type="text" name="ohsquat_weight" class="_weight" id="weight_2"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="ohs_datepicker"/> 
                    <p></p>
                    <h5>Deadlift:</h5> 
                    <p></p>
                    Weight: <input type="text" name="deadlift_weight" class="_weight" id="weight_3"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="dl_datepicker"/> 
                    <p></p>
                    <h5>Sumo Deadlift Highpull:</h5> 
                    <p></p>
                    Weight: <input type="text" name="sdlhp_weight" class="_weight" id="weight_4"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="sdlhp_datepicker"/> 
                    <p></p>
                    <h5>Power Clean:</h5> 
                    <p></p>
                    Weight: <input type="text" name="pc_weight" class="_weight" id="weight_5"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="pc_datepicker"/> 
                    <p></p>
                    <h5>Overhead Press:</h5> 
                    <p></p>
                    Weight: <input type="text" name="ohp_weight" class="_weight" id="weight_6"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="ohp_datepicker"/> 
                    <p></p>
                    <h5>Push Press:</h5> 
                    <p></p>
                    Weight: <input type="text" name="pp_weight" class="_weight" id="weight_7"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="pp_datepicker"/> 
                    <p></p>
                    <h5>Push Jerk:</h5> 
                    <p></p>
                    Weight: <input type="text" name="pj_weight" class="_weight" id="weight_8"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="pj_datepicker"/> 
                    <p></p>
                    <h5>Clean and Jerk:</h5> 
                    <p></p>
                    Weight: <input type="text" name="cj_weight" class="_weight" id="weight_9"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="cj_datepicker"/> 
                    <p></p>
                    <h5>Snatch:</h5> 
                    <p></p>
                    Weight: <input type="text" name="snatch_weight" class="_weight" id="weight_10"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="snatch_datepicker"/> 
                    <p></p>
                    <h5>Bench:</h5> 
                    <p></p>
                    Weight: <input type="text" name="bench_weight" class="_weight" id="weight_11"/>
                    <p></p>
                    Date Achieved: <input type="text" name="date" class="_datepicker" id="bench_weight"/> 
                    <p></p>
                </div> <!-- END OF new_wod -->
             </form>         
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" id="submit">submit</button>
          <a href="#" class="btn" data-dismiss="modal">Close</a>
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
                    <!-- <td class="mvment">Back Squat</td>
                    <td class="weight">385</td>
                    <td class="mvment">Back Squat</td>
                    <td class="weight">385</td>
                    <td class="mvment">Overhead Squat</td>
                    <td class="weight">385</td>-->
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
   
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="dist/js/jquery.plugin.js"></script> 
    <script type="text/javascript" src="dist/js/jquery.datepick.js"></script>
	<script id="source" language="javascript" type="text/javascript">

$(document).ready(function() {
	addDivSeparators();
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

$(function() {
	$("#bs_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#fs_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#ohs_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#dl_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#sdlhp_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#pc_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#ohp_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#pp_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#pj_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#cj_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#snatch_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
	$("#bench_datepicker").datepick({dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true});
  });	
	
$(function(){
	$("button#first_time_button").click(function() {
		//alert("HELLO");
		var modalHeight = ($(this).height() / 2 ) + 'in !important';
		var modalWidth = ($(this).width() / 2 )  + 'in !important';
		//alert("HELLO 2 ");
		$('#pr_modal').css('margin-top', modalHeight);
		$('#pr_modal').css('margin-left', modalWidth);
		//alert("HELLO 3 ");
		$('#pr_modal').modal('show');
		//alert(wod_description + " " + wod_name + " "+wod_type+" "+level_perf+" " );
	});
});

$(function(){
	$("button#submit").click(function() {
		//alert("HELLO");
		submitPRs();
		$('#pr_modal').modal('hide');
	});
});
	
function submitPRs() {
	var datastring = $("#pr_form").serializeArray();
	var sendRequest = true;

	//alert("DATASTRING: " + datastring.toString());

	//WORKING
	$('._weight').each(function(i, item) {
        var weight =  $('#weight_'+i+'').val();
		//alert("i = "+i+", weight_"+i+" = "+$('#weight_'+i+'').val());
		var characterReg = /^[0-9]*$/;
		if(!characterReg.test(weight)) {
			sendRequest = false;
			alert("Invalid character at: Weight " + (i));
			$('#weight_'+i+'').addClass("big_input_wod_error");
		} //else if (movement.length == 0) {
			//$('#weight_'+i+'').addClass("big_input_wod_error");
			//sendRequest = false;
		//}
    });
	var x = new Array(12);
	var count = 0;
	$.each(datastring, function(i, field){
    	//alert("DATA: " +field.name + ":" + field.value + " ");
		//alert("i = "+i+", i mod 2 = "+i%2);
		
		if(i%2 == 0)
		{
			x[count] = new Array(3);
			x[count][0] = field.name;
			x[count][1] = field.value;
			//alert("DATA: " +field.name + ":" + field.value + ", count = " + count);
		} else if (i%2 == 1) {
			//alert("DATA: " +field.name + ":" + field.value + ", count = " + count);
			//add date to array, then update count
			x[count][2] = field.value;
			count++;
		}
  	});
	for (var i = 0; i < 13; i++) {
    	//alert("x["+i+"]["+0+"] = "+x[i][0] + "x["+i+"]["+1+"] = "+x[i][1]+ "x["+i+"]["+2+"] = "+x[i][2]);
		var movementName = x[i][0];
		var movementWeight = x[i][1];
		var date_achieved = x[i][2];
		
		if(movementWeight.length > 0 && date_achieved.length > 0) {
			var newName = "";
			//function to decode movementname into mvmnt_id
			newName = idOfMovement(movementName);
			//alert("ID: "+newName);
		
			if(sendRequest == true) {
				$.ajax({
					type: "POST",
					url: "addUserFoundPR.php",
					data: { "mvmnt_id" : newName,
					"name" : "",
						"weight": movementWeight, 
						"time" : "",
						"date" : date_achieved},
					success: function(data) {
						//_Ajax.pages[key] = data;
						 alert('Data send:' + data);
					}
				});
				if(newName == "cft_01") {
					$.ajax({
						type: "POST",
						url: "addUserFoundPR.php",
						data: { "mvmnt_id" : "pwr_01",
						"name" : "",
							"weight": movementWeight, 
							"time" : "",
							"date" : date_achieved},
						success: function(data) {
							//_Ajax.pages[key] = data;
							 alert('Data send:' + data);
							}
						});
					} else if(newName == "cft_04") {
						$.ajax({
							type: "POST",
							url: "addUserFoundPR.php",
							data: { "mvmnt_id" : "pwr_03",
							"name" : "",
								"weight": movementWeight, 
								"time" : "",
								"date" : date_achieved},
							success: function(data) {
							//_Ajax.pages[key] = data;
							 alert('Data send:' + data);
							}
						});
					}
			}
		}
  	}
	$('._weight').each(function(i, item) {
        $('#weight_'+i+'').text('');
		$('#weight_'+i+'').val('');
	});
	
}
		
$("#sidebar_ul").on("click", "li", function() {
	//event.preventDefault();
	var id = jQuery(this).attr("id");
	//alert("SideBar click, ID: " + id);
	if(id == "wod")
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
    	$(li).removeClass('active');
  	});
  setActive(anID);
  
}

function setActive(anID) {
	$('#'+anID).addClass('active');
	$('#'+anID+" a").addClass('a');
}

function changeTextColorOfTables() {
 $('tr:odd').addClass('alt');
}

  function getData(movement_id) 
  {
	  var html = "";
    //Send HTTP request and call file based on movement_id
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
		  url: "php_json_test.php", //the script to call to get data          
		  data: { dataString: movement_id }, //you can insert url argumnets here to pass to api.php
		  dataType: "json",                //data format      
		  success: function(response) //on recieve of reply
		  {
			  //alert(movement_id);
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
		html += "<table class=\"cft_wods\">";
		html += "<tbody class=\"wods_table_body\">";
		html += "</tbody>";
		html += "</table>";
		html += "</div>";
	
		//alert("HTML: " + html )
		$('#data_container').html(html);
		
		//now load data into table
		$.ajax({ 
			  type:"POST",                                     
			  url: "getUserWODs.php", //the script to call to get data          
			  //data: { dataString: movement_id }, //you can insert url argumnets here to pass to api.php
			  dataType: "json",                //data format      
			  success: function(response) //on recieve of reply
			  {
				  //alert(movement_id);
				loadCFTWODData(response);
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
		  url: "php_json_test.php", //the script to call to get data          
		  data: { dataString: "oly" }, //you can insert url argumnets here to pass to api.php
		  dataType: "json",                //data format      
		  success: function(response) //on recieve of reply
		  {
			  //alert(movement_id);
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
		  url: "php_json_test.php", //the script to call to get data          
		  data: { dataString: "pwr" }, //you can insert url argumnets here to pass to api.php
		  dataType: "json",                //data format      
		  success: function(response) //on recieve of reply
		  {
			  //alert(movement_id);
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
		  url: "php_json_test.php", //the script to call to get data          
		  data: { dataString: "mis" }, //you can insert url argumnets here to pass to api.php
		  dataType: "json",                //data format      
		  success: function(response) //on recieve of reply
		  {
			  //alert(movement_id);
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
		  url: "php_json_test.php", //the script to call to get data          
		  data: { dataString: movement_id }, //you can insert url argumnets here to pass to api.php
		  dataType: "json",                //data format      
		  success: function(response) //on recieve of reply
		  {
				console.log(response);
				loadGRLData(response);
		  } 
		});
	}
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
			if(data[i].weight == 0) {
				data[i].weight = "--:--";
			}
			if(i <= 6) {
				if(data[i].wod_type == 'r') {
					html_sec1 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].time+"</td></tr>";
				}
				else {
					html_sec1 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].reps+"</td></tr>";
				}
			} else if (i > 6 && i <= 13) {
				if(data[i].wod_type == 'r') {
					html_sec2 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].time+"</td></tr>";
				}
				else {
					html_sec2 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].reps+"</td></tr>";
				}
			}
			else {
				if(data[i].wod_type == 'r') {
					html_sec3 += "<tr class="+sec1_classID+"><td>"+
						vname+"</td><td>"+data[i].time+"</td></tr>";
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
  
  
  function setSideBar(id)
  {
	  var html = "";
	  //alert("setSideBar() - Line 1; ID: " + id);
	  $('#sidebar_ul').empty();
	   //alert("setSideBar() - Line 3");
	  if(id == "cft_lnk")
	  {	
	  	 //alert("setSideBar() - in IF");
	  	html += "<li id=\"wod\" ><a href=\"#\">WODS</a></li>";
		html += "<li id=\"cft\" ><a href=\"#\">FUNDAMENTALS</a></li>";
		html += "<li id=\"grl\" ><a href=\"#\">THE GIRLS</a></li>";
		html += "<li id=\"hro\" ><a href=\"#\">HEROES</a></li>";
		//alert("HTML: " + html);
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
	//alert("movement_name = " + movement_name);
 	var value = "Unknown Movement"; 
	if (movement_name == "backsquat_weight") { 
	  value = "cft_01"; 
	} 
	else if (movement_name == "frontsquat_weight") { 
	  value = "cft_02"; 
	} 
	else if (movement_name == "ohsquat_weight") { 
	  value = "cft_03"; 
	}
	else if (movement_name == "deadlift_weight") { 
	  value = "cft_04"; 
	}
	else if (movement_name == "sdlhp_weight") { 
	  value = "cft_05"; 
	}
	else if (movement_name == "pc_weight") { 
	  value = "cft_06"; 
	}
	else if (movement_name == "ohp_weight") { 
	  value = "cft_07"; 
	}
	else if (movement_name == "pp_weight") { 
	  value = "cft_08"; 
	}
	else if (movement_name == "pj_weight") { 
	  value = "cft_09"; 
	} 
	else if (movement_name == "cj_weight") { 
	  value = "oly_01"; 
	}
	else if (movement_name == "snatch_weight") { 
	  value = "oly_02"; 
	}
	else if (movement_name == "bench_weight") { 
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
<?php
mysql_free_result($getUserCFBenchmarks);
?>
