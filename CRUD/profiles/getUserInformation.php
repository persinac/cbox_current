<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$user_id = $_SESSION['MM_UserID'];

$content = "";
if($stmt = $mysqli->prepare("SELECT first_name, picture_url, region, 
		f_lift, f_girl, f_hero, f_movement, cf_exp, member_since 
		FROM athletes where user_id = $user_id")) 
{
	$stmt->execute();
	$stmt->store_result();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($fn, $purl, $reg, $fl, $fg, $fh, $fm, $cfe, $ms);
		$content = "";
		while ($stmt->fetch()) {
			$content .= "<div>";
			$content .= "<h3>Welcome, ".$fn."!</h3>";
			
			$content .= "<img src=\"".$purl."\" class=\"user_picture\"/></div><div>";
			
			//$content .= '<form action="/CRUD/profiles/submitUserPicture.php" method="POST" enctype="multipart/form-data">';
			$content .= '<input type="file" name="picture" id="picture"><br>';
			$content .= '<input type="button" value="upload" onclick="submitNewPicture()" /></div>';
			
			$content .= "<div id=\"basic_user_info\" class=\"user_update\">";
			$content .= "<p><span class=\"user_info_header\">Region:</span>";
			$content .= "<input name=\"user_reg\" id=\"user_reg\" type=\"text\" value=\"".$reg."\"/><input type=\"submit\" value=\"Change Region\" onclick=\"submitUserEdit(1);\"/></p>";
			$content .= "<p><span class=\"user_info_header\">Crossfit Experience (years):</span>";
			$content .= "<input name=\"user_cf_exp\" id=\"user_cf_exp\" type=\"text\" value=\"".$cfe."\"/><input type=\"submit\" value=\"Update Experience\" onclick=\"submitUserEdit(2);\"/></p>";
			$content .= "</div>";
			
			$content .= "<div id=\"user_fav\" class=\"user_update\">";
			$content .= "<p><span class=\"user_info_header\">Favorite Lift:</span>";
			$content .= "<input name=\"user_fav_lift\" id=\"user_fav_lift\" type=\"text\" value=\"".$fl."\"/><input type=\"submit\" value=\"Update Favorite Lift\" onclick=\"submitUserEdit(3);\"/></p>";
			$content .= "<p><span class=\"user_info_header\">Favorite Girl:</span>";
			$content .= "<input name=\"user_fav_girl\" id=\"user_fav_girl\" type=\"text\" value=\"".$fg."\"/><input type=\"submit\" value=\"Update Favorite Girl\" onclick=\"submitUserEdit(4);\"/></p>";
			$content .= "<p><span class=\"user_info_header\">Favorite Hero:</span>";
			$content .= "<input name=\"user_fav_hero\" id=\"user_fav_hero\" type=\"text\" value=\"".$fh."\"/><input type=\"submit\" value=\"Update Favorite Hero\" onclick=\"submitUserEdit(5);\"/></p>";
			$content .= "<p><span class=\"user_info_header\">Favorite Movement:</span>";
			$content .= "<input name=\"user_fav_mvmt\" id=\"user_fav_mvmt\" type=\"text\" value=\"".$fm."\"/><input type=\"submit\" value=\"Update Favorite Movement\" onclick=\"submitUserEdit(6);\"/></p>";
			$content .= "</div>";
		}
		/* free results */
		$stmt->free_result();
		echo $content;
	}
	else {
		echo "1: No data";
	}
} else {
		echo "2: " . $mysqli->error;
}

$mysqli->close();
?>