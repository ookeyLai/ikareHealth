<?php
if (!defined("ACCOUNTGROUP")) {

class AccountGroup {
	var $tbl = "accountgroup";
	var $key = "accountID";
	
	function add_validate(&$d){
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
		$db = new ps_DB;
		for ($i=0;$i<count($d['accountIDs']);$i++) {
			$q = "INSERT INTO $this->tbl ( groupID, accountID ) VALUES ('";
			$q .= $d['groupID']."','";
			$q .= $d['accountIDs'][$i]."') ";
			$db->query($q);
		}
		return true;
	}
	
	function delete(&$d) {
		$db = new ps_DB;
		if (!$this->delete_validate($d)) {
		  return False;
		}
		$ID = $d['ID'];
		$q = "DELETE from $this->tbl where groupID='" . $ID . "'";
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

	function get_field($id,$fld="username") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl WHERE $this->key = '$id' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("$fld");
		else return "";
	}

	function get_accounts($id) {
		$ret = array();
		$db = $this->sql("groupID='$id'","accountID");
		while ($db->next_record()) {
			$ret[] = $db->f('accountID');
		}
		return $ret;
	}

}

define("ACCOUNTGROUP",true);

} //END defined ACCOUNTGROUP

?>