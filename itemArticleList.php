<?php
$account = load_class("Account");
$item = load_class("Item");
$itemArticle = load_class("ItemArticle");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$itemArticle->delete($d);
}
?>
        <div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="page" value="itemArticleEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="160" scope="col"><?=$_ITEM;?></th>
            <th width="100" scope="col"><?=$_NAME;?></th>
            <th width="60" scope="col"><?=$_MEASUREUNIT;?></th>
            <th width="60" scope="col"><?=$_DISPLAY_ORDER;?></th>
            <th width="60" scope="col"><?=$_MINVAL;?></th>
            <th width="60" scope="col"><?=$_MAXVAL;?></th>
            <th width="60" scope="col"><?=$_LEVEL1;?></th>
            <th width="60" scope="col"><?=$_LEVEL2;?></th>
            <th scope="col" colspan="2"><?=$_ACTION;?></th>
          </tr>
         </thead>
         <tbody>
        <?php
		$db = $itemArticle->sql("","B.display_order, A.display_order");
		$name_fld = "name_".$lang;
		$measure_fld = 'measurement_'.$lang;
		$i = 0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
                        $accName = ($db->f("accountID")?" - ".$account->get_field($db->f("accountID"),"name"):"");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$item->get_field($db->f('itemID'),$name_fld).$accName; ?></td>
            <td><?=$db->f($name_fld); ?></td>
            <td><?=$db->f($measure_fld); ?> (<?=$_DECIMAL.": ".$db->f('deci_num'); ?>)</td>
            <td><?=$db->f('display_order'); ?></td>
            <td><?=$db->f('min_Value'); ?></td>
            <td><?=$db->f('max_Value'); ?></td>
            <td><?=$db->f('value_1'); ?></td>
            <td><?=$db->f('value_2'); ?></td>
            <td width="7%" align="center">
               <form action="index.php" method="post" name="edit<?=$ID; ?>" >
               <input type="hidden" name="page" value="itemArticleEdit" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_EDIT;?>" />
               </form>
            </td>
            <td width="8%" align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="itemArticleList" >
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
               <input type="hidden" name="page" value="itemArticleEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
        </div>
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {8:{sorter: false}}});
	});	
	</script>
