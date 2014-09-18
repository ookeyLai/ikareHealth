<?php
$name_fld = "name_".$lang;
$measure_fld = "measurement_".$lang;
$item = load_class("Item");
$itemArticle = load_class("ItemArticle");
$ID = $_REQUEST['ID'];
$accountID = $userID;
$action = $_REQUEST['action'];
$itemID = $_REQUEST['itemID'];
$typeDef = $_REQUEST['typeDef'];
$artname = $_REQUEST['artname'];
$measurement = $_REQUEST['measurement'];
$deci_num = $_REQUEST['deci_num'];
$minValue = $_REQUEST['minValue'];
$maxValue = $_REQUEST['maxValue'];
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$itemArticle->delete($d);
}
if ($action=='ADD' && $itemID && $artname) {
	$d['accountID'] = $accountID;
	$d['itemID'] = $itemID;
	$d['typeDef'] = $typeDef;
	$d[$name_fld] = $artname;
	$d[$measure_fld] = $measurement;
	$d['deci_num'] = $deci_num;
	$d['minValue'] = $minValue;
	$d['maxValue'] = $maxValue;
	$itemArticle->add($d);
}
?>
        <table id="loglist" class="tablesorter" width="100%" border="0">
         <thead>
          <tr>
            <th width="20%" scope="col"><?=$_ITEM;?></th>
            <th width="26%" scope="col"><?=$_NAME;?></th>
            <th width="18%" scope="col"><?=$_MEASUREUNIT;?></th>
            <th width="8%" scope="col"><?=$_DECIMAL;?></th>
            <th width="10%" scope="col"><?=$_MINVAL;?></th>
            <th width="10%" scope="col"><?=$_MAXVAL;?></th>
            <th><?=$_ACTION;?></th>
          </tr>
         </thead>
         <tbody>
        <?php
		$db = $itemArticle->sql("accountID='$userID'","itemID");
		$i=0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$item->get_field($db->f('itemID'),$name_fld); ?></td>
            <td><?=$db->f($name_fld); ?></td>
            <td><?=$db->f($measure_fld); ?></td>
            <td><?=$db->f('deci_num'); ?></td>
            <td><?=$db->f('min_Value'); ?></td>
            <td><?=$db->f('max_Value'); ?></td>
            <td width="16%" align="center">
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="userArticle" >
               <input type="hidden" name="action" value="DELETE" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_DELETE;?>" onClick="return confirm('<?=$_DELETE_MSG;?>');" />
               </form>
            </td>
          </tr>
        <?php
		}
		$i++;
		$item_select = $item->user_select("itemID");
		?>
          <form action="index.php" method="post" name="add" >
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$item_select; ?></td>
            <td><input name="artname" size="40" /></td>
            <td><input name="measurement" size="20" /></td>
            <td><input name="deci_num" size="4" /></td>
            <td><input name="minValue" size="10" /></td>
            <td><input name="maxValue" size="10" /></td>
            <td width="16%" align="center">
               <input type="hidden" name="page" value="userArticle" >
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
		$("#loglist").tablesorter({headers: {6:{sorter: false}}});
	});	
	</script>
