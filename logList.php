<?php
$item = load_class("Item");
$acountItem = load_class("AccountItem");
$itemArticle = load_class("ItemArticle");
$itemEntry = load_class("ItemEntry");
$category = load_class("Category");
$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$IKARE_ITEM = 6;
$PAGE_SIZE = 10;
$offset = 0;
if ($action=='DELETE' && $ID) {
	$d['ID'] = $ID;
	$itemEntry->delete($d);
}
$d['accountID']=$userID;
$d['itemID'] = $itemID = $_REQUEST['itemID'];
$frompage = $_REQUEST['frompage']?$_REQUEST['frompage']:1;
$items = $accountItem->items($userID);
if ($_REQUEST['submit']) { $_SESSION['categoryID']=""; $_SESSION['fromDate']=""; $_SESSION['toDate']=""; }
if ($_SESSION['categoryID']) $d['categoryID'] = $categoryID = $_SESSION['categoryID'];
else $_SESSION['categoryID'] = $d['categoryID'] = $categoryID = $_REQUEST['categoryID'];

if ($_SESSION['fromDate']) $fromDate = $_SESSION['fromDate'];
else $_SESSION['fromDate'] = $fromDate = $_REQUEST['fromDate'];
if ($_SESSION['toDate']) $toDate = $_SESSION['toDate'];
else $_SESSION['toDate'] = $toDate = $_REQUEST['toDate'];
if (!$fromDate) $fromDate = date("Y-m-d",time()-$DISPLAY_DAYS*24*60*60);
else $fromDate = date("Y-m-d",strtotime($fromDate));
if (!$toDate) $toDate = date("Y-m-d",time());
else $toDate = date("Y-m-d",strtotime($toDate));
if ($fromDate>$toDate) {
	$tmp = $fromDate;
	$fromDate = $toDate;
	$toDate = $tmp;
}
$cate_select = $category->log_select('categoryID',$categoryID,$d);
$item_fld = "name_".$lang;
$itemName = $item->get_field($itemID, $item_fld);
$itemName = $itemName?$itemName:"$_ALL";
?>
<!--
Log List <?php if ($itemName) echo "of $itemName"; ?>
        <hr color="#000099" />
