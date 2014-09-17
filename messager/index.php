<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";
include "include/auth.inc";

$def_person = 1;

$lang = $_SESSION['lang'];
if (!in_array($lang,$languages)) $lang='tw';
$lang_path="lang/{$lang}/lang.inc";
include $lang_path;

$accountItem = load_class("AccountItem");
$item = load_class("Item");

$db = new ps_DB;

if ($_REQUEST['page']) {
	$page = $_REQUEST['page'].".php";
} else {
	$page = "messagesList.php";
}
$itemID = $_REQUEST['itemID'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh_tw" lang="zh_tw">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$_PROJECT_NAME;?></title>
<link href="myhealth.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="jquery/themes/blue/style.css" type="text/css" media="print, projection, screen" />
<link rel="stylesheet" href="jquery/jquery-ui.css" />
<script type='text/javascript' src='js/jquery.min.js'></script>
<script src="jquery/jquery-ui.js"></script>
<script type="text/javascript" src="jquery/jquery.tablesorter.min.js"></script>

    <script>
    $(function() {
        $( "#menu1" ).menu();
    });
    </script>
    <style>
    .ui-menu { width: 190px; }
    </style>

</head>

<body>
<div class="top_head" id="top_head">
<table width="100%" border="0" class="top_head">
  <tr>
    <td width="36%" height="40"><font size="+6" color="#000066"><?=$_PROJECT_NAME;?></font></td>
    <td width="17%">&nbsp;</td>
    <td width="47%">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="3" align="right" valign="bottom"><?=$_WELCOME;?>&nbsp; <?=$name;?>&nbsp;&nbsp; [<a href="login.php?action=logout"><?=$_LOGOUT;?></a>]</td>
    </tr>
</table>
</div>
<table width="100%" border="0">
<tr>
        <td width="200" valign="top">
<div id='sysdiv' style="display:none">
  <ul id="menu1">
<center><B><?=$_SYSTEM_SETTING;?></B></center><hr>
    <li class="<?=($page=='messagesList.php'||$page=='messagesEdit.php'?'ui-state-error':''); ?>"><a href="?page=messagesList"><?=$_MESSAGES;?></a></li>
    <li class="<?=($page=='groupsList.php'||$page=='groupsEdit.php'?'ui-state-error':''); ?>"><a href="?page=groupsList"><?=$_GROUPS;?></a></li>
    <li class="<?=($page=='accountList.php'||$page=='accountEdit.php'?'ui-state-error':''); ?>"><a href="?page=accountList"><?=$_ACCOUNT;?></a></li>
  </ul>
</div>
</td>
<td width="87%" height="480" align="center" valign="top" bgcolor="#CCCCCC">
        <div id="main_content">
        <?php
		include($page);
		?>
        </div>
</td>
      </tr>
    </table>

<p>&nbsp;</p>
<script>
<?php
if ($authority=="ADMIN") {
	echo "sysdiv=document.getElementById('sysdiv'); sysdiv.style.display='block';";
}
?>
</script>
</body>
</html>
