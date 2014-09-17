<?php
if (!defined("ACCOUNT")) {

class Account {
	var $tbl = "account";
	var $key = "ID";
	
	function add_validate(&$d){
	    global $_PWD_NOT_SAME, $_PWD_REQUIRED;
		$valid = true;
		$username = $d['username'];
		$rows = $this->count("username='$username'");
		if ($rows) {
			$valid = false;
		}
		if ($d['password1'] && $d['password2'] && $d['password1']==$d['password2']) {
			$d['password'] = $d['password1'];
		} elseif ($d['password1'] && $d['password2'] && $d['password1']!=$d['password2']) {
			$d['error'] = $_PWD_NOT_SAME;
			$valid = false;
		} elseif (!$d['password1'] || !$d['password2']) {
			$d['error'] = $_PWD_REQUIRED;
			$valid = false;
		}
		return $valid;
	}
	
	function update_validate(&$d){
	    global $_PWD_NOT_SAME;
		$valid = true;
		$accountID = $d['ID'];
		$username = $d['username'];
		$rows = $this->count("username='$username' and ID != '$accountID'");
		if ($rows) {
			$valid = false;
		}
		if ($d['password1'] && $d['password2'] && $d['password1']==$d['password2']) {
			$d['password'] = $d['password1'];
		} elseif ($d['password1'] && $d['password2'] && $d['password1']!=$d['password2']) {
			$d['error'] = $_PWD_NOT_SAME;
			$valid = false;
		} else {
			$d['password']="";
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
		$q = "INSERT INTO $this->tbl ( username, name, gender, birthday, email, phone, timezone, authority, password, weight, glucose ) VALUES ('";
		$q .= $d['username']."','";
		$q .= $d['name']."','";
		$q .= $d['gender']."','";
		$q .= $d['birthday']."','";
		$q .= $d['email']."','";
		$q .= $d['phone']."','";
		$q .= $d['timezone']."','";
		$q .= $d['authority']."','";
		$q .= md5($d['password'])."','";
		$q .= $d['weight']."','";
		$q .= $d['glucose']."') ";
		$db->query($q);
		$d[$this->key] = mysql_insert_id();
		return true;
	}
	
	function update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
		$db = new ps_DB;
		$accountID = $d['ID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "username = '".$d['username']."',";
		$q .= "name = '".$d['name']."',";
		$q .= "gender = '".$d['gender']."',";
		$q .= "birthday = '".$d['birthday']."',";
		$q .= "email = '".$d['email']."',";
		$q .= "phone = '".$d['phone']."',";
		$q .= "timezone = '".$d['timezone']."',";
		$q .= "authority = '".$d['authority']."',";
		if ($d['password'])
			$q .= "password = '".md5($d['password'])."',";
		$q .= "weight = '".$d['weight']."',";
		$q .= "glucose = '".$d['glucose']."' ";
		$q .= "WHERE $this->key = '$accountID' ";
		$db->query($q);
		return true;
	}
	
	function user_update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
		$accountItem = load_class("AccountItem");
		$db = new ps_DB;
		$accountID = $d['ID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "username = '".$d['username']."',";
		$q .= "name = '".$d['name']."',";
		$q .= "gender = '".$d['gender']."',";
		$q .= "birthday = '".$d['birthday']."',";
		$q .= "email = '".$d['email']."',";
		$q .= "phone = '".$d['phone']."',";
		$q .= "timezone = '".$d['timezone']."',";
		if ($d['password'])
			$q .= "password = '".md5($d['password'])."',";
		$q .= "weight = '".$d['weight']."',";
		$q .= "glucose = '".$d['glucose']."' ";
		$q .= "WHERE $this->key = '$accountID' ";
		$db->query($q);
		$d["accountID"] = $accountID;
		$accountItem->delete($d);
		for ($i=0;$i<count($d["items"]);$i++) {
			$d["itemID"] = $d["items"][$i];
			$accountItem->add($d);
		}
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

	function get_field($id,$fld="username") {
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
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f("name")."</option>";
		}
		return $str;
	}
	
	function multi_select($fldname='accountIDs[]',$selVals="") {
	    global $_NONE;
		$db = $this->sql("","name");
		$str = "<select name='$fldname' size='8' multiple>";
		while ($db->next_record()) {
			$sel = in_array($db->f('ID'),$selVals)?"SELECTED":"";
			$str .= "<option value='".$db->f('ID')."' $sel>".$db->f("name")."(".$db->f("username").")"."</option>";
		}
		return $str;
	}

}

define("ACCOUNT",true);

} //END defined ACCOUNT

?>