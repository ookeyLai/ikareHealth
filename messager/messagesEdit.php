<form id="form1" name="form1" method="post" action="index.php">
<input type="hidden" name="page" value="messagesEdit" />
<?php
$messages = load_class("Messages");
$messageGroup = load_class("MessageGroup");
$groups = load_class("Groups");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$d = $_REQUEST;

if ($action=='ADD') {
	$messages->add($d);
	backToPage("messagesList");
} elseif ($action=='EDIT'){
	$messages->update($d);
	if (!$d['error']) echo "<font color='#0000FF'>$_DATA_UPDATED</font><hr>";
}
if ($d['error']) echo "<font color='#FF0000'>".$d['error']."</font><hr>";

if ($ID) {
	$db = $messages->sql("ID = '$ID'");
	$db->next_record();
	$_message = $db->f("message");
	$grouped0Chk = $db->f("grouped")=='0'?"CHECKED":"";
	$grouped1Chk = $db->f("grouped")=='1'?"CHECKED":"";
	$published = $db->f('published')>0?date("Y-m-d",strtotime($db->f('published'))):"";
	$closed = $db->f('closed')>0?date("Y-m-d",strtotime($db->f('closed'))):"";
	$groupIDs = $messageGroup->get_groups($ID);
	$action = $_UPDATE;
	echo "<input type='hidden' name='action' value='EDIT'>";
	echo "<input type='hidden' name='ID' value='$ID'>";
} else {
	$_message = $d['message'];
	$grouped0Chk = $d["grouped"]=='0'?"CHECKED":"";
	$grouped1Chk = $d["grouped"]=='1'?"CHECKED":"";
	$published = $d["published"];
	$closed = $d["closed"];
	$groupIDs = array();
	$action = $_ADD;
	echo "<input type='hidden' name='action' value='ADD'>";
}
$group_sel = $groups->multi_select('groupIDs[]',$groupIDs);
?>
  <div align="right"></div>
<table width="90%" border="0">
  <tr>
    <th height="30" colspan="2" bgcolor="#333333" scope="row"><font color="#FFFFFF"><?=$_MESSAGES;?></font> </th>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_GROUPED;?></th>
    <td bgcolor="#FFFFFF">
    <input type="radio" name="grouped" id="gp_all" value="0" <?=$grouped0Chk; ?> /><?=$_GP_ALL;?><br />
    <input type="radio" name="grouped" id="gp_group" value="1" <?=$grouped1Chk; ?> /><?=$_GP_GROUP;?><br />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$group_sel;?>
    </td>
  </tr>
  <tr>
    <th width="19%" bgcolor="#999999" scope="row"><?=$_MESSAGE;?></th>
    <td width="81%" bgcolor="#FFFFFF"><textarea rows=10 cols=48 name="message" id="message"><?=$_message; ?></textarea>
    </td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_PUBLISHED;?></th>
    <td bgcolor="#FFFFFF"><input name="published" type="text" id="published" size="20" maxlength="12" value="<?=$published; ?>" /> 
      <?=$_YYYYMMDD;?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_CLOSED;?></th>
    <td bgcolor="#FFFFFF"><input name="closed" type="text" id="closed" size="20" maxlength="12" value="<?=$closed; ?>" /> 
      <?=$_YYYYMMDD;?></td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#333333" scope="row"><input type="submit" name="button" id="button" value="<?=$action; ?>" /></th>
  </tr>
</table>
</form>
        <div align="right"></div>
