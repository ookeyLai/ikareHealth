<form id="form1" name="form1" method="post" action="index.php">
<input type="hidden" name="page" value="groupsEdit" />
<?php
$groups = load_class("Groups");
$accountGroup = load_class("AccountGroup");
$account = load_class("Account");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$d = $_REQUEST;

if ($action=='ADD') {
	$groups->add($d);
	backToPage("groupsList");
} elseif ($action=='EDIT'){
	$groups->update($d);
	if (!$d['error']) echo "<font color='#0000FF'>$_DATA_UPDATED</font><hr>";
}
if ($d['error']) echo "<font color='#FF0000'>".$d['error']."</font><hr>";

if ($ID) {
	$db = $groups->sql("ID = '$ID'");
	$db->next_record();
	$_groupname = $db->f("groupname");
	$_parentID = $db->f("parentID");
	$accountIDs = $accountGroup->get_accounts($ID);
	$action = $_UPDATE;
	echo "<input type='hidden' name='action' value='EDIT'>";
	echo "<input type='hidden' name='ID' value='$ID'>";
} else {
	$_groupname = $d['groupname'];
	$_parentID = $d['parentID'];
	$accountIDs = array();
	$action = $_ADD;
	echo "<input type='hidden' name='action' value='ADD'>";
}
$parent_sel = $groups->parent_select('parentID',$_parentID,$ID);
$account_sel = $account->multi_select('accountIDs[]',$accountIDs);
?>
  <div align="right"></div>
<table width="90%" border="0">
  <tr>
    <th height="30" colspan="2" bgcolor="#333333" scope="row"><font color="#FFFFFF"><?=$_GROUPS;?></font> </th>
  </tr>
  <tr>
    <th width="19%" bgcolor="#999999" scope="row"><?=$_GROUPNAME;?></th>
    <td width="81%" bgcolor="#FFFFFF"><input name="groupname" type="text" id="groupname" size="20" value="<?=$_groupname; ?>" />
    </td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_PARENT_GROUPNAME;?></th>
    <td bgcolor="#FFFFFF"><?=$parent_sel;?>
    </td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_GROUP_ACCOUNT;?></th>
    <td bgcolor="#FFFFFF"><?=$account_sel;?>
    </td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#333333" scope="row"><input type="submit" name="button" id="button" value="<?=$action; ?>" /></th>
  </tr>
</table>
</form>
        <div align="right"></div>
