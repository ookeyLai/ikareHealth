<form id="form1" name="form1" method="post" action="index.php">
<input type="hidden" name="page" value="userAccount" />
<?php
$account = load_class("Account");
$item = load_class("Item");
$accountItem = load_class("AccountItem");
$ID = $userID;
$action = $_REQUEST['action'];
$d = $_REQUEST;

if ($action=='EDIT'){
	$account->user_update($d);
	if (!$d['error']) {
		echo "<script>window.location.href='?page=userAccount&updated=1';</script>";
	}
}
if ($d['error']) echo "<font color='#FF0000'>".$d['error']."</font><hr>";
if ($d['updated']) echo "<font color='#0000FF'>$_DATA_UPDATED</font><hr>";

if ($ID) {
	$oldChk = $accountItem->items($ID);
	$db = $account->sql("ID = '$ID'");
	$db->next_record();
	$_username = $db->f("username");
	$_name = $db->f("name");
	$item_check = $item->checkbox('items',$oldChk);
	$genderM = $db->f("gender")=='M'?"CHECKED":"";
	$genderF = $db->f("gender")=='F'?"CHECKED":"";
	$birthday = $db->f("birthday");
	$email = $db->f("email");
	$phone = $db->f("phone");
	$timezone = $db->f("timezone");
	$weight = $db->f("weight");
	$glucose = $db->f("glucose");
	$action = $_UPDATE;
	echo "<input type='hidden' name='action' value='EDIT'>";
	echo "<input type='hidden' name='ID' value='$ID'>";
} else {
	$_username = $d['username'];
	$_name = $d['name'];
	$item_check = $item->checkbox('items');
	$genderM = $d['gender']=='M'?"CHECKED":"";
	$genderF = $d['gender']=='F'?"CHECKED":"";
	$birthday = $d['birthday'];
	$email = $d['email'];
	$phone = $d['phone'];
	$timezone = $d['timezone'];
	$weight = $d['weight'];
	$glucose = $d['glucose'];
	$action = $_ADD;
	echo "<input type='hidden' name='action' value='ADD'>";
}
?>
  <div align="right"></div>
<table width="90%" border="0">
  <tr>
    <th height="30" colspan="2" bgcolor="#333333" scope="row"><font color="#FFFFFF"><?=$_ACCOUNT;?></font> </th>
  </tr>
  <tr>
    <th width="19%" bgcolor="#999999" scope="row"><?=$_USERNAME;?></th>
    <td width="81%" bgcolor="#FFFFFF"><input type="text" name="username" id="username" size="40" value="<?=$_username; ?>" />
    </td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_TRACEING_ITEMS;?></th>
    <td bgcolor="#FFFFFF"><?=$item_check; ?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_NAME;?></th>
    <td bgcolor="#FFFFFF"><input name="name" type="text" id="name" size="40" value="<?=$_name; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_GENDER;?></th>
    <td bgcolor="#FFFFFF"><input type="radio" name="gender" id="gender" value="M" <?=$genderM; ?> />
      <?=$_MALE;?><input type="radio" name="gender" id="gender" value="F" <?=$genderF; ?> />
      <?=$_FEMALE;?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_BIRTHDAY;?></th>
    <td bgcolor="#FFFFFF"><input name="birthday" type="text" id="birthday" size="20" maxlength="12" value="<?=$birthday; ?>" /> 
      <?=$_YYYYMMDD;?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_EMAIL;?></th>
    <td bgcolor="#FFFFFF"><input name="email" type="text" id="email" size="40" value="<?=$email; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_PHONE;?></th>
    <td bgcolor="#FFFFFF"><input name="phone" type="text" id="phone" size="40" value="<?=$phone; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_TIMEZONE;?></th>
    <td bgcolor="#FFFFFF"><input name="timezone" type="text" id="timezone" size="20" value="<?=$timezone; ?>" />
      <?=$_TIMEZONE_TIP;?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_PASSWORD;?></th>
    <td bgcolor="#FFFFFF"><input type="password" name="password1" id="password1" size="40" value="" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_PASSWORD_CONFIRM;?></th>
    <td bgcolor="#FFFFFF"><input type="password" name="password2" id="password2" size="40" value="" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_WEIGHT_MEASUREMENT;?></th>
    <td bgcolor="#FFFFFF"><input name="weight" type="text" id="weight" size="40" value="<?=$weight; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_GLUCOSE_MEASUREMENT;?></th>
    <td bgcolor="#FFFFFF"><input name="glucose" type="text" id="glucose" size="40" value="<?=$glucose; ?>" /></td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#333333" scope="row"><input type="submit" name="button" id="button" value="<?=$action; ?>" /></th>
  </tr>
</table>
</form>
        <script type="text/javascript">
        $(function() {
                $("#birthday").datepicker(datePickerOpt);
                $("#birthday").datepicker("setDate","<?=$birthday=='0000-00-00'?'':$birthday;?>");
        });
        </script>

        <div align="right"></div>
