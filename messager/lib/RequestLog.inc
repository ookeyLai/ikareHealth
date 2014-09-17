<?php
if (!defined("REQUESTLOG")) {

class RequestLog {
	var $tbl = "requestlog";
	var $key = "messageID";
	
	function logging($mid, $aid) {
		if ($this->count("messageID='$mid' and accountID='$aid'")>0) return "OLD";
		$now = date("Y-m-d H:i:s");
		$db = new ps_DB;
		$q = "INSERT INTO $this->tbl ( messageID, accountID, requested ) VALUES ('";
		$q .= $mid."','";
		$q .= $aid."','";
		$q .= $now."') ";
		$db->query($q);
		
		return "NEW";
	}
	
	function delete(&$d) {
		$db = new ps_DB;
		if (!$this->delete_validate($d)) {
		  return False;
		}
		$ID = $d['ID'];
		$q = "DELETE from $this->tbl where messageID='" . $ID . "'";
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
	
}

define("REQUESTLOG",true);

} //END defined REQUESTLOG

?>