// -->
        <div align="right">
               <form action="index.php" method="post" name="search" >
               <?=$_LIST_DATE;?>: <input name="fromDate" id="fromDate" type="text" size="12" value="<?=$fromDate; ?>" />
               ~ <input name="toDate" id="toDate" type="text" size="12" value="<?=$toDate; ?>" />
               <?=$_CATEGORY;?>:<?=$cate_select; ?>
               <input type="hidden" name="itemID" value="<?=$itemID; ?>" >
               <input type="hidden" name="page" value="logList" >
               <input type="submit" name="submit" value="<?=$_SUBMIT;?>" />
               </form>
		</div>
        <hr color="#000099" />
        <?php if ($itemID && $itemID!=$IKARE_ITEM) { ?>
		<div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="itemID" value="<?=$itemID; ?>" >
               <input type="hidden" name="page" value="logEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <?php } ?>
        <?php
		$q = "accountID='$userID' AND createdTime>='$fromDate 00:00:00' AND createdTime<='$toDate 23:59:59'";
		if ($multi_person) $q .= " AND (fieldP=0 OR fieldP='$def_person')";
		if ($itemID) $q .= " AND itemID='$itemID'";
		else if (count($items)) $q .= " AND itemID in (".implode(",",$items).") ";
		if ($categoryID) $q .= " AND categoryID='$categoryID'";
		$pcnt = $itemEntry->count($q);
		$page_num = intval($pcnt/$PAGE_SIZE) + (($pcnt %  $PAGE_SIZE)?1:0);
		if ($frompage<=0) $frompage=1;
		if ($frompage>$page_num) $frompage = $page_num;
		$offset = ($frompage-1)*$PAGE_SIZE;
		if ($offset<0) $offset = 0;
	?>
	<div align="left" class="even">
               <?=$itemName."-".$_RECORDS.": ".$pcnt; ?> <?php if ($pcnt) { ?>-- &nbsp;[&nbsp;
        <?php 
        for ($i=1;$i<=$page_num;$i++) {
        	if ($i==$frompage) echo $i."&nbsp;";
        	else echo "<a href='?frompage=$i&itemID=$itemID'>$i</a>&nbsp;";
    	} 
    	?>&nbsp;]&nbsp;<?php } ?>
	</div>
<table id="loglist" class="tablesorter" width="100%" border="0">
	<thead>
          <tr>
            <th width="140"><?=$_DATETIME;?></th>
            <th width="140"><?=$_ITEM;?></th>
            <th width="140"><?=$_CATEGORY;?></th>
            <th width="160" ><?=$_VALUE;?></th>
            <th><?=$_NOTES;?></th>
            <th width="100"><?=$_ACTION;?></th>
          </tr>
    </thead>
    <tbody>
	<?php
		$db = $itemEntry->sql($q,"createdTime DESC, itemID LIMIT $offset, $PAGE_SIZE");
		$i=0;
		while ($db->next_record()) {
			$i++;
			$ID = $db->f("ID");
			$psn = $db->f("fieldP");
			$rowValues = $itemEntry->rowValues($ID);
		?>
          <tr class="<?=($i%2==0?'even':'odd'); ?>">
            <td><?=$db->f('createdTime'); ?></td>
            <td><?=$item->get_field($db->f('itemID'),$item_fld); ?></td>
            <td><?=$category->get_field($db->f('categoryID'),$item_fld); ?></td>
            <td><?=$rowValues; ?></td>
            <td><?=$db->f('note').($psn?"P$psn":""); ?></td>
            <td>
               <form action="index.php" method="post" name="delete<?=$ID; ?>" >
               <input type="hidden" name="page" value="logList" >
               <input type="hidden" name="itemID" value="<?=$itemID; ?>" >
               <input type="hidden" name="frompage" value="<?=$frompage; ?>" >
               <input type="hidden" name="action" value="DELETE" >
               <input type="hidden" name="ID" value="<?=$ID; ?>" >
               <input type="submit" value="<?=$_DELETE;?>" onClick="return confirm('<?=$_DELETE_MSG;?>');" />
               </form>
            </td>
          </tr>
        <?php
		}
		if (!$rowValues) echo "<div align='center'><font color=red size=+2>$_NODATA_MSG</font></div>";
		?>
	</tbody>
</table>
        <?php if ($itemID && $itemID!=$IKARE_ITEM) { ?>
		<div align="right">
               <form action="index.php" method="post" name="add" >
               <input type="hidden" name="itemID" value="<?=$itemID; ?>" >
               <input type="hidden" name="page" value="logEdit" >
               <input type="submit" value="+<?=$_ADD;?>" />
               </form>
		</div>
        <?php } ?>
	<div align="left" class="even">
               <?=$itemName."-".$_RECORDS.": ".$pcnt; ?> <?php if ($pcnt) { ?>-- &nbsp;[&nbsp;
        <?php 
        for ($i=1;$i<=$page_num;$i++) {
        	if ($i==$frompage) echo $i."&nbsp;";
        	else echo "<a href='?frompage=$i&itemID=$itemID'>$i</a>&nbsp;";
    	} 
    	?>&nbsp;]&nbsp;<?php } ?>
	</div>
        	
	<script type="text/javascript">
	$(function() {		
		$("#loglist").tablesorter({headers: {4:{sorter: false}, 5:{sorter: false}}});
		$("#fromDate").datepicker(datePickerOpt);
		$("#fromDate").datepicker("setDate","<?=$fromDate;?>");
		$("#toDate").datepicker(datePickerOpt);
		$("#toDate").datepicker("setDate","<?=$toDate;?>");
	});	
	</script>

