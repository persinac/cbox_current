<?php 
class ActivityLogConnection {
	public $mys;
	var $user_id = 0;
	var $fullname = "";
	var $username = "";
	var $log_date = "";
	var $show_on_feed = -1;
	var $challenge_title = "";
	var $activities = Array(
		"rest"=>"",
		"warmup"=>"",
		"strength"=>"",
		"conditioning"=>"",
		"speed"=>"",
		"core"=>"",
		"login"=>"",
		"update"=>"",
		"createWorkout"=>"", //either 1 or a 0 - then set rest, warmup, strength...
		"updateWorkout"=>"",
		"deleteWorkout"=>"",
		"logWorkout"=>"", //either 1 or a 0 - then set rest, warmup, strength...
		"createChallenge"=>"",
		"acceptChallenge"=>"",
		"declineChallenge"=>"",
		"updateChallenge"=>"",
		"submitScoreForChallenge"=>"",
		"updateProfile"=>""
	);
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
	
	
	/* SUBMIT ACTIVITY LOG 
		params:
			@activity - the activity string that is set by the user
			@s - show on feed, 1 or 0
	 */
	function SubmitToActivityLog($activity, $s) {
		$success = false;
		//$activity = _ActivityString($id);
		$show_on_feed = $s;
		$log_date = date('Y-m-d h:i:s');
		$stmt = $this->mys->prepare("insert into activity_feed values (?,?,?,?,?)"); 
		$stmt->bind_param( 'isssi', $this->user_id, $this->fullname, $activity, $log_date, $show_on_feed);
		if($result = $stmt->execute()) {
			$success = true;
			$stmt->close();
		} else {
			$success = false;
		}
		return $success;
	}
	
	function GetLogTimeStamp() {
		return date('Y-m-d');
	}
	
	function displayActivityFeed() {
		$name = "";
		$query = "select CONCAT(first_name, ' ', last_name) as name
		from user_info 
		WHERE user_id = $u";
		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				$name = $row['name'];
			}
			$result->free();
		}
		$this->fullname = $name;
	}
	
	/*************** GETTERS AND SETTERS *****************/
	
	/*
	 * Sets $this full name
	 * params:
	 *	@u - user_id
	 */
	function SetUserFullName($u) {
		$name = "";
		$query = "select CONCAT(first_name, ' ', last_name) as name
		from user_info 
		WHERE user_id = $u";
		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				$name = $row['name'];
			}
			$result->free();
		}
		$this->fullname = $name;
	}
	
	function GetUserFullName() {
		return $this->fullname;
	}
	
	function SetUserID($id) {
		$this->user_id = $id;
	}
	
	function GetUserID() {
		return $this->user_id;
	}
	
	function SetUsername($un) {
		$this->username = $un;
	}
	
	function GetUsername() {
		return $this->username;
	}
	
	function SetUpdateProfile($str) {
		$this->activities['updateProfile'] = $str;
	}
	
	function GetUpdateProfile() {
		return $this->activities['updateProfile'];
	}
	
	function SetRest($str) {
		$this->activities["rest"] = $str;
	}
	
	function GetRest() {
		return $this->activities["rest"];
	}
	
	function SetLogin($str) {
		$this->activities["login"] = $str;
	}
	
	function GetLogin() {
		return $this->activities["login"];
	}
	
	function SetDelete($str) {
		$this->activities["deleteWorkout"] = $str;
	}
	
	function GetDelete() {
		return $this->activities["deleteWorkout"];
	}
	
	function SetAcceptedChallenge($str) {
		$this->activities["acceptChallenge"] = $str;
	}
	
	function GetAcceptedChallenge() {
		return $this->activities["acceptChallenge"];
	}
	
	function SetDeclinedChallenge($str) {
		$this->activities["declineChallenge"] = $str;
	}
	
	function GetDeclinedChallenge() {
		return $this->activities["declineChallenge"];
	}
	
	function SetNewWorkout($str) {
		$this->activities["createWorkout"] = $str;
	}
	
	function SetNewWorkoutActivities($obj) {
		foreach ($obj as $key => $act) {
			foreach ($this->activities as $obj_key => $value) {
				if($key == $obj_key && strlen($act) > 0) {
					$this->activities["$obj_key"] = $act;
					
				}
			}
		}
	}
	
	function GetWorkout() {
		$retVal = "";
		foreach ($this->activities as $obj_key => $value) {
			if(strlen($value) > 0) {
				$retVal .= "$obj_key, ";
			}
		}
		return $retVal;
	}
	
}
?>