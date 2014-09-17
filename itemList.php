<?php
$item = load_class("Item");
$device = load_class("Device");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$item->delete($d);
}
?>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="itemEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="26%" scope="col"><?=$_ITEM;?></th>
            <th width="19%" scope="col"><?=$_DEVICE;?></th>
            <th width="14%" scope="col"><?=$_MODE;?></th>
            <th width="24%" scope="col"><?=$_USER_ARTICLE;?></th>
            <th width="8%" scope="col"><?=$_DISABLED;?></th>
            <th width="10%" scope="col"><?=$_DISPLAY_ORDER;?></th>
            <th scope="col" colspan="2"><?=$_ACTION;?></th>
          </tr>
         </thead>
         <tbody>
        <?php
		$db = $item->sqlAll();
		$name_fld = "name_".$lang;
		$i = 0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>" >
            <td><?=$db->f($name_fld); ?></td>
            <td><?=$device->get_field($db->f('deviceID'),$name_fld); ?></td>
            <td><?=$db->f('articleMode'); ?></td>
            <td><?=$db->f('userArticle'); ?></td>
            <td><?=$db->f('disabled'); ?></td>
            <td><?=$db->f('display_order'); ?></td>
            <td width="9%" align="center">
               <form action="index.php" method="post" name="edit<?=$ID; ?>" >
               <input type="hidden" name="page" value="itemEdit" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_EDIT;?>" />
               </form>
            </td>
            <td width="8%" align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="itemList" >
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
               <input type="hidden" name="page" value="itemEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
        </div>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {6:{sorter: false}}});
	});	
	</script>
