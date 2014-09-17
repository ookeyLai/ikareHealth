<?php
$name_fld = "name_".$lang;
$catename_fld = "catename_".$lang;
$item = load_class("Item");
$category = load_class("Category");
$ID = $_REQUEST['ID'];
$accountID = $userID;
$action = $_REQUEST['action'];
$itemID = $_REQUEST['itemID'];
$typeDef = $_REQUEST['typeDef'];
$catename = $_REQUEST['catename'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$category->delete($d);
}
if ($action=='ADD' && $itemID && $catename) {
	$d['accountID'] = $accountID;
	$d['itemID'] = $itemID;
	$d['typeDef'] = $typeDef;
	$d[$catename_fld] = $catename;
	$category->add($d);
}
?>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="28%" scope="col"><?=$_ITEM;?></th>
            <th width="52%" scope="col"><?=$_NAME;?></th>
            <th><?=$_ACTION;?></th>
          </tr>
         </thead>
         <tbody>
        <?php
		$db = $category->sql("accountID='$userID'","itemID");
        $i=0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$item->get_field($db->f('itemID'),$name_fld); ?></td>
            <td><?=$db->f($name_fld); ?></td>
            <td width="20%" align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="userCategory" >
               <input type="hidden" name="action" value="DELETE" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_DELETE;?>" onClick="return confirm('<?=$_DELETE_MSG;?>');" />
               </form>
            </td>
          </tr>
        <?php
		}
		$item_select = $item->select("itemID");
		$i++;
		?>
          <form action="index.php" method="post" name="add" >
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$item_select; ?></td>
            <td><input name="catename" size="40" /></td>
            <td width="20%" align="center">
               <input type="hidden" name="page" value="userCategory" >
               <input type="hidden" name="typeDef" value="USER" >
               <input type="hidden" name="action" value="ADD" >
               <input type="submit" value="<?=$_ADD;?>" />
            </td>
          </tr>
          </form>
         </tbody>
        </table>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {2:{sorter: false}}});
	});	
	</script>
