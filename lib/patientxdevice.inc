<?php
if (!defined("PATIENTXDEVICE")) {

class PatientXDevice {
	var $tbl = "patientxdevice";
	var $key = "PDID";
	
	function add_validate(&$d){
		$valid = true;
//		$patientID = $d['patientID'];
//		$deviceID = $d['deviceID'];
//		$rows = $this->count("patientID='$patientID' and deviceId='$deviceID'");
//		if ($rows) {
//			$valid = false;
//		}
		return $valid;
	}
	
	function update_validate(&$d){
		$valid = true;
//		$PDID = $d['PDID'];
//		$patientID = $d['patientID'];
//		$deviceID = $d['deviceID'];
//		$rows = $this->count("patientID='$patientID' and deviceId='$deviceID' and PDID != $PDID");
//		if ($rows) {
//			$valid = false;
//		}
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
		$now = date("Y-m-d");
		$db = new ps_DB;
		$patientID = $d['patientID'];
		$deviceID = $d['deviceID'];
		$q = "SELECT PDID FROM $this->tbl WHERE patientID='$patientID' AND deviceID='$deviceID' ";
		$db->query($q);
		$db->next_record();
		if ($db->f('PDID')) {
			$d["$this->key"] = $db->f("PDID");
		} else {
			$q = "INSERT INTO $this->tbl ( patientID, deviceID, createDate) VALUES ('";
			$q .= $patientID."','";
			$q .= $deviceID."','";
			$q .= $now."') ";
			$db->query($q);
			$d["$this->key"] = mysql_insert_id();
		}
		return true;
	}
	
	function update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
		$db = new ps_DB;
		$PDID = $d['PDID'];
		$patientID = $d['patientID'];
		$deviceID = $d['deviceID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "patientID = '$patientID',";
		$q .= "deviceID = '$deviceID' ";
		$q .= "WHERE $this->key = $PDID ";
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

	function get_field($id,$fld="userName") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl WHERE $this->key = '$id' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("$fld");
		else return "";
	}
}

define("PATIENTXDEVICE",true);

} //END defined PATIENTXDEVICE

?>