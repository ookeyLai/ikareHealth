<?php
$account = load_class("Account");
$item = load_class("Item");
$category = load_class("Category");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$category->delete($d);
}
?>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="categoryEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="16%" scope="col"><?=$_ITEM;?></th>
            <th width="32%" scope="col"><?=$_NAME;?></th>
            <th width="16%" scope="col"><?=$_TYPE;?></th>
            <th width="16%" scope="col"><?=$_USERNAME;?></th>
            <th scope="col" colspan="2"><?=$_ACTION;?></th>
          </tr>
         </thead>
         <tbody>
        <?php
		$db = $category->sql("","itemID");
		$name_fld = "name_".$lang;
		$i = 0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
			$username = $db->f('accountID')?$account->get_field($db->f('accountID'),"name"):"";
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$item->get_field($db->f('itemID'),$name_fld); ?></td>
            <td><?=$db->f($name_fld); ?></td>
            <td><?=$db->f('type_Def'); ?></td>
            <td><?=$username; ?></td>
            <td width="4%" align="center">
               <form action="index.php" method="post" name="edit<?=$ID; ?>" >
               <input type="hidden" name="page" value="categoryEdit" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_EDIT;?>" />
               </form>
            </td>
            <td width="12%" align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="categoryList" >
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
               <input type="hidden" name="page" value="categoryEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
        </div>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {3:{sorter: false}}});
	});	
	</script>
