<?php
if (!defined("ITEMENTRY")) {

class ItemEntry {
	var $tbl = "itementry";
	var $subTbl = "itementryrow";
	var $key = "ID";
	var $linkkey = "itemEntryID";
	
	function add_validate(&$d){
		$valid = true;
		$d['createdTime'] = $d['c_date']." ".$d['c_time'];
		return $valid;
	}
	
	function import_add_validate(&$d){
		$valid = true;
		$valid = $this->count("accountID='".$d['accountID']."' AND fieldP='".$d['fieldP']."' AND createdTime='".$d['createdTime']."' ")>0  ?false : true;
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
		$q = "INSERT INTO $this->tbl ( accountID, itemID, deviceID, createdTime, categoryID, dataPath, note ) VALUES ('";
		$q .= $d['accountID']."','";
		$q .= $d['itemID']."','";
		$q .= $d['deviceID']."','";
		$q .= $d['createdTime']."','";
		$q .= $d['categoryID']."','";
		$q .= $d['dataPath']."','";
		$q .= $d['note']."') ";
		$db->query($q);
		$d[$this->key] = $d[$this->linkkey] = mysql_insert_id();
		$this->addSubTbl($d);
		return true;
	}
	
	function import_add(&$d) {
		if (!$this->import_add_validate($d)) {
			return false;
		}
		$db = new ps_DB;
		$q = "INSERT INTO $this->tbl ( accountID, itemID, deviceID, createdTime, fieldD, fieldB, fieldA, fieldP ) VALUES ('";
		$q .= $d['accountID']."','";
		$q .= $d['itemID']."','";
		$q .= $d['deviceID']."','";
		$q .= $d['createdTime']."','";
		$q .= $d['fieldD']."','";
		$q .= $d['fieldB']."','";
		$q .= $d['fieldA']."','";
		$q .= $d['fieldP']."') ";
		$db->query($q);
		$d[$this->key] = $d[$this->linkkey] = mysql_insert_id();
		$this->addSubTbl($d);
		return true;
	}
	
	function remove_import($acc_id) {
		$db = new ps_DB;
		$mainq = "SELECT $this->key from $this->tbl WHERE fieldP != 0 ";
		if ($acc_id) $mainq .= "AND accountID = '$acc_id' ";
		$q = "DELETE FROM $this->subTbl WHERE $this->linkkey In ( $mainq ) ";
		$db->query($q);
		$q = "DELETE FROM $this->tbl WHERE fieldP != 0 ";
		if ($acc_id) $q .= "AND accountID = '$acc_id' ";
		$db->query($q);
		return true;
	}
	
	function addSubTbl(&$d) {
		$db = new ps_DB;
		$articles = $d['articles'];
		if ($d['itemArticleID']) {
			$k = $d['itemArticleID'];
			$v = $articles[0];
			$q = "INSERT INTO $this->subTbl ( itemEntryID, itemArticleID, value ) VALUES ('";
			$q .= $d['itemEntryID']."','";
			$q .= $k."','";
			$q .= $v."') ";
			$db->query($q);
		} else {
			foreach ($articles as $k=>$v) {
				$q = "INSERT INTO $this->subTbl ( itemEntryID, itemArticleID, value ) VALUES ('";
				$q .= $d['itemEntryID']."','";
				$q .= $k."','";
				$q .= $v."') ";
				$db->query($q);
			}
		}
	}
	
	function update(&$d) {
		if (!$this->update_validate($d)) {
			return false;
		}
		$db = new ps_DB;
		$ID = $d['ID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "categoryID = '".$d['categoryID']."',";
		$q .= "value = '".$d['value']."',";
		$q .= "dataPath = '".$d['dataPath']."',";
		$q .= "note = '".$d['note']."' ";
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
		$q = "DELETE from $this->subTbl where $this->linkkey='" . $ID . "'";
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

	function get_field($id,$fld="name") {
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl WHERE $this->key = '$id' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("$fld");
		else return "";
	}
	
	function rowValues($id) {
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$db = new ps_DB;
		$itemArticle = load_class("ItemArticle");
		$q = "SELECT * from $this->subTbl WHERE itemEntryID='$id'";
		$db->query($q);
		$str = "";
		while ($db->next_record()) {
			$articleName = $itemArticle->get_field($db->f("itemArticleID"),$name_fld);
			$measurement = $itemArticle->get_field($db->f("itemArticleID"),"measurement");
			$str .= $articleName.": ".$db->f("value")." ".$measurement."<br>";
		}
		return $str;
	}
	
	function vals_arr($id) {
		$db = new ps_DB;
		$q = "SELECT * from $this->subTbl WHERE itemEntryID='$id'";
		$db->query($q);
		$arr = array();
		while ($db->next_record()) {
			$arr[$db->f("itemArticleID")] = $db->f("value");
		}
		return $arr;
	}

}

define("ITEMENTRY",true);

} //END defined ITEMENTRY

?>
