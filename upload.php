<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";
function authUser($acc, $pwd) {
	if ($acc && $pwd) {
		$db = new ps_DB ();
		$pwd = md5 ( $pwd );
		$sql = "select ID, name, authority from account where username='$acc' and password='$pwd' ";
		$db->query ( $sql );
		if ($db->next_record ()) {
			return $db->f ( 'ID' );
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}

$acc = $_REQUEST ['account_id'];
$pwd = $_REQUEST ['account_pw'];
$ret = authUser ( $acc, $pwd );
if ($ret == 0) {
	echo "AUTH_ERROR";
	return;
}
$target_path = "upload/";
$dateSN = date ( "mdHis" );
$target_path = $target_path . $acc . "_" . $ret . "_" . $dateSN . "_" . basename ( $_FILES ['uploadedfile'] ['name'] );
if (move_uploaded_file ( $_FILES ['uploadedfile'] ['tmp_name'], $target_path )) {
	echo basename ( $_FILES ['uploadedfile'] ['name'] );
} else {
	echo "Error! (" . $target_path . ")";
}
?>
