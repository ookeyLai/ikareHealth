<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";
include "include/auth.inc";

$multi_person = 0;
$def_person = 1;
$DISPLAY_DAYS = 30;

$lang = $_SESSION['lang'];
$agroupID = $_SESSION['agroupID'];
if (!in_array($lang,$languages)) $lang='tw';
$lang_path="lang/{$lang}/lang.inc";
include $lang_path;

$account = load_class("Account");
$accountItem = load_class("AccountItem");
$item = load_class("Item");

$db = new ps_DB;

if ($_REQUEST['page']) {
	$page = $_REQUEST['page'].".php";
} else {
	$page = "logList.php";
}
$itemID = $_REQUEST['itemID'];
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh_tw" lang="zh_tw">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My Health</title>
<link href="myhealth.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="jquery/jquery-ui.css" />
<link rel="stylesheet" href="jquery/themes/blue/style.css" type="text/css" media="print, projection, screen" />
<script type='text/javascript' src='jquery/jquery-1.11.1.min.js'></script>
<script src="jquery/jquery-ui.js"></script>
<script type="text/javascript" src="jquery/jquery.timepicker.js"></script>
<link rel="stylesheet" type="text/css" href="jquery/jquery.timepicker.css" />
<script type="text/javascript" src="jquery/jquery.tablesorter.min.js"></script>

    <script>
    $(function() {
        $( "#menu1" ).menu();
        $( "#menu2" ).menu();
        $( "#menu3" ).menu();
        $( "#menu4" ).menu();
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
    <td height="20" colspan="3" align="right" valign="bottom">
    <?=$_WELCOME;?>&nbsp; <?=$name;?>&nbsp;&nbsp; [<a href="login.php?action=logout"><?=$_LOGOUT;?></a>]
    </td>
    </tr>
</table>
</div>
<table width="100%" border="0">
<tr>
        <td width="200" valign="top">
<ul id="menu1">
<center><B><?=$_HEALTH_LOG;?></B></center><hr>
  <li class="<?=($page=='logList.php'&&!$itemID?'ui-state-error':''); ?>"><a href="index.php"><?=$_ALL;?></a></li>
  <?php
  $items = $accountItem->items($userID);
  $item_fld = "name_".$lang;
  for ($i=0;$i<count($items);$i++) {
	  $xitemID = $items[$i];
	  $liclass = (($page=='logList.php'||$page=='logEdit.php') && $itemID==$xitemID)?'ui-state-error':'';
	  $itemName = $item->get_field($xitemID,$item_fld);
	  echo "<li class='$liclass'><a href='?page=logList&itemID=$xitemID'>$itemName</a></li>";
  }
  ?>
</ul>
<br />
<ul id="menu2">
<center><B><?=$_HEALTH_CHARTS;?></B></center><hr>
  <?php
  for ($i=0;$i<count($items);$i++) {
	  $xitemID = $items[$i];
	  $liclass = ($page=='logChart.php' && $itemID==$xitemID)?'ui-state-error':'';
	  $itemName = $item->get_field($xitemID,$item_fld);
	  echo "<li class='$liclass'><a href='?page=logChart&itemID=$xitemID'>$itemName</a></li>";
  }
  ?>
  </ul>
<br />
  <ul id="menu3">
<center><B><?=$_USER_SETTING;?></B></center><hr>
    <li class="<?=($page=='userAccount.php'?'ui-state-error':''); ?>"><a href="?page=userAccount"><?=$_ACCOUNT;?></a></li>
    <li class="<?=($page=='userDevice.php'?'ui-state-error':''); ?>"><a href="?page=userDevice"><?=$_DEVICE;?></a></li>
    <li class="<?=($page=='userCategory.php'?'ui-state-error':''); ?>"><a href="?page=userCategory"><?=$_CATEGORY;?></a></li>
    <li class="<?=($page=='userArticle.php'?'ui-state-error':''); ?>"><a href="?page=userArticle"><?=$_ARTICLE;?></a></li>
  </ul>
<br />
	<?php
	if (count($agroupID)>1) {
		echo "<form method=POST action=login.php>";
		$sel = "Acc:<select id=switchAcc name=switchAcc onChange='this.form.submit();'>";
		for ($i=0;$i<count($agroupID);$i++) {
			$mark = ($agroupID[$i]==$_SESSION['userID'])?"SELECTED":"";
			$sel .= "<option value='".$agroupID[$i]."' $mark>".$account->get_field($agroupID[$i],"username").": ".$account->get_field($agroupID[$i],"name")."</option>";
		}
		$sel .= "</select>";
		echo $sel;
		echo "</form>";
	}
	?>
<br />
<div id='sysdiv' style="display:none">
  <ul id="menu4">
<center><B><?=$_SYSTEM_SETTING;?></B></center><hr>
    <li class="<?=($page=='accountList.php'?'ui-state-error':''); ?>"><a href="?page=accountList"><?=$_ACCOUNT;?></a></li>
    <li class="<?=($page=='itemList.php'?'ui-state-error':''); ?>"><a href="?page=itemList"><?=$_ITEM;?></a></li>
    <li class="<?=($page=='itemArticleList.php'?'ui-state-error':''); ?>"><a href="?page=itemArticleList"><?=$_ARTICLE;?></a></li>
    <li class="<?=($page=='categoryList.php'?'ui-state-error':''); ?>"><a href="?page=categoryList"><?=$_CATEGORY;?></a></li>
    <li class="<?=($page=='deviceList.php'?'ui-state-error':''); ?>"><a href="?page=deviceList"><?=$_DEVICE;?></a></li>
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
