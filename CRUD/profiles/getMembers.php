<?php require_once('../../Connections/cboxConn.php'); ?>
<?php
session_start();

$mysqli = new mysqli($hostname_cboxConn, $username_cboxConn, $password_cboxConn, $database_cboxConn);
	/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$box_name = "";

$box_id = $_POST['box_id'];

$query_getBoxID = "select box_name
 from box
WHERE box_id = $box_id";

if ($result = $mysqli->query($query_getBoxID)) {
	$row = $result->fetch_assoc();
	$box_name = $row['box_name'];
}

$content = "";
if($stmt = $mysqli->prepare("SELECT first_name, last_name, user_id, region, picture_url,
		f_lift, f_girl, f_hero, f_movement, cf_exp, member_since 
		FROM athletes where box_id = $box_id")) 
{
	$stmt->execute();
	$stmt->store_result();
	/* Get the number of rows */
	$num_of_rows = $stmt->num_rows;
	if($num_of_rows > 0) {
		/* Bind the result to variables */
		$stmt->bind_result($fn, $ln, $aid, $reg, $purl, $fl, $fg, $fh, $fm, $cfe, $ms);
		$content = "</br><div>";
		$counter = 1;
		while ($stmt->fetch()) {
			$content .= "<div class=\"user_information\">";
			$content .= "<h3>".$fn." ".$ln."</h3>";
			$content .= "<img src=\"".$purl."\" class=\"user_picture\">";
			$content .= "<div id=\"basic_user_info\" class=\"user_description\">";
			$content .= "<p><span class=\"user_info_header\">Box Affiliation:</span>".$box_name."</p>";
			$content .= "<p><span class=\"user_info_header\">Region:</span>".$reg."</p>";
			$content .= "<p><span class=\"user_info_header\">Crossfit Experience:</span>$cfe</p>";
			$content .= "<p><span class=\"user_info_header\">Member Since:</span>$ms</p>";
			$content .= "</div>";
			$content .= "<div id=\"basic_user_bench\" class=\"user_description\">";
			$content .= "<p><span class=\"user_info_header\">Favorite Lift:</span>$fl</p>";
			$content .= "<p><span class=\"user_info_header\">Favorite Girl:</span>$fg</p>";
			$content .= "<p><span class=\"user_info_header\">Favorite Hero:</span>$fh</p>";
			$content .= "<p><span class=\"user_info_header\">Favorite Movement:</span>$fm</p>";
			$content .= "</div>";
			$content .= "</div>";
			$content .= "<p style=\"clear: both;\">";
		}
		$content .= "</div>";
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