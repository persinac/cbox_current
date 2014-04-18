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
<?php
function nameOfMovement($movementID)
{ 
 	$value = "Unknown Movement"; 
	if ($movementID == "cft_01") { 
	  $value = "Back Squat"; 
	} 
	else if ($movementID == "cft_02") { 
	  $value = "Front Squat"; 
	} 
	else if ($movementID == "cft_03") { 
	  $value = "OverHead Squat"; 
	}
	else if ($movementID == "cft_04") { 
	  $value = "Deadlift"; 
	}
	else if ($movementID == "cft_05") { 
	  $value = "SDLHP"; 
	}
	else if ($movementID == "cft_06") { 
	  $value = "Power Clean"; 
	}
	else if ($movementID == "cft_07") { 
	  $value = "OverHead Press"; 
	}
	else if ($movementID == "cft_08") { 
	  $value = "Push Press"; 
	}
	else if ($movementID == "cft_09") { 
	  $value = "Push Jerk"; 
	}
  return $value; 
}
?>


<html>
<head>
<title>PROGRESS_PAGE_CFAPP_template</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- Custom styles for this template -->
    <link href="dist/css/user_progress_page_v2.css" rel="stylesheet">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- Save for Web Slices (PROGRESS_PAGE_CFAPP_template.psd) -->
<div class="div_main">
<table id="Table_01" width="1224" height="793" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="12">
			<img src="images/PROGRESS_PAGE_CFAPP_template_01.jpg" width="1224" height="89" alt=""></td>
	</tr>
	<tr>
		<td colspan="3" rowspan="2">
			<img src="images/PROGRESS_PAGE_CFAPP_template_02.jpg" width="505" height="122" alt=""></td>
		<td colspan="3">
			<img src="images/home_link.jpg" width="146" height="35" alt=""></td>
		<td>
			<img src="images/compare_link.jpg" width="140" height="35" alt=""></td>
		<td colspan="3">
			<img src="images/WOD_link.jpg" width="147" height="35" alt=""></td>
		<td>
			<img src="images/progress_link.jpg" width="141" height="35" alt=""></td>
		<td>
			<img src="images/settings_link.jpg" width="145" height="35" alt=""></td>
	</tr>
	<tr>
		<td colspan="9">
			<img src="images/PROGRESS_PAGE_CFAPP_template_08.jpg" width="719" height="87" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/sidebar_area.jpg" width="180" height="254" alt=""></td>
		<td colspan="3" rowspan="2">
			<img src="images/_sectionOne.jpg" width="333" height="254" alt=""></td>
		<td colspan="5">
			<img src="images/PROGRESS_PAGE_CFAPP_template_11.jpg" width="379" height="4" alt=""></td>
		<td colspan="3" rowspan="2">
			<img src="images/_sectionThree.jpg" width="332" height="254" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/PROGRESS_PAGE_CFAPP_template_13.jpg" width="21" height="252" alt=""></td>
		<td colspan="3">
			<img src="images/_sectionTwo.jpg" width="336" height="250" alt=""></td>
		<td rowspan="2">
			<img src="images/PROGRESS_PAGE_CFAPP_template_15.jpg" width="22" height="252" alt=""></td>
	</tr>
	<tr>
		<td colspan="4">
			<img src="images/PROGRESS_PAGE_CFAPP_template_16.jpg" width="513" height="2" alt=""></td>
		<td colspan="3">
			<img src="images/PROGRESS_PAGE_CFAPP_template_17.jpg" width="336" height="2" alt=""></td>
		<td colspan="3">
			<img src="images/PROGRESS_PAGE_CFAPP_template_18.jpg" width="332" height="2" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="images/PROGRESS_PAGE_CFAPP_template_19.jpg" width="264" height="325" alt=""></td>
		<td colspan="10">
			<img src="images/graph_area.jpg" width="960" height="325" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/spacer.gif" width="180" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="84" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="241" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="8" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="21" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="117" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="140" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="79" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="22" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="46" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="141" height="1" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="145" height="1" alt=""></td>
	</tr>
</table>

<table class="cft_foundamentals_sec1">
<?php
if($movement_id == "cft")
{
?>
	<table class="cft_foundamentals_sec1">
    <?php
	for ($j = 0 ; $j < 3; ++$j)
	{ 
		$rows = mysql_fetch_row($getUserCFBenchmarks); 
		?>
		<tr>
		<td><?php echo nameOfMovement($rows[0]); ?></td>
		<td><?php echo $rows[1]; ?></td>
		</tr>
        <?php
	}
	?>
	</table>
    <table class="cft_foundamentals_sec2">
    <?php
	for ($j = 0 ; $j < 3; ++$j)
	{ 
		$rows = mysql_fetch_row($getUserCFBenchmarks); 
		?>
		<tr>
		<td><?php echo nameOfMovement($rows[0]); ?></td>
		<td><?php echo $rows[1]; ?></td>
		</tr>
        <?php
	}
	?>
	</table>
    <table class="cft_foundamentals_sec3">
    <?php
	for ($j = 0 ; $j < 3; ++$j)
	{ 
		$rows = mysql_fetch_row($getUserCFBenchmarks); 
		?>
		<tr>
		<td><?php echo nameOfMovement($rows[0]); ?></td>
		<td><?php echo $rows[1]; ?></td>
		</tr>
        <?php
	}
	?>
	</table>
<?php
}
?>



</div>
</body>
</html>
<?php
mysql_free_result($getUserCFBenchmarks);
?>
