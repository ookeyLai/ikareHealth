<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";

$lang = $_POST['lang'];
if (!$lang) $lang = $_SESSION['lang'];
if (!in_array($lang,$languages)) $lang='tw';
$lang_path="lang/{$lang}/lang.inc";
include $lang_path;
$_SESSION['lang'] = $lang;

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$_LOGIN;?></title>
</head>

<?php
$db = new ps_DB;

$action = $_GET['action'];
if ($action=='logout') {
	unset($_SESSION['name']);
	unset($_SESSION['username']);
	unset($_SESSION['authority']);
	unset($_SESSION['userID']);
} else {
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	if (!$errormsg) $errormsg = "";
	
	if ($username) {
		$sql = "select ID, name, authority from account where username='$username' and password='$password' ";
		$db->query($sql);
		if ($db->next_record() && $db->f('authority')=='ADMIN') {
			$_SESSION['username'] = $username;
			$_SESSION['name'] = $db->f('name');
			$_SESSION['authority'] = $db->f('authority');
			$_SESSION['userID'] = $db->f('ID');
		?>
		<script language="javascript">
		document.location="index.php";
		</script>
		<?php
		} else {
			unset($_SESSION['name']);
			unset($_SESSION['username']);
			unset($_SESSION['authority']);
			unset($_SESSION['userID']);
			$errormsg = $_LOGIN_ERRORMSG;
		}
	}
}
?>
<body>
<table width="300" border="1" align="center">
	<form id='form2' name='form2' method='post' action='login.php'>
	<tr>
    <td align="center"><?=$_LANGUAGE;?>: <select name='lang' onChange='this.form.submit();'>
        <?php
          foreach ($lang_str as $k=>$v) {
          	  if ($lang==$k) $sel = "SELECTED";
          	  else $sel = "";
          	  echo "<option value='$k' $sel>$v</option>";
		  }
        ?>
        </select>
    </td>
    </tr>
	</form>
<form id="form1" name="form1" method="post" action="login.php">
  <tr>
    <td align="center" bgcolor="#999999"><?=$_LOGIN;?></td>
  </tr>
  <tr>
    <td><?=$_USERNAME;?>: 
        <input name="username" type="text" id="username" size="20" />
    </td>
  </tr>
  <tr>
    <td><?=$_PASSWORD;?>: 
      <input name="password" type="password" id="password" size="20" /></td>
  </tr>
  	  <input type='hidden' name='lang' value='<?=$lang;?>'>
  <tr>
    <td align="center" bgcolor="#999999"><input type="submit" name="login" id="login" value="<?=$_SUBMIT;?>" /></td>
  </tr>
</form>
</table>
<?php
if ($errormsg) {
	echo "<center><font color=red>".$errormsg."</font></center>";
}
?>
</body>
</html>
