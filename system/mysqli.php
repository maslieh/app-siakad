<?php
require_once("config.php");

if ( !class_exists( 'DB' ) ) {
	
class DB {
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $total_time_db = 0;
	var $time_query = "";
	
	public function __construct($sqlserver, $sqluser, $sqlpassword, $database) {
		$this->db_connect_id = mysqli_connect($sqlserver, $sqluser, $sqlpassword, $database);
		if ($this->db_connect_id) {
			/*
			if ($database != "" && !@mysqli_select_db($database)) {
				@mysqli_close($this->db_connect_id);
				$this->db_connect_id = false;
			}
			*/
			return $this->db_connect_id;
		} else {
			return false;
		}
		
		/*
		if (mysqli_connect_errno())
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		*/  
	}

	function sql_close() {
		if ($this->db_connect_id) {
 			$result = @mysqli_close($this->db_connect_id);
			return $result;
 		} else {
			return false;
		}
	}

	function sql_query($query = "", $transaction = false) {
		unset($this->query_result);
		if ($query != "") {
			$this->query_result = @mysqli_query($this->db_connect_id, $query);
		}
		 
		if ($this->query_result) {
			//$this->num_queries += 1;
			//unset($this->row[$this->query_result]);
			//unset($this->rowset[$this->query_result]);
			return $this->query_result;
		} else {
			//return ($transaction == END_TRANSACTION) ? true : false;
		}
		 
	}

	function sql_numrows($query_id =0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$result = @mysqli_num_rows($query_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_affectedrows() {
		if ($this->db_connect_id) {
			$result = @mysqli_affected_rows($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_numfields($query_id ) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$result = @mysqli_num_fields($query_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_fieldname($offset, $query_id ) {
	
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$properties = mysqli_fetch_field_direct($query_id, $offset);
    		return is_object($properties) ? $properties->name : null;
		} else {
			return false;
		}
	}

	function sql_fieldtype($offset, $query_id) {
		if (!$query_id) $query_id = $this->query_result;
		if($query_id) {
			$properties = mysqli_fetch_field_direct($query_id, $offset);
    		return is_object($properties) ? $properties->type : null;
			
		} else {
			return false;
		}
	}

	function sql_fetchrow($query_id ) {
		if (!$query_id) $query_id = $this->query_result;
		if($query_id) {
      		return @mysqli_fetch_row($query_id);
 		} else {
			 return false;
		}
	}
	
 	function sql_fetchassoc($query_id) {
		if (!$query_id) $query_id = $this->query_result;
		if($query_id) {
     	return @mysqli_fetch_assoc($query_id);
 		} else {
			 return false;
		}
	}

 #mysqli_fetch_fields
 function sql_fetchfields($query_id) {
 		if (!$query_id) $query_id = $this->query_result;
		if($query_id) {
     		return @mysqli_fetch_fields($query_id);
 		} else {
			 return false;
		}
 
 }
	
	function sql_fetchrowset($query_id) {

		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while ($this->rowset[$query_id] = @mysqli_fetch_array($query_id,MYSQLI_ASSOC)) {
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		} else {
			return false;
		}
	}
	
	
/*
	function sql_fetchfield($field, $rownum = -1, $query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			if ($rownum > -1) {
				$result = @mysql_result($query_id, $rownum, $field);
			} else {
				if (empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {
					if ($this->sql_fetchrow()) {
						$result = $this->row[$query_id][$field];
					}
				} else {
					if ($this->rowset[$query_id]) {
						$result = $this->rowset[$query_id][0][$field];
					} else if ($this->row[$query_id]) {
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		} else {
			return false;
		}
	}

	function sql_rowseek($rownum, $query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		} else {
			return false;
		}
	}
 *
	function sql_freeresult($query_id = 0){
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);
			@mysqli_free_result($query_id);
			return true;
		} else {
			return false;
		}
	}
*/
 	function sql_nextid() {
		if ($this->db_connect_id) {
			$result = @mysqli_insert_id($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_error($query_id ) {
		$result["message"] = @mysqli_error($this->db_connect_id);
		$result["code"] = @mysqli_errno($this->db_connect_id);
		return $result;
	}
}

}
$koneksi_db = new DB($mysql_host, $mysql_user, $mysql_password, $mysql_database );

if (!$koneksi_db->db_connect_id) {	
die("<br /><br /><center><img src=\"images/logo-depan.png\"><br /><br /><b>Sepertinya Settingan atau Database anda bermasalah, silahkan di cek kembali.<br /><br />Support by : SSR.<br /><br /></center></b>");
}
?>
 