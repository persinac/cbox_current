<?php 
class ChallengeCalendarUtility {
	public $mys;
	var $chall_user_id = 0;
	
	function NewConnection($host, $user, $pass, $database) {
		$this->mys = mysqli_connect($host, $user, $pass, $database);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	}
	
	function CloseConnection() {
		try {
			mysqli_close($this->mys);
			return true;
		} catch (Exception $e) {
			printf("Close connection failed: %s\n", $mys->error);
		}
	}
	
	/*************** GETTERS AND SETTERS *****************/
	
	/*
	 * Sets chall_user_id
	 * params:
	 *	@u - user_id, passes in the current MAX chall_user_id, so I must add 1
	 */
	function SetChallengeUserID($u) {
		$this->chall_user_id = $u + 1;
	}
	
	function GetChallengeUserID() {
		return $this->chall_user_id;
	}
	
	function GetMaxChallengeUserID() {
		$max_id = -1;
		$query = "select max(user_id) AS user_id from user_info";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				$max_id = $row['user_id'];
			}
			$result->free();
		}
		return $max_id;
	}
	
	function InsertNewUser($cbox_id, $args) {
		//echo "\n".$cbox_id .', '.$args['un'].', '.$args['pw']."\n";
		$this->InsertIntoLogin($cbox_id, $args['un'], $args['pw']);
		$this->InsertIntoUserInfo(
			$args['first'],
			$args['last'],
			$args['email'],
			$args['gen'],
			$args['city'],
			$args['state']
		);
		$this->InsertIntoUserPubInfo($this->chall_user_id);
	}
	
	function InsertIntoLogin($cbox_id, $un, $pw) {
		//echo "\n$cbox_id, $un, $pw\n";
		$stmt = $this->mys->prepare("insert into login values (?, ?, ?, ?)");
		$stmt->bind_param( 'issi', $this->chall_user_id, $un, $pw, $cbox_id);
		if($result = $stmt->execute()) {
			$stmt->close();
		} else {
			echo "Error: Could not insert into Challenge Calendar Login";
		}
	}
	
	function InsertIntoUserInfo($fn, $ln, $em, $gen, $city, $state) {
		$stmt = $this->mys->prepare("insert into user_info values (?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param( 'issssss', $this->chall_user_id, $fn, $ln, $em, $gen, $city, $state);
		if($result = $stmt->execute()) {
			$stmt->close();
		} else {
			echo "Error: Could not insert into Challenge Calendar User Info";
		}
	}
	
	function InsertIntoUserPubInfo() {
		$t_val = '-';
		$stmt = $this->mys->prepare("insert into user_pub_info values (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param( 'isssss', $this->chall_user_id, $t_val, $t_val, $t_val, $t_val, $t_val, $t_val);
		if($result = $stmt->execute()) {
			$stmt->close();
		} else {
			echo "Error: Could not insert into Challenge Calendar User Pub Info";
		}
	}
}
?>