<form id="form1" name="form1" method="post" action="index.php">
<input type="hidden" name="page" value="itemEdit" />
<?php
$item = load_class("Item");
$device = load_class("Device");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$d = $_REQUEST;
$lang = $_SESSION['lang'];
$name_fld = 'name_'.$lang;

if ($action=='ADD') {
	$item->add($d);
	backToPage("itemList");
} elseif ($action=='EDIT'){
	$item->update($d);
	if (!$d['error']) echo "<font color='#0000FF'>$_DATA_UPDATED</font><hr>";
}
if ($d['error']) echo "<font color='#FF0000'>".$d['error']."</font><hr>";

if ($ID) {
	$db = $item->sqlAll("ID = '$ID'");
	$db->next_record();
	$_name = $db->f($name_fld);
	$device_select = $device->select("deviceID",$db->f("deviceID"));
	$modeListChk = $db->f("articleMode")=='LIST'?"CHECKED":"";
	$modeSelectChk = $db->f("articleMode")=='SELECT'?"CHECKED":"";
	$YesChk = $db->f("userArticle")=='YES'?"CHECKED":"";
	$NoChk = $db->f("userArticle")=='NO'?"CHECKED":"";
	$YesChk2 = $db->f("disabled")=='Yes'?"CHECKED":"";
	$NoChk2 = $db->f("disabled")=='No'?"CHECKED":"";
	$display_order = $db->f("display_order");
	$action = $_UPDATE;
	echo "<input type='hidden' name='action' value='EDIT'>";
	echo "<input type='hidden' name='ID' value='$ID'>";
} else {
	$_name = $d[$name_fld];
	$device_select = $device->select("deviceID",$d["deviceID"]);
	$modeListChk = $d['articleMode']=='LIST'?"CHECKED":"";
	$modeSelectChk = $d['articleMode']=='SELECT'?"CHECKED":"";
	$YesChk = $d['userArticle']=='YES'?"CHECKED":"";
	$NoChk = $d['userArticle']=='NO'?"CHECKED":"";
	$YesChk2 = $d['disabled']=='Yes'?"CHECKED":"";
	$NoChk2 = $d['disabled']=='No'?"CHECKED":"";
	$display_order = '0';
	$action = $_ADD;
	echo "<input type='hidden' name='action' value='ADD'>";
}
?>
  <div align="right"></div>
<table width="90%" border="0">
  <tr>
    <th height="30" colspan="2" bgcolor="#333333" scope="row"><font color="#FFFFFF"><?=$_ITEM;?></font> </th>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_NAME;?></th>
    <td bgcolor="#FFFFFF"><input name="<?=$name_fld;?>" type="text" id="<?=$name_fld;?>" size="40" value="<?=$_name; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_DEVICE;?></th>
    <td bgcolor="#FFFFFF"><?=$device_select; ?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_ARTICLE;?><?=$_MODE;?></th>
    <td bgcolor="#FFFFFF"><input type="radio" name="articleMode" id="articleMode" value="LIST" <?=$modeListChk; ?> />
      LIST <input type="radio" name="articleMode" id="articleMode" value="SELECT" <?=$modeSelectChk; ?> />
      SELECT</td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_USERDEFINE;?><?=$_ARTICLE;?></th>
    <td bgcolor="#FFFFFF"><input type="radio" name="userArticle" id="userArticle" value="YES" <?=$YesChk; ?> />
      YES <input type="radio" name="userArticle" id="userArticle" value="NO" <?=$NoChk; ?> />
      NO</td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_DISABLED;?></th>
    <td bgcolor="#FFFFFF"><input type="radio" name="disabled" id="disabled" value="Yes" <?=$YesChk2; ?> />
      YES <input type="radio" name="disabled" id="disabled" value="No" <?=$NoChk2; ?> />
      NO</td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_DISPLAY_ORDER;?></th>
    <td bgcolor="#FFFFFF"><input name="display_order" type="text" id="display_order" size="2" value="<?=$display_order; ?>" /></td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#333333" scope="row"><input type="submit" name="button" id="button" value="<?=$action; ?>" /></th>
  </tr>
</table>
</form>

        <div align="right"></div>
