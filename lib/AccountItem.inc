<?php
if (!defined("ACCOUNTITEM")) {

class AccountItem {
	var $tbl = "accountitem";
	var $key = "accountID";
	var $itemtbl = "item";
	
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
		$q = "INSERT INTO $this->tbl ( accountID, itemID ) VALUES ('";
		$q .= $d['accountID']."','";
		$q .= $d['itemID']."') ";
		$db->query($q);
		return true;
	}
	
	function update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
		$db = new ps_DB;
		$ID = $d['accountID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "itemID = '".$d['itemID']."' ";
		$q .= "WHERE $this->key = '$ID' ";
		$db->query($q);
		return true;
	}
	
	function delete(&$d) {
		$db = new ps_DB;
		if (!$this->delete_validate($d)) {
		  return False;
		}
		$ID = $d['accountID'];
		$q = "DELETE from $this->tbl where $this->key='" . $ID . "'";
		$db->query($q);
		$db->next_record();
		
		return True;
	}
	
	function sql($where="", $orderby="B.display_order") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl A LEFT JOIN $this->itemtbl B ON A.itemID=B.ID WHERE B.disabled='No' ";
		if ($where) {
			$q .= "AND $where ";
		}
		if ($orderby) {
			$q .= "ORDER BY $orderby ";
		}
		$db->query($q);
		return $db;
	}

	function count($where="") {
		$db = new ps_DB;
		$q = "SELECT count(*) as rows FROM $this->tbl A LEFT JOIN $this->itemtbl B ON A.itemID=B.ID WHERE B.disabled='No' ";
		if ($where) {
			$q .= "AND $where ";
		}
		$db->query($q);
		$db->next_record();
		$rows = $db->f("rows");
		return $rows;
	}

	function get_field($id,$fld="itemID") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl WHERE $this->key = '$id' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("$fld");
		else return "";
	}

	function items($accountID) {
		$db = $this->sql("accountID='$accountID'");
		$ret = array();
		while ($db->next_record()) {
			$ret[] = $db->f("itemID");
		}
		return $ret;
	}
	
	function accounts($itemID) {
		$db = $this->sql("itemID='$itemID'","accountID");
		$ret = array();
		while ($db->next_record()) {
			$ret[] = $db->f("accountID");
		}
		return $ret;
	}
	
}

define("ACCOUNTITEM",true);

} //END defined ACCOUNTITEM

?>
