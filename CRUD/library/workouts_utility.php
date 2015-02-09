<?php 
class details {
	public $id = 0;
	public $title = "";
	public $start = "";
	public $end = "";
	public $description  = "";
	public $color = "";
	public $t_date = "";
}
class WorkoutsUtility {
	public $mys;

	var $global_index = 1;
	var $global_time = 1;
	var $warmup_index = 1;
	var $str_index = 1;
	var $cond_index = 1;
	var $speed_index = 1;
	var $core_index = 1;
	var $warmups = array();
	var $strength = array();
	var $condit = array();
	var $speed = array();
	var $core = array();
	var $rest = array();
	var $workouts = array();
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
	 * Gets Workouts
	 * params:
	 *	@u - user_id
	 *  @opt - " AND date = '<YYYY-MM-DD>' "
	 *
	 * returns:
	 *	@a - new array of all workouts
	 */
	function BuildWorkouts($u, $opt) {
		$query = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m-%d') AS date  
				 FROM workouts 
				 WHERE user_id = $u 
				 $opt 
				 ORDER BY date";	
		//echo $query;
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				$this->global_time = 1;
				$this->BuildWarmups($u, $row['date']);
				$this->BuildStrength($u, $row['date']);
				$this->BuildConditioning($u, $row['date']);
				$this->BuildSpeed($u, $row['date']);
				$this->BuildCore($u, $row['date']);
				$this->BuildRest($u, $row['date']);
				$this->ResetIndexes();
			}
			$result->free();
		}
		return $this->MergeWorkouts();
	}
	
	/*
	 * Builds Warmups
	 * params:
	 *	@u - user_id
	 *	@d - date
	 *	@t - time (for organization on calendar)
	 *
	 * returns:
	 *	nothing
	 */
	function BuildWarmups($u, $d) {
		//echo "BuildWARMUPS, $u, $d\n";
		$query = "SELECT  DATE_FORMAT(date, '%Y-%m-%d') AS date, warmup 
				 FROM workouts 
				 WHERE user_id = $u AND date = '$d'
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["warmup"]) > 3 ) {
					$detail = (object) array('id' => '', 'start' => '', 'end' => '',
							'title' => '', 'description' => '',
							'color' => '', 't_date' => '');
					$detail->id = "w_" . $this->warmup_index;
					if($this->global_time < 10) {
						$detail->start = $row["date"] . "T0".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T0".$this->global_time.":59:00";
					} else if ($this->global_time > 9 && $this->global_time < 13) {
						$detail->start = $row["date"] . "T".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T".$this->global_time.":59:00";
					}
				
					$detail->title = "Warm up " . $this->warmup_index;
					$detail->description .= $row["warmup"];
					$detail->color = "yellow";
					$detail->t_date = $row["date"];
					$this->warmup_index = $this->warmup_index + 1;
					$this->global_time = $this->global_time + 1;
					$this->warmups[] = $detail;
				}
			}
			$result->free();
		}
	}
	
	function BuildStrength($u, $d) {
		$query = "SELECT  DATE_FORMAT(date, '%Y-%m-%d') AS date, strength 
				 FROM workouts 
				 WHERE user_id = $u AND date = '$d'  
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["strength"]) > 3 ) {
					$detail = (object) array('id' => '', 'start' => '', 'end' => '',
							'title' => '', 'description' => '',
							'color' => '', 't_date' => '');
					$detail->id = "str_" . $this->str_index;
					if($this->global_time < 10) {
						$detail->start = $row["date"] . "T0".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T0".$this->global_time.":59:00";
					} else if ($this->global_time > 9 && $this->global_time < 13) {
						$detail->start = $row["date"] . "T".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T".$this->global_time.":59:00";
					}
				
					$detail->title = "Strength " . $this->str_index;
					$detail->description .= $row["strength"];
					$detail->color = "blue";
					$detail->t_date = $row["date"];
					$this->str_index = $this->str_index + 1;
					$this->global_time = $this->global_time + 1;
					$this->strength[] = $detail;
				}
			}
			$result->free();
		}
	}
	
	function BuildConditioning($u, $d) {
		$query = "SELECT  DATE_FORMAT(date, '%Y-%m-%d') AS date, conditioning 
				 FROM workouts 
				 WHERE user_id = $u AND date = '$d'  
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["conditioning"]) > 3 ) {
					$detail = (object) array('id' => '', 'start' => '', 'end' => '',
							'title' => '', 'description' => '',
							'color' => '', 't_date' => '');
					$detail->id = "con_" . $this->cond_index;
					if($this->global_time < 10) {
						$detail->start = $row["date"] . "T0".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T0".$this->global_time.":59:00";
					} else if ($this->global_time > 9 && $this->global_time < 13) {
						$detail->start = $row["date"] . "T".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T".$this->global_time.":59:00";
					}
				
					$detail->title = "Conditioning " . $this->cond_index;
					$detail->description .= $row["conditioning"];
					$detail->color = "orange";
					$detail->t_date = $row["date"];
					$this->cond_index = $this->cond_index + 1;
					$this->global_time = $this->global_time + 1;
					$this->condit[] = $detail;
				}
			}
			$result->free();
		}
	}
	
	function BuildSpeed($u, $d) {
		$query = "SELECT  DATE_FORMAT(date, '%Y-%m-%d') AS date, speed 
				 FROM workouts 
				 WHERE user_id = $u AND date = '$d'  
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["speed"]) >3 ) {
					$detail = (object) array('id' => '', 'start' => '', 'end' => '',
							'title' => '', 'description' => '',
							'color' => '', 't_date' => '');
					$detail->id = "spe_" . $this->speed_index;
					if($this->global_time < 10) {
						$detail->start = $row["date"] . "T0".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T0".$this->global_time.":59:00";
					} else if ($this->global_time > 9 && $this->global_time < 13) {
						$detail->start = $row["date"] . "T".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T".$this->global_time.":59:00";
					}
				
					$detail->title = "Speed " . $this->speed_index;
					$detail->description .= $row["speed"];
					$detail->color = "green";
					$detail->t_date = $row["date"];
					$this->speed_index = $this->speed_index + 1;
					$this->global_time = $this->global_time + 1;
					$this->speed[] = $detail;
				}
			}
			$result->free();
		}
	}
	
	function BuildCore($u, $d) {
		$query = "SELECT  DATE_FORMAT(date, '%Y-%m-%d') AS date, core 
				 FROM workouts 
				 WHERE user_id = $u AND date = '$d'  
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["core"]) > 3) {
					$detail = (object) array('id' => '', 'start' => '', 'end' => '',
							'title' => '', 'description' => '',
							'color' => '', 't_date' => '');
					$detail->id = "core_" . $this->core_index;
					if($this->global_time < 10) {
						$detail->start = $row["date"] . "T0".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T0".$this->global_time.":59:00";
					} else if ($this->global_time > 9 && $this->global_time < 13) {
						$detail->start = $row["date"] . "T".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T".$this->global_time.":59:00";
					}
				
					$detail->title = "Core " . $this->core_index;
					$detail->description .= $row["core"];
					$detail->color = "purple";
					$detail->t_date = $row["date"];
					$this->core_index = $this->core_index + 1;
					$this->global_time = $this->global_time + 1;
					$this->core[] = $detail;
				}
			}
			$result->free();
		}
	}
	
	function BuildRest($u, $d) {
		$query = "SELECT  DATE_FORMAT(date, '%Y-%m-%d') AS date, rest 
				 FROM workouts 
				 WHERE user_id = $u AND date = '$d'  
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["rest"]) > 0 ) {
					$detail = (object) array('id' => '', 'start' => '', 'end' => '',
							'title' => '', 'description' => '',
							'color' => '', 't_date' => '');
					$detail->id = "rest_1";
					if($this->global_time < 10) {
						$detail->start = $row["date"] . "T0".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T0".$this->global_time.":59:00";
					} else if ($this->global_time > 9 && $this->global_time < 13) {
						$detail->start = $row["date"] . "T".$this->global_time.":00:00";
						$detail->end = $row["date"] . "T".$this->global_time.":59:00";
					}
				
					$detail->title = "Rest Day";
					$detail->description .= $row["rest"];
					$detail->color = "rgb(96,137,233)";
					$detail->t_date = $row["date"];
					$this->global_time = $this->global_time + 1;
					$this->rest[] = $detail;
				}
			}
			$result->free();
		}
	}
	
	
	/*
	 * Get Warmup based on UID and Date
	 * params:
	 *	@u - user_id
	 *	@d - date
	 *	@r - rank
	 *
	 * returns:
	 *	nothing
	 */
	function GetWarmups($u, $d, $r) {
		$retVal = "-";
		$query = "SELECT  @rownum:=@rownum+1 AS rank, DATE_FORMAT(date, '%Y-%m-%d') AS date, warmup 
				 FROM workouts, (SELECT @rownum:=0) r  
				 WHERE user_id = $u AND date = '$d' 
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["warmup"]) > 3 && $r == $row['rank']) {
					$retVal = $row["warmup"];
				}
			}
			$result->free();
		}
		return $retVal;
	}
	
	function GetStrength($u, $d, $r) {
		$retVal = "-";
		$query = "SELECT  @rownum:=@rownum+1 AS rank, DATE_FORMAT(date, '%Y-%m-%d') AS date, strength 
				 FROM workouts, (SELECT @rownum:=0) r  
				 WHERE user_id = $u AND date = '$d' 
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["strength"]) > 3 && $r == $row['rank']) {
					$retVal = $row["strength"];
				}
			}
			$result->free();
		}
		return $retVal;
	}
	
	function GetConditioning($u, $d, $r) {
		$retVal = "-";
		$query = "SELECT  @rownum:=@rownum+1 AS rank, DATE_FORMAT(date, '%Y-%m-%d') AS date, conditioning 
				 FROM workouts, (SELECT @rownum:=0) r  
				 WHERE user_id = $u AND date = '$d' 
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["conditioning"]) > 3 && $r == $row['rank']) {
					$retVal = $row["conditioning"];
				}
			}
			$result->free();
		}
		return $retVal;
	}
	
	function GetSpeed($u, $d, $r) {
		$retVal = "-";
		$query = "SELECT  @rownum:=@rownum+1 AS rank, DATE_FORMAT(date, '%Y-%m-%d') AS date, speed  
				 FROM workouts, (SELECT @rownum:=0) r  
				 WHERE user_id = $u AND date = '$d' 
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["speed"]) > 3 && $r == $row['rank']) {
					$retVal = $row["speed"];
				}
			}
			$result->free();
		}
		return $retVal;
	}
	
	function GetCore($u, $d, $r) {
		$retVal = "-";
		$query = "SELECT  @rownum:=@rownum+1 AS rank, DATE_FORMAT(date, '%Y-%m-%d') AS date, core  
				 FROM workouts, (SELECT @rownum:=0) r  
				 WHERE user_id = $u AND date = '$d' 
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["core"]) > 3 && $r == $row['rank']) {
					$retVal = $row["core"];
				}
			}
			$result->free();
		}
		return $retVal;
	}
	
	function GetRest($u, $d, $r) {
		$retVal = "-";
		$query = "SELECT  @rownum:=@rownum+1 AS rank, DATE_FORMAT(date, '%Y-%m-%d') AS date, rest  
				 FROM workouts, (SELECT @rownum:=0) r  
				 WHERE user_id = $u AND date = '$d' 
				 ORDER BY date";		
		if ($result = $this->mys->query($query)) {
		   while ($row = $result->fetch_assoc()) {
				if( strlen($row["rest"]) > 0 && $r == $row['rank']) {
					$retVal = "Rest day";
				}
			}
			$result->free();
		}
		return $retVal;
	}
	
	/*
	 * Gets previous workout based on col name
	 * 
	 * params:
	 *	$u: user_id
	 *	$d: date
	 *	$col: column name
	 */
	function GetPrevWorkout($u, $d, $col) {
		$retVal = "-";
		$arr = (object) array("rank" => "", "info" => "-");
		$arr->rank = 0;
		$arr->info = '-';
		$query = "call getPrevWorkout($u, '{$d}', '{$col}')";
		//echo "$u, $d, $col\n$query\n";
		if ($rs = $this->mys->prepare($query)) {
			$rs->execute();
			$rs->store_result();
			$row_cnt = $rs->num_rows;
			if($row_cnt > 0) {
				$rs->bind_result($r, $d, $i);
				while ($rs->fetch()) {
					$arr->rank = $r;
					$arr->info = $i;
				}
			} 
			$rs->free_result();
			$this->mys->next_result();
		} else {
			$this->mys->error;
		}
		return $arr;
	}
	
	function ResetIndexes() {
		$this->warmup_index = 1;
		$this->str_index = 1;
		$this->cond_index = 1;
		$this->speed_index = 1;
		$this->core_index = 1;
	}
	
	function MergeWorkouts() {
		$main_count = 0;
		for ($x=0; $x<count($this->warmups); $x++) {
			$this->workouts[] = $this->warmups[$x];
		}

		for ($x=0; $x<count($this->strength); $x++) {
			$this->workouts[] = $this->strength[$x];
		}
		
		for ($x=0; $x<count($this->condit); $x++) {
			$this->workouts[] = $this->condit[$x];
		}
		
		for ($x=0; $x<count($this->speed); $x++) {
			$this->workouts[] = $this->speed[$x];
		}
		
		for ($x=0; $x<count($this->core); $x++) {
			$this->workouts[] = $this->core[$x];
		}
		
		for ($x=0; $x<count($this->rest); $x++) {
			$this->workouts[] = $this->rest[$x];
		}
		
		return $this->workouts;
	}
	
	/************ INSERT / DELETE ****************/
	
	function InsertWorkout($user_id, $warmup, $strength, $conditioning, $speed, $core, $rest, $date) {
		$query = "INSERT INTO workouts VALUES(?,?,?,?,?,?,?,?)";
		$retVal = 0;

		$stmt = $this->mys->prepare($query);
		$stmt->bind_param( 'isssssss', $user_id, $warmup, $strength, $conditioning, $speed, $core, $rest, $date);
		if($result = $stmt->execute()) {
			$stmt->close();
			$retVal = 1;
		} else {
			$retVal = 0;
		}
		return $retVal;
	}
	/*
	 * Inserts a workout into a specific column
	 *
	 * params:
	 * 	@uid - User id
	 * 	@date - date of workout
	 * 	@workout - the actual workout
	 * 	@column - the column the workout will be inserted into
	 *		either: warmup, strength, conditioning, speed, core
	 *
	 */
	function InsertSpecificWorkout($uid, $date, $workout, $column) {
		$query = "INSERT INTO workouts VALUES(?,?,?,?,?,?,?,?)";
		$warmup = "";
		$strength = "";
		$conditioning = "";
		$speed = "";
		$core = "";
		$rest = "";
		$retVal = 0;
		if($column == "warmup") {
			$warmup = $workout;
		} else if($column == "strength") {
			$strength = $workout;
		} else if($column == "conditioning") {
			$conditioning = $workout;
		} else if($column == "speed") {
			$speed = $workout;
		}  else if($column == "core") {
			$core = $workout;
		} else if($column == "rest") {
			$rest = $workout;
		}
		
		$stmt = $this->mys->prepare($query);
		$stmt->bind_param( 'isssssss', $uid, $warmup, $strength, $conditioning, $speed, $core, $rest, $date);
		if($result = $stmt->execute()) {
			$stmt->close();
			$retVal = 1;
		} else {
			$retVal = 0;
		}
		return $retVal;
	}
	/*
	 * Updates a workout into a specific column
	 *
	 * params:
	 * 	@uid - User id
	 * 	@date - date of workout
	 * 	@workout - the actual workout
	 * 	@column - the column the workout will be inserted into
	 *		either: warmup, strength, conditioning, speed, core
	 *
	 */
	function UpdateSpecificWorkout($uid, $date, $workout, $column) {
		$query = "";
	}
	
	function DeleteWorkout($user_id, $date) {
		$query = "DELETE FROM workouts WHERE user_id = ? AND date = ?";
		$retVal = 0;
		$stmt = $this->mys->prepare($query);
		$stmt->bind_param( 'is', $user_id, $date);
		if($result = $stmt->execute()) {
			$stmt->close();
			$retVal = 1;
		} else {
			$retVal = 0;
		}
		return $retVal;
	}
}
?>