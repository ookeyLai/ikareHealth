<form id="form1" name="form1" method="post" action="index.php">
<input type="hidden" name="page" value="categoryEdit" />
<?php
$account = load_class("Account");
$item = load_class("Item");
$category = load_class("Category");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$d = $_REQUEST;
$lang = $_SESSION['lang'];
$name_fld = 'name_'.$lang;
$fname = 'catename_'.$lang;

if ($action=='ADD') {
	$category->add($d);
	backToPage("categoryList");
} elseif ($action=='EDIT'){
	$category->update($d);
	if (!$d['error']) echo "<font color='#0000FF'>$_DATA_UPDATED</font><hr>";
}
if ($d['error']) echo "<font color='#FF0000'>".$d['error']."</font><hr>";

if ($ID) {
	$db = $category->sql("A.ID = '$ID'");
	$db->next_record();
	$item_select = $item->select("itemID",$db->f("itemID"));
	$user_select = $account->select("accountID",$db->f("accountID"));
	$$fname = $db->f($name_fld);
	$typeSysChk = $db->f("type_Def")=='SYSTEM'?"CHECKED":"";
	$typeUserChk = $db->f("type_Def")=='USER'?"CHECKED":"";
	$action = $_UPDATE;
	echo "<input type='hidden' name='action' value='EDIT'>";
	echo "<input type='hidden' name='ID' value='$ID'>";
} else {
	$item_select = $item->select("itemID",$d["itemID"]);
	$user_select = $account->select("accountID",$d["accountID"]);
	$$fname = $d[$name_fld];
	$typeSysChk = $d["typeDef"]=='SYSTEM'?"CHECKED":"";
	$typeUserChk = $d["typeDef"]=='USER'?"CHECKED":"";
	$action = $_ADD;
	echo "<input type='hidden' name='action' value='ADD'>";
}
?>
  <div align="right"></div>
<table width="90%" border="0">
  <tr>
    <th height="30" colspan="2" bgcolor="#333333" scope="row"><font color="#FFFFFF"><?=$_CATEGORY;?></font> </th>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_ITEM;?></th>
    <td bgcolor="#FFFFFF"><?=$item_select; ?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_NAME;?></th>
    <td bgcolor="#FFFFFF"><input name="<?=$fname;?>" type="text" id="<?=$fname;?>" size="40" value="<?=$$fname; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_TYPE;?></th>
    <td bgcolor="#FFFFFF"><input type="radio" name="typeDef" id="typeDef" value="SYSTEM" <?=$typeSysChk; ?> />
      SYSTEM <input type="radio" name="typeDef" id="typeDef" value="USER" <?=$typeUserChk; ?> />
      USER</td>
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
