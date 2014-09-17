<?php
if (!defined("DEVICE")) {

class Device {
	var $tbl = "device";
	var $accdevtbl = "accountdevice";
	var $key = "ID";
	
	function add_validate(&$d){
		$valid = true;
		return $valid;
	}
	
	function user_add_validate(&$d){
		$valid = true;
		return $valid;
	}
	
	function update_validate(&$d){
		$valid = true;
		return $valid;
	}

	function delete_validate(&$d){
		$valid = true;
		return $valid;
	}

	function add(&$d) {
		if (!$this->add_validate($d)) {
			return false;
		}
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$db = new ps_DB;
		$q = "INSERT INTO $this->tbl ( $name_fld, model, information) VALUES ('";
		$q .= $d[$name_fld]."','";
		$q .= $d['model']."','";
		$q .= $d['information']."') ";
		$db->query($q);
		$d[$this->key] = mysql_insert_id();
		return true;
	}
	
	function user_add(&$d) {
		if (!$this->user_add_validate($d)) {
			return false;
		}
		$db = new ps_DB;
		$q = "INSERT INTO $this->accdevtbl ( accountID, deviceID, deviceSN) VALUES ('";
		$q .= $d['accountID']."','";
		$q .= $d['deviceID']."','";
		$q .= $d['deviceSN']."') ";
		$db->query($q);
		return true;
	}
	
	function update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$db = new ps_DB;
		$ID = $d['ID'];
		$name = $d[$name_fld];
		$model = $d['model'];
		$information = $d['information'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "$name_fld = '$name',";
		$q .= "model = '$model',";
		$q .= "information = '$information' ";
		$q .= "WHERE $this->key = $ID ";
		$db->query($q);
		return true;
	}
	
	function delete(&$d) {
		$db = new ps_DB;
		if (!$this->delete_validate($d)) {
		  return False;
		}
		$q = "DELETE from $this->tbl where $this->key='" . $d["$this->key"] . "'";
		$db->query($q);
		$db->next_record();
		
		return True;
	}
	
	function user_delete(&$d) {
		$db = new ps_DB;
		$q = "DELETE from $this->accdevtbl where accountID='" . $d["accountID"] . "' and deviceID='" . $d["deviceID"] . "' and deviceSN='". $d["deviceSN"] . "'";
		$db->query($q);
		$db->next_record();
		
		return True;
	}
	
	function sql($where="", $orderby="") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl ";
		if ($where) {
			$q .= "WHERE $where ";
		}
		if ($orderby) {
			$q .= "ORDER BY $orderby ";
		}
		$db->query($q);
		return $db;
	}

	function user_sql($where="", $orderby="") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl A, $this->accdevtbl B ";
		$q .= "WHERE A.ID=B.deviceID ";
		if ($where) {
			$q .= "AND $where ";
		}
		if ($orderby) {
			$q .= "ORDER BY $orderby ";
		}
		$db->query($q);
		return $db;
	}
	
	function user_count($where="") {
		$db = new ps_DB;
		$q = "SELECT count(*) as rows FROM $this->accdevtbl ";
		if ($where) {
			$q .= "WHERE $where ";
		}
		$db->query($q);
                $db->next_record();
		$rows = $db->f("rows");
		return $rows;
	}

	function device_users($did) {
		$account = load_class("Account");
                $str = "";
                $db = new ps_DB;
                $q = "SELECT * FROM $this->accdevtbl ";
                $q .= "WHERE deviceID = '$did' ";
                $db->query($q);
                while ($db->next_record()) {
			$username = $account->get_field($db->f("accountID"),"name");
                        $str .= "<u>".$db->f("deviceSN")."</u> : $username<br>";
                }
                return $str;
	}
	
	function user_device($sn) {
		$userdev = array();
		$db = new ps_DB;
		$q = "SELECT * FROM $this->accdevtbl ";
		$q .= "WHERE deviceSN = '$sn' ";
		$db->query($q);
		if ($db->next_record()) {
			$userdev['deviceID'] = $db->f("deviceID");
			$userdev['accountID'] = $db->f("accountID");
			return $userdev;
		} else 
			return "";
	}
		
	function count($where="") {
		$db = new ps_DB;
		$q = "SELECT count(*) as rows FROM $this->tbl ";
		if ($where) {
			$q .= "WHERE $where ";
		}
		$db->query($q);
		$db->next_record();
		$rows = $db->f("rows");
		return $rows;
	}

	function get_field($id,$fld="name_tw") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl WHERE $this->key = '$id' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("$fld");
		else return "";
	}
	
	function select($fldname='ID',$selVal="") {
	    global $_NONE;
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$db = $this->sql();
		$str = "<select name='$fldname'><option value=''>--$_NONE--</option>";
		while ($db->next_record()) {
			$sel = $selVal==$db->f('ID')?"SELECTED":"";
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f($name_fld)."</option>";
		}
		return $str;
	}
	
}

define("DEVICE",true);

} //END defined DEVICE

?>
