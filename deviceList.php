<?php
$device = load_class("Device");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$device->delete($d);
}
?>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="deviceEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="16%" scope="col"><?=$_NAME;?></th>
            <th width="20%" scope="col"><?=$_MODEL;?></th>
            <th width="27%" scope="col"><?=$_INFORMATION;?></th>
            <th width="6%" scope="col"><?=$_RECORDS;?></th>
            <th scope="col" colspan="2"><?=$_ACTION;?></th>
          </tr>
         </thead>
         <tbody>
        <?php
		$db = $device->sql();
		$name_fld = "name_".$lang;
		$i = 0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
			$drows = $device->user_count("deviceID='$ID'");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$db->f($name_fld); ?></td>
            <td><?=$db->f('model'); ?></td>
            <td><?=$db->f('information'); ?></td>
            <td><?=$drows; ?></td>
            <td width="5%" align="center">
               <form action="index.php" method="post" name="edit<?=$ID; ?>" >
               <input type="hidden" name="page" value="deviceEdit" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_EDIT;?>" />
               </form>
            </td>
            <td width="12%" align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="deviceList" >
               <input type="hidden" name="action" value="DELETE" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_DELETE;?>" onClick="return confirm('<?=$_DELETE_MSG;?>');" />
               </form>
            </td>
          </tr>
        <?php
		}
		?>
		 </tbody>
        </table>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="deviceEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
        </div>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {4:{sorter: false}}});
	});	
	</script>
