<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";

function authUser($acc,$pwd) {
    if ($acc && $pwd) {
        $db = new ps_DB;
        $pwd = md5($pwd);
        $sql = "select ID, name, authority from account where username='$acc' and password='$pwd' ";
        $db->query($sql);
        if ($db->next_record()) {
            return $db->f('ID');
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

$groups = load_class("Groups");
$accountGroup = load_class("AccountGroup");
$messages = load_class("Messages");
$messageGroup = load_class("MessageGroup");
$requestLog = load_class("RequestLog");

$acc = $_REQUEST['account_id'];
$pwd = $_REQUEST['account_pw'];
$acc_id = authUser($acc,$pwd);
// if ($_REQUEST['acc_id']) $acc_id = $_REQUEST['acc_id'];
if ($acc_id==0) {
	echo "{'Error': 'No User Data!'}";
	exit(0);
}
$now = date("Y-m-d H:i:s");
$msg = array();
$db = $messages->sql("grouped='0'","published desc");
$i=0;
$msg['ALL'] = array();
while ($db->next_record()) {
	if ($db->f("published")>0 && $db->f("published")>$now) continue;
	if ($db->f("closed")>0 && $db->f("closed")<=$now) continue;
	$msg['ALL'][$i]['Time'] = $db->f("published")>0 ? date("Y-m-d",strtotime($db->f("published"))):date("Y-m-d");
	$msg['ALL'][$i]['Content'] = $db->f("message");
	$status = $requestLog->logging($db->f("ID"),$acc_id);
	$msg['ALL'][$i]['Status'] = $status;
	$i++;
}

$msg['GROUP'] = array();
$gp = array();
$db = $accountGroup->sql("accountID='$acc_id'");
while ($db->next_record()) {
	$gp[] = $gpid = $db->f("groupID");
	$db1 = $groups->sql("parentID='$gpid'");
	while ($db1->next_record()) {
		$gp[] = $db1->f("ID");
	}
}

if (count($gp)) {
	$gps = join(",",$gp);
	$db = $messageGroup->sql("groupID in (".$gps.")");
	$msgid = array();
	while ($db->next_record()) {
		$msgid[] = $db->f("messageID");
	}
	if (count($msgid)) {
		$msgids = join(",",$msgid);
		$db = $messages->sql("ID in (".$msgids.")","published desc");
		$i = 0;
		while ($db->next_record()) {
			if ($db->f("published")>0 && $db->f("published")>$now) continue;
			if ($db->f("closed")>0 && $db->f("closed")<=$now) continue;
			$msg['GROUP'][$i]['Time'] = $db->f("published")>0 ? date("Y-m-d",strtotime($db->f("published"))):date("Y-m-d");
			$msg['GROUP'][$i]['Content'] = $db->f("message");
			$status = $requestLog->logging($db->f("ID"),$acc_id);
			$msg['GROUP'][$i]['Status'] = $status;
			$i++;
		}
	}
}
$json_str = json_encode($msg);
// print_r(json_decode($json_str,true));
// echo "<hr>";
echo $json_str;
?>
