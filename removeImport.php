<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";

$itemEntry = load_class("ItemEntry");

$acc_id = 0;
if ($_REQUEST['acc_id']) $acc_id = $_REQUEST['acc_id'];

$db = new ps_DB;

$itemEntry->remove_import($acc_id);

echo "Import removed!!<br>";
?>
