<?php
if (!defined("MESSAGES")) {

class Messages {
	var $tbl = "messages";
	var $key = "ID";
	
	function add_validate(&$d){
	    global $_NONE;
		$valid = true;
		if (!$d['message']) {
			$d['error'] = $_NO_MESSAGE_DEFINED;
			$valid = false;
		} 
		return $valid;
	}
	
	function update_validate(&$d){
		$valid = true;
		if (!$d['message']) {
			$d['error'] = $_NO_MESSAGE_DEFINED;
			$valid = false;
		} 
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
		$messageGroup = load_class("MessageGroup");
		$q = "INSERT INTO $this->tbl ( message, grouped, published, closed ) VALUES ('";
		$q .= $d['message']."','";
		$q .= $d['grouped']."','";
		$q .= $d['published']."','";
		$q .= $d['closed']."') ";
		$db->query($q);
		$d['messageID'] = $d[$this->key] = mysql_insert_id();
		if ($d['grouped']) $messageGroup->add($d);
		return true;
	}
	
	function update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
		$db = new ps_DB;
		$messageGroup = load_class("MessageGroup");
		$d['messageID'] = $messageID = $d['ID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "message = '".$d['message']."',";
		$q .= "grouped = '".$d['grouped']."',";
		$q .= "published = '".$d['published']."',";
		$q .= "closed = '".$d['closed']."' ";
		$q .= "WHERE $this->key = '$messageID' ";
		$db->query($q);
		$messageGroup->delete($d);
		if ($d['grouped']) $messageGroup->add($d);
		return true;
	}
		
	function delete(&$d) {
		if (!$this->delete_validate($d)) {
		  return False;
		}
		$db = new ps_DB;
		$messageGroup = load_class("MessageGroup");
		$ID = $d['ID'];
		$q = "DELETE from $this->tbl where $this->key='" . $ID . "'";
		$db->query($q);
		$db->next_record();
		
		$messageGroup->delete($d);
		
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

	function get_field($id,$fld="message") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl WHERE $this->key = '$id' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("$fld");
		else return "";
	}

	function select($fldname='ID',$selVal="") {
	    global $_NONE;
		$db = $this->sql();
		$str = "<select name='$fldname'><option value=''>--$_NONE--</option>";
		while ($db->next_record()) {
			$sel = $selVal==$db->f('ID')?"SELECTED":"";
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f("message")."</option>";
		}
		return $str;
	}
}

define("MESSAGES",true);

} //END defined MESSAGES

?>