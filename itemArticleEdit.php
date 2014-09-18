<form id="form1" name="form1" method="post" action="index.php">
<input type="hidden" name="page" value="itemArticleEdit" />
<?php
$account = load_class("Account");
$item = load_class("Item");
$itemArticle = load_class("ItemArticle");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$d = $_REQUEST;
$lang = $_SESSION['lang'];
$name_fld = 'name_'.$lang;
$measure_fld = 'measurement_'.$lang;

if ($action=='ADD') {
	$itemArticle->add($d);
	backToPage("itemArticleList");
} elseif ($action=='EDIT'){
	$itemArticle->update($d);
	if (!$d['error']) echo "<font color='#0000FF'>$_DATA_UPDATED</font><hr>";
}
if ($d['error']) echo "<font color='#FF0000'>".$d['error']."</font><hr>";

if ($ID) {
	$db = $itemArticle->sql("A.ID = '$ID'");
	$db->next_record();
	$item_select = $item->select("itemID",$db->f("itemID"));
	$user_select = $account->select("accountID",$db->f("accountID"));
	$artname = $db->f($name_fld);
	$measurement = $db->f($measure_fld);
	$minValue = $db->f("min_Value");
	$maxValue = $db->f("max_Value");
	$value1 = $db->f("value_1");
	$value2 = $db->f("value_2");
	$display_order = $db->f("display_order");
	$deci_num = $db->f("deci_num");
	$typeSysChk = $db->f("type_Def")=='SYSTEM'?"CHECKED":"";
	$typeUserChk = $db->f("type_Def")=='USER'?"CHECKED":"";
	$defTrueChk = $db->f("display_default")=='true'?"CHECKED":"";
	$defFalseChk = $db->f("display_default")=='false'?"CHECKED":"";
	$action = $_UPDATE;
	echo "<input type='hidden' name='action' value='EDIT'>";
	echo "<input type='hidden' name='ID' value='$ID'>";
} else {
	$item_select = $item->select("itemID",$d["itemID"]);
	$user_select = $account->select("accountID",$d["accountID"]);
	$artname = $d[$name_fld];
	$measurement = $d[$measure_fld];
	$minValue = $d["minValue"];
	$maxValue = $d["maxValue"];
	$value1 = $d["value1"];
	$value2 = $d["value2"];
	$display_order = $d["display_order"];
	$deci_num = $d["deci_num"];
	$typeSysChk = $d["typeDef"]=='SYSTEM'?"CHECKED":"";
	$typeUserChk = $d["typeDef"]=='USER'?"CHECKED":"";
	$defTrueChk = $d["display_default"]=='true'?"CHECKED":"";
	$defFalseChk = $d["display_default"]=='false'?"CHECKED":"";
	$action = $_ADD;
	echo "<input type='hidden' name='action' value='ADD'>";
}
?>
  <div align="right"></div>
<table width="90%" border="0">
  <tr>
    <th height="30" colspan="2" bgcolor="#333333" scope="row"><font color="#FFFFFF"><?=$_ITEM;?><?=$_ARTICLE;?></font> </th>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_ITEM;?></th>
    <td bgcolor="#FFFFFF"><?=$item_select; ?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_NAME;?></th>
    <td bgcolor="#FFFFFF"><input name="<?=$name_fld;?>" type="text" id="<?=$name_fld;?>" size="40" value="<?=$artname; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_MEASUREUNIT;?></th>
    <td bgcolor="#FFFFFF"><input name="<?=$measure_fld;?>" type="text" id="<?=$measure_fld;?>" size="40" value="<?=$measurement; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_MINVAL;?></th>
    <td bgcolor="#FFFFFF"><input name="minValue" type="text" id="minValue" size="40" value="<?=$minValue; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_MAXVAL;?></th>
    <td bgcolor="#FFFFFF"><input name="maxValue" type="text" id="maxValue" size="40" value="<?=$maxValue; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_LEVEL1;?></th>
    <td bgcolor="#FFFFFF"><input name="value1" type="text" id="value1" size="40" value="<?=$value1; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_LEVEL2;?></th>
    <td bgcolor="#FFFFFF"><input name="value2" type="text" id="value2" size="40" value="<?=$value2; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_TYPE;?></th>
    <td bgcolor="#FFFFFF"><input type="radio" name="typeDef" id="typeDef" value="SYSTEM" <?=$typeSysChk; ?> />
      SYSTEM <input type="radio" name="typeDef" id="typeDef" value="USER" <?=$typeUserChk; ?> />
      USER</td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_DISPLAY_ORDER;?></th>
    <td bgcolor="#FFFFFF"><input name="display_order" type="text" id="display_order" size="4" value="<?=$display_order; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_DECIMAL;?></th>
    <td bgcolor="#FFFFFF"><input name="deci_num" type="text" id="deci_num" size="4" value="<?=$deci_num; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row">Chart Default</th>
    <td bgcolor="#FFFFFF"><input type="radio" name="display_default" id="display_default" value="true" <?=$defTrueChk; ?> />
      Yes <input type="radio" name="display_default" id="display_default" value="false" <?=$defFalseChk; ?> />
      No </td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_ACCOUNT;?></th>
    <td bgcolor="#FFFFFF"><?=$user_select; ?></td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#333333" scope="row"><input type="submit" name="button" id="button" value="<?=$action; ?>" /></th>
  </tr>
</table>
</form>

        <div align="right"></div>
