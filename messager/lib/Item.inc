<?php
if (!defined("ITEM")) {

class Item {
	var $tbl = "item";
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
		$db = new ps_DB;
		$q = "INSERT INTO $this->tbl ( $name_fld, deviceID, articleMode, userArticle ) VALUES ('";
		$q .= $d[$name_fld]."','";
		$q .= $d['deviceID']."','";
		$q .= $d['articleMode']."','";
		$q .= $d['userArticle']."') ";
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
		$db = new ps_DB;
		$ID = $d['ID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "$name_fld = '".$d[$name_fld]."',";
		$q .= "deviceID = '".$d['deviceID']."',";
		$q .= "articleMode = '".$d['articleMode']."',";
		$q .= "userArticle = '".$d['userArticle']."' ";
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
	    $lang = $_SESSION['lang'];
	    if ($fld=="name") $fld = 'name_'.$lang;
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl WHERE $this->key = '$id' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("$fld");
		else return "";
	}

	function getItemID($devID) {
		$db = new ps_DB;
		$q = "SELECT ID FROM $this->tbl WHERE deviceID = '$devID' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("ID");
		else return "";
	}

	function select($fldname='ID',$selVal="") {
	    global $_NONE;
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$db = $this->sql("","ID");
		$str = "<select name='$fldname'><option value=''>--$_NONE--</option>";
		while ($db->next_record()) {
			$sel = $selVal==$db->f('ID')?"SELECTED":"";
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f($name_fld)."</option>";
		}
		return $str;
	}
	
	function user_select($fldname='ID',$selVal="") {
	    global $_NONE;
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$db = $this->sql("userArticle='YES'","ID");
		$str = "<select name='$fldname'><option value=''>--$_NONE--</option>";
		while ($db->next_record()) {
			$sel = $selVal==$db->f('ID')?"SELECTED":"";
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f($name_fld)."</option>";
		}
		return $str;
	}
	
	function checkbox($fldname='ID',$selVals=array()) {
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$db = $this->sql("","ID");
		$str = "";
		while ($db->next_record()) {
			$sel = in_array($db->f('ID'),$selVals)?"CHECKED":"";
			$str .= "<input type='checkbox' name='{$fldname}[]' value='".$db->f("ID")."' $sel>".$db->f($name_fld)."<br>";
		}
		return $str;
	}
	
}

define("ITEM",true);

} //END defined ITEM

?>