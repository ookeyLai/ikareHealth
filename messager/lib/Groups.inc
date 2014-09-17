<?php
if (!defined("GROUPS")) {

class Groups {
	var $tbl = "groups";
	var $key = "ID";
	
	function add_validate(&$d){
	    global $_NONE;
		$valid = true;
		if (!$d['groupname']) {
			$d['error'] = $_NO_GROUP_NAME_DEFINED;
			$valid = false;
		} 
		return $valid;
	}
	
	function update_validate(&$d){
		$valid = true;
		if (!$d['groupname']) {
			$d['error'] = $_NO_GROUP_NAME_DEFINED;
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
		$accountGroup = load_class("AccountGroup");
		$q = "INSERT INTO $this->tbl ( groupname, parentID ) VALUES ('";
		$q .= $d['groupname']."','";
		$q .= $d['parentID']."') ";
		$db->query($q);
		$d['groupID'] = $d[$this->key] = mysql_insert_id();
		$accountGroup->add($d);
		return true;
	}
	
	function update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
		$db = new ps_DB;
		$accountGroup = load_class("AccountGroup");
		$d['groupID'] = $groupID = $d['ID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "groupname = '".$d['groupname']."',";
		$q .= "parentID = '".$d['parentID']."' ";
		$q .= "WHERE $this->key = '$groupID' ";
		$db->query($q);
		$accountGroup->delete($d);
		$accountGroup->add($d);
		return true;
	}
		
	function delete(&$d) {
		if (!$this->delete_validate($d)) {
		  return False;
		}
		$db = new ps_DB;
		$accountGroup = load_class("AccountGroup");
		$d['groupID'] = $ID = $d['ID'];
		$q = "DELETE from $this->tbl where $this->key='" . $ID . "'";
		$db->query($q);
		$db->next_record();
		
		$accountGroup->delete($d);
		
		return True;
	}
	
	function sql($where="", $orderby="") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl ";
		if ($where) {
			$q .= " WHERE $where ";
		}
		if ($orderby) {
			$q .= " ORDER BY $orderby ";
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
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f("groupname")."</option>";
		}
		return $str;
	}

	function parent_select($fldname='parentID',$selVal="",$myID=0) {
	    global $_NONE;
		$db = $this->sql("parentID='0' and ID != '$myID' ","groupname");
		$str = "<select name='$fldname'><option value='0'>--$_NONE--</option>";
		while ($db->next_record()) {
			$sel = $selVal==$db->f('ID')?"SELECTED":"";
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f("groupname")."</option>";
		}
		return $str;
	}
	
	function multi_select($fldname='groupIDs[]',$selVals="") {
	    global $_NONE;
		$db = $this->sql("parentID='0'","groupname");
		$str = "<select name='$fldname' size='8' multiple>";
		while ($db->next_record()) {
			$sel = in_array($db->f('ID'),$selVals)?"SELECTED":"";
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f("groupname")."</option>";
			$db1 = $this->sql("parentID='".$db->f('ID')."'","groupname");
			while ($db1->next_record()) {
				$sel = in_array($db1->f('ID'),$selVals)?"SELECTED":"";
				$str .= "<option value='".$db1->f('ID')."' $sel>____".$db1->f("groupname")."</option>";
			}
		}
		return $str;
	}
}

define("GROUPS",true);

} //END defined GROUPS

?>
