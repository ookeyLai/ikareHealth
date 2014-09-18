<?php
if (!defined("CATEGORY")) {

class Category {
	var $tbl = "category";
	var $key = "ID";
	
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
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
	    $fname = 'catename_'.$lang;
		if (!$d['accountID']) $d['accountID']=0;
		$db = new ps_DB;
		$q = "INSERT INTO $this->tbl ( itemID, $name_fld, type_Def, accountID ) VALUES ('";
		$q .= $d['itemID']."','";
		$q .= $d[$fname]."','";
		$q .= $d['typeDef']."','";
		$q .= $d['accountID']."') ";
		$db->query($q);
		$d[$this->key] = mysql_insert_id();
		return true;
	}
	
	function update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
	    $fname = 'catename_'.$lang;
		$db = new ps_DB;
		$ID = $d['ID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "itemID = '".$d['itemID']."',";
		$q .= "$name_fld = '".$d[$fname]."',";
		$q .= "type_Def = '".$d['typeDef']."',";
		$q .= "accountID = '".$d['accountID']."' ";
		$q .= "WHERE $this->key = '$ID' ";
		$db->query($q);
		return true;
	}
	
	function delete(&$d) {
		$db = new ps_DB;
		if (!$this->delete_validate($d)) {
		  return False;
		}
		$ID = $d['ID'];
		$q = "DELETE from $this->tbl where $this->key='" . $ID . "'";
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
	
	function log_select($fldname='ID',$selVal="",$d) {
	    global $_NONE;
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$accountID=$d['accountID'];
		$db = $this->sql("type_Def='SYSTEM' OR accountID='$accountID'");
		$str = "<select name='$fldname'><option value=''>--$_NONE--</option>";
		while ($db->next_record()) {
			$sel = $selVal==$db->f('ID')?"SELECTED":"";
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f($name_fld)."</option>";
		}
		return $str;
	}
	
}

define("CATEGORY",true);

} //END defined CATEGORY

?>