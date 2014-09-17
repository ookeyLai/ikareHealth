<?php
include "include/mysql_psw.inc";
include "lib/db_mysql.inc";
include "include/function.inc";

$upload_dir = "upload/";
$parsed_dir = "upload/parsed/";

$account = load_class("Account");
$device = load_class("Device");
$item = load_class("Item");
$itemArticle = load_class("ItemArticle");
$itemEntry = load_class("ItemEntry");

$db = new ps_DB;

// Mapping device users to account
// Using Device SN by default, otherwise using upload account
$a_uploaders = array("uploader");
$a_deviceSN = array("BP12010134300005");
$a_accMap = array("uploader"=>array(0,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45),
                  "BP12010134300005" => array(0,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45)
                 );
$k_vals = array("M"=>array(1.05355*0.00001, 4.871*0.001),"F"=>array(9.49353*0.000001, 4.245*0.001));

if ($dir = opendir($upload_dir)) {
	$fcnt = 0;
        $is_multi = false;
	while (($file = readdir($dir)) !== false) {
                $pat = split("_",$file);
                if (in_array($pat[0],$a_uploaders)) {
                   // echo $pat[0]."=".$pat[1]."<br>";
		   		   // Get upload account name
		   		   $is_multi = true;
                   $acc = $pat[0];
                   $accID = $pat[1];
                } else {
                   $acc = $pat[0];
                   $accID = $pat[1];
				}
				$fname = $upload_dir.$file;
				$pname = $parsed_dir.$file;
				if (is_dir($fname)) continue;
				echo "File: $fname<br>\n";
				$fd = fopen($fname,"r");
				$devsn = trim(fgets($fd));
                if (preg_match("/^#<([^>]+\w*[^>]+)>$/",$devsn,$matchs)) {
                   $dev_sn = $matchs[1];
		   			$acc = $dev_sn; // Using device SN by default, if device SN exists
		   			echo "Device SN: $dev_sn<br>\n";
                } else
		   			$dev_sn = "---";
				$userdev = array();
                $userdev['deviceID'] = 3; // i-Kare BP
		// if ($userdev = $device->user_device($dev_sn)) {
			$userdev['itemID'] = $item->getItemID($userdev['deviceID']);
			$articleIDs = $itemArticle->getArticleIDs($userdev['itemID']);
			while (!feof($fd)) {
				$s = trim(fgets($fd));
				if ($s && substr($s,0,1)!='#') {
					$flds = explode(",",$s);
					if (count($articleIDs)+11 != count($flds))
						continue;
					if ($flds[0]=='255')
						continue;
						
					$userdev['fieldD'] = $flds[1];
					$userdev['fieldB'] = $flds[2];
					$userdev['fieldA'] = $flds[3];
					$P = $userdev['fieldP'] = $flds[4];
					// Mapping to account ID
                    $userdev['accountID'] = ($is_multi?$a_accMap[$acc][$P]:$accID);
                    $gender = $account->get_field($userdev['accountID'],"gender");
					$userdev['createdTime'] = $flds[5]."/".$flds[6]."/".$flds[7];
					$userdev['createdTime'] .= " ".$flds[8].":".$flds[9].":00";
					$userdev['articles'] = array();
					for ($i=10;$i<15;$i++) {
						$k = $articleIDs[$i];
						$userdev['articles'][$k] = $flds[$i];
					}
					// flds[15~17] : SVp1,SVp2,SVp3
					$HR = $flds[10];
					$C = $flds[14];
					$A = $flds[15];
					$dP = $flds[16];
					$T = $flds[17];
					$R = ($T*$C)?1/($T*$C):0;
					$k = $k_vals[$gender][0] * $R + $k_vals[$gender][1];
					$SV = ($dP*$k)?$A/($dP*$k):0;
					$CO = $SV*$HR;
					// 16:SV, 17:CO
					$userdev['articles'][16] = $SV;
					$userdev['articles'][17] = $CO;
					$itemEntry->import_add($userdev);
					if ($userdev['itemEntryID']) echo "Create ".$userdev['itemEntryID']." at ".$userdev['createdTime']."<br>\n";
				}
				// echo "==================<br>";
			}
			fclose($fd);
			$fcnt++;
			rename($fname, $pname);
		//}
	}
	closedir($dir);
					print_r($userdev);
	echo "$fcnt files Completed!<br>\n";
} else {
	echo "Dir $dir Not existed!\n";
}

?>
