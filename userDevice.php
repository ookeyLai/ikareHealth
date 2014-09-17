<?php
$device = load_class("Device");
$accountID = $userID;
$deviceID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$deviceSN = $_REQUEST['deviceSN'];
if ($action=='DELETE' && $deviceID) {
	$d['accountID'] = $accountID;
	$d['deviceID'] = $deviceID;
	$d['deviceSN'] = $deviceSN;
	$device->user_delete($d);
}
if ($action=='ADD' && $deviceSN) {
	$d['accountID'] = $accountID;
	$d['deviceID'] = $deviceID;
	$d['deviceSN'] = $deviceSN;
	$device->user_add($d);
}
?>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="20%"><?=$_NAME;?></th>
            <th width="15%"><?=$_MODEL;?></th>
            <th width="24%"><?=$_INFORMATION;?></th>
            <th width="32%"><?=$_SERIALNO;?></th>
            <th><?=$_ACTION;?></th>
          </tr>
         </thead>
         <tbody>
        <?php
        $name_fld = "name_".$lang;
		$db = $device->user_sql("accountID='$accountID'");
		$i = 0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$db->f($name_fld); ?></td>
            <td><?=$db->f('model'); ?></td>
            <td><?=$db->f('information'); ?></td>
            <td><?=$db->f('deviceSN'); ?></td>
            <td width="9%" align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="userDevice" >
               <input type="hidden" name="action" value="DELETE" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="hidden" name="deviceSN" value="<?=$db->f('deviceSN'); ?>" >
               <input type="submit" value="<?=$_DELETE;?>" onClick="return confirm('<?=$_DELETE_MSG;?>');" />
               </form>
            </td>
          </tr>
        <?php
		}
		$i++;
		$device_select = $device->select();
		?>
          <form action="index.php" method="post" name="add" >
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$device_select; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="deviceSN" size="20" /></td>
            <td width="9%" align="center">
               <input type="hidden" name="page" value="userDevice" >
               <input type="hidden" name="action" value="ADD" >
               <input type="submit" value="<?=$_ADD;?>" />
            </td>
          </tr>
          </form>
          </tbody>
        </table>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {2:{sorter: false}, 4:{sorter: false}}});
	});	
	</script>
