<form id="form1" name="form1" method="post" action="index.php">
<input type="hidden" name="page" value="deviceEdit" />
<?php
$device = load_class("Device");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$d = $_REQUEST;
$lang = $_SESSION['lang'];
$name_fld = 'name_'.$lang;

if ($action=='ADD') {
	$device->add($d);
	backToPage("deviceList");
} elseif ($action=='EDIT'){
	$device->update($d);
	if (!$d['error']) echo "<font color='#0000FF'>$_DATA_UPDATED</font><hr>";
}
if ($d['error']) echo "<font color='#FF0000'>".$d['error']."</font><hr>";

if ($ID) {
	$db = $device->sql("ID = '$ID'");
	$db->next_record();
	$_name = $db->f($name_fld);
	$model = $db->f("model");
	$information = $db->f("information");
        $users_list = $device->device_users($ID);
	$action = $_UPDATE;
	echo "<input type='hidden' name='action' value='EDIT'>";
	echo "<input type='hidden' name='ID' value='$ID'>";
} else {
	$_name = $d[$name_fld];
	$model = $d['model'];
	$information = $d['information'];
	$action = $_ADD;
	echo "<input type='hidden' name='action' value='ADD'>";
}
?>
  <div align="right"></div>
<table width="90%" border="0">
  <tr>
    <th height="30" colspan="2" bgcolor="#333333" scope="row"><font color="#FFFFFF"><?=$_DEVICE;?></font> </th>
  </tr>
  <tr>
    <th width="19%" bgcolor="#999999" scope="row"><?=$_NAME;?></th>
    <td width="81%" bgcolor="#FFFFFF"><input name="<?=$name_fld;?>" type="text" id="<?=$name_fld;?>" size="40" value="<?=$_name; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_MODEL;?></th>
    <td bgcolor="#FFFFFF"><input name="model" type="text" id="model" size="20" maxlength="12" value="<?=$model; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_INFORMATION;?></th>
    <td bgcolor="#FFFFFF"><input name="information" type="text" id="information" size="100" value="<?=$information; ?>" /></td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#333333" scope="row"><input type="submit" name="button" id="button" value="<?=$action; ?>" /></th>
  </tr>
  <?php if ($users_list) { ?>
  <tr>
    <th colspan="2" bgcolor="#DDEEDD" scope="row"><?=$users_list; ?></th>
  </tr>
  <?php } ?>
</table>
</form>
        <div align="right"></div>
