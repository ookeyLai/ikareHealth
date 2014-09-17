<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";

$upload_dir = "upload/";

$device = load_class("Device");
$item = load_class("Item");
$itemArticle = load_class("ItemArticle");
$itemEntry = load_class("ItemEntry");

$db = new ps_DB;

if ($dir = opendir($upload_dir)) {
	while (($file = readdir($dir)) !== false) {
		$fname = $upload_dir.$file;
		if (is_dir($fname)) continue;
		echo "Filename : $fname<br>";
		$fd = fopen($fname,"r");
		$dev_sn = trim(fgets($fd));
		echo "Device SN: $dev_sn<br>";
		$userdev = array();
		if ($userdev = $device->user_device($dev_sn)) {
			$userdev['itemID'] = $item->getItemID($userdev['deviceID']);
			$articleIDs = $itemArticle->getArticleIDs($userdev['itemID']);
			while (!feof($fd)) {
				$s = trim(fgets($fd));
				if ($s) {
					$flds = split(" ",$s);
					if (count($articleIDs)+10 != count($flds))
						continue;
					if ($flds[4]=='0')
						continue;
						
					$userdev['fieldD'] = $flds[1];
					$userdev['fieldB'] = $flds[2];
					$userdev['fieldA'] = $flds[3];
					$userdev['fieldP'] = $flds[4];
					$userdev['createdTime'] = $flds[5]."/".$flds[6]."/".$flds[7];
					$userdev['createdTime'] .= " ".$flds[8].":".$flds[9].":00";
					$userdev['articles'] = array();
					for ($i=10;$i<count($flds);$i++) {
						$k = $articleIDs[$i];
						$userdev['articles'][$k] = $flds[$i];
					}
					$itemEntry->import_add($userdev);
					if ($userdev['itemEntryID']) echo $userdev['itemEntryID']." at ".$userdev['createdTime']."<br>";
				}
				// echo "==================<br>";
			}
			fclose($fd);
		}
	}
	closedir($dir);
} else {
	echo "Dir $dir Not existed!";
}

?>
