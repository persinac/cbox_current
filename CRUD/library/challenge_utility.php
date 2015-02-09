<?php 
class ChallengeUtility {
	public $mys;
	var $challenge_id = 0;
	var $end_date = "";
	var $start_date = "";
	var $title = "";
	
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
	 * Sets challenge_id
	 * params:
	 *	@u - user_id
	 */
	function SetChallengeID($id) {
		$this->challenge_id = $id;
	}
	
	function GetChallengeID() {
		return $this->challenge_id;
	}
	
	/* Set end date via date passed */
	function SetEndDate($date) {
		$this->end_date = $date;
	}
	
	/* Set end date via id passed */
	function SetEndDateByID($id) {
		$end_date = "";
		$query = "select exp_date  
			from challenge_table ct 
			WHERE challenge_id = $id";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				$end_date = $row['exp_date'];
			}
			$result->free();
		}
		$this->end_date = $end_date;
	}
	
	function GetEndDate() {
		return $this->end_date;
	}
	
	/* Set start date via date passed */
	function SetStartDate($date) {
		$this->start_date = $date;
	}
	
	/* Set end date via id passed */
	function SetStartDateByID($id) {
		$start_date = "";
		$query = "select start_date  
			from challenge_table ct 
			WHERE challenge_id = $id";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				$start_date = $row['start_date'];
			}
			$result->free();
		}
		$this->start_date = $start_date;
	}
	
	function GetStartDate() {
		return $this->start_date;
	}
	
	function SetTitle($id) {
		$title = "";
		$query = "SELECT title  
			FROM challenge_table ct 
			WHERE challenge_id = $id";
					
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				$title = $row['title'];
			}
			$result->free();
		}
		$this->title = $title;
	}
	
	function GetTitle() {
		return $this->title;
	}
}
?>