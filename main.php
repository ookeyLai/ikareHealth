<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";
include "include/auth.inc";

$db = new ps_DB;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh_tw" lang="zh_tw">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My Health</title>

<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<link href="myhealth.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="top_head" id="top_head">
<table width="100%" height="163" border="0" class="top_head">
  <tr>
    <td width="36%" height="114">&nbsp;</td>
    <td width="17%">&nbsp;</td>
    <td width="47%">&nbsp;</td>
  </tr>
  <tr>
    <td height="39" colspan="3" align="right" valign="bottom">Welcome <?=$name;?>  [<a href="login.php?action=logout">LOGOUT</a>]</td>
    </tr>
</table>
</div>
<table width="100%" border="0">
<tr>
        <td width="200" valign="top">
<div>
<ul id="MenuBar1" class="MenuBarVertical">
Health Log
  <li><a href="entryList.php">All</a></li>
  <li><a href="#">[User select 1]</a></li>
  <li><a href="#">[User select 2]</a></li>
  <li><a href="#">[User select 3]</a></li>
  <li><a href="#">[User select 4]</a></li>
</ul>
</div>
<br />
<div>
  <ul id="MenuBar2" class="MenuBarVertical">
    Health Charts
  <li><a href="#">[User select 1]</a></li>
  <li><a href="#">[User select 2]</a></li>
  <li><a href="#">[User select 3]</a></li>
  <li><a href="#">[User select 4]</a></li>
  </ul>
</div>
<br />
<div>
  <ul id="MenuBar3" class="MenuBarVertical">
    User Setting
    <li><a href="#">Account</a></li>
    <li><a href="#">Device</a></li>
    <li><a href="#">Category</a></li>
    <li><a href="#">Article</a></li>
  </ul>
</div>
<br />
<div id='sysdiv' style="display:none">
  <ul id="MenuBar4" class="MenuBarVertical">System
    <li><a href="accountList.php">Account</a></li>
    <li><a href="itemList.php">Item</a></li>
    <li><a href="categoryList.php">Category</a></li>
    <li><a href="deviceList.php">Device</a></li>
  </ul>
</div>
</td>
<td width="87%" height="480" align="center" valign="top" bgcolor="#CCCCCC">
        <div id="main_content">
        </div>
</td>
      </tr>
    </table>

<p>&nbsp;</p>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar2 = new Spry.Widget.MenuBar("MenuBar2", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar3 = new Spry.Widget.MenuBar("MenuBar3", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var MenuBar4 = new Spry.Widget.MenuBar("MenuBar4", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
<?php
if ($authority=="ADMIN") {
	echo "sysdiv=document.getElementById('sysdiv'); sysdiv.style.display='block';";
}
?>
</script>
</body>
</html>
