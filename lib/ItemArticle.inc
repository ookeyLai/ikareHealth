<?php
if (!defined("ITEMARTICLE")) {

class ItemArticle {
	var $tbl = "itemarticle";
	var $itemtbl = "item";
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
	    $measure_fld = 'measurement_'.$lang;
		if (!$d['accountID']) $d['accountID']=0;
		$db = new ps_DB;
		$q = "INSERT INTO $this->tbl ( itemID, $name_fld, $measure_fld, min_Value, max_Value, value_1, value_2, type_Def, accountID, display_order, display_default, deci_num ) VALUES ('";
		$q .= $d['itemID']."','";
		$q .= $d[$name_fld]."','";
		$q .= $d[$measure_fld]."','";
		$q .= $d['minValue']."','";
		$q .= $d['maxValue']."','";
		$q .= $d['value1']."','";
		$q .= $d['value2']."','";
		$q .= $d['typeDef']."','";
		$q .= $d['accountID']."','";
		$q .= $d['display_order']."','";
		$q .= $d['display_default']."','";
		$q .= $d['deci_num']."') ";
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
	    $measure_fld = 'measurement_'.$lang;
		$db = new ps_DB;
		$ID = $d['ID'];
		$q = "UPDATE $this->tbl SET ";
		$q .= "itemID = '".$d['itemID']."',";
		$q .= "$name_fld = '".$d[$name_fld]."',";
		$q .= "$measure_fld = '".$d[$measure_fld]."',";
		$q .= "min_Value = '".$d['minValue']."',";
		$q .= "max_Value = '".$d['maxValue']."',";
		$q .= "value_1 = '".$d['value1']."',";
		$q .= "value_2 = '".$d['value2']."',";
		$q .= "type_Def = '".$d['typeDef']."',";
		$q .= "accountID = '".$d['accountID']."',";
		$q .= "display_order = '".$d['display_order']."',";
		$q .= "display_default = '".$d['display_default']."',";
		$q .= "deci_num = '".$d['deci_num']."' ";
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
	
	function sql($where="", $orderby="A.display_order") {
		$db = new ps_DB;
		$q = "SELECT A.* FROM $this->tbl A LEFT JOIN $this->itemtbl B ON A.itemID=B.ID WHERE B.disabled='No' ";
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
			$q .= "WHERE $where ";
		}
		$db->query($q);
		$db->next_record();
		$rows = $db->f("rows");
		return $rows;
	}

	function get_field($id,$fld="name") {
	    $lang = $_SESSION['lang'];
	    if ($fld=="name") $fld = 'name_'.$lang;
		$db = new ps_DB;
		$q = "SELECT * FROM $this->tbl WHERE $this->key = '$id' ";
		$db->query($q);
		if ($db->next_record()) return $db->f("$fld");
		else return "";
	}
	
	function article_inputs($d, $vals=array(), $val=0) {
	    global $_NONE;
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
	    $measure_fld = 'measurement_'.$lang;
		$itemID = $d['itemID'];
		$accountID=$d['accountID'];
		$db = $this->sql("itemID='$itemID' and (type_Def='SYSTEM' OR accountID='$accountID')");
		$str = "";
		if ($d['articleMode']=="SELECT")
			$str = "<SELECT name='itemArticleID'><option value='0'>--$_NONE--</option>";
		while ($db->next_record()) {
			$aid = $db->f("ID");
			try {
				$v = $vals[$aid];
			} catch (Exception $e) {
				$v = "";
			}
			if ($d['articleMode']=="SELECT") {
				$sel = $val==$aid?"SELECTED":"";
				$vid = $val==$aid?$aid:"";
				$str .= "<option value='$aid' $sel>".$db->f($name_fld)." (".$db->f($measure_fld).")</option>";
			} else {
				$str .= $db->f($name_fld).": <input name='articles[$aid]' type=text size=20 value='".$v."'> ".$db->f($measure_fld)."<br />";
			}
		}
		if ($d['articleMode']=="SELECT")
			$str .= "</SELECT> : <input  name='articles[$vid]' type=text size=20 value='".$v."'> ";
		return $str;
	}
	
	function get_articles($itemID, $accID=0) {
	    $lang = $_SESSION['lang'];
	    $name_fld = 'name_'.$lang;
		$db = $this->sql("itemID='$itemID'".($accID?" AND (accountID=0 OR accountID='$accID')":""));
		$arr = array();
		while ($db->next_record()) {
			$aid = $db->f("ID");
			$arr[$aid] = $db->f($name_fld);
		}
		return $arr;
	}

	function getArticleIDs($itemID) {
		$db = $this->sql("itemID='$itemID'","field_index");
		$arr = array();
		while ($db->next_record()) {
			$arr[$db->f('field_index')] = $db->f("ID");
		}
		return $arr;
	}

	function get_article_HiLow($itemID,$accID=0) {
		$db = $this->sql("itemID='$itemID'".($accID?" AND (accountID=0 OR accountID='$accID')":""));
		$arr = array();
		while ($db->next_record()) {
			$aid = $db->f("ID");
			$arr[$aid] = array();
			$arr[$aid]['value1'] = $db->f('value_1');
			$arr[$aid]['value2'] = $db->f('value_2');
			$arr[$aid]['display_default'] = $db->f('display_default');
		}
		return $arr;
	}

}

define("ITEMARTICLE",true);

} //END defined ITEMARTICLE

?>
