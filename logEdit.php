<?php
$account = load_class("Account");
$item = load_class("Item");
$itemArticle = load_class("ItemArticle");
$itemEntry = load_class("ItemEntry");
$category = load_class("Category");

$ID = $_REQUEST['ID'];
$action = $_REQUEST['action'];
$d = $_REQUEST;

$d['accountID']=$userID;
$d['itemID'] = $itemID = $_REQUEST['itemID'];
$d['articleMode'] = $item->get_field($itemID,'articleMode');
$d['categoryID'] = $categoryID = $_REQUEST['categoryID'];

$item_fld = "name_".$lang;
$cate_select = $category->log_select('categoryID',$categoryID,$d);
$itemName = $item->get_field($itemID,$item_fld);
$article_inputs = $itemArticle->article_inputs($d,$d['articles'],$d['articleID']);
?>

<?=$_HEALTH_LOG;?> <?php if ($itemName) echo ": $itemName"; ?>
<form id="form1" name="form1" method="post" action="index.php">
<input type="hidden" name="page" value="logEdit" />
<?php

if ($action=='ADD') {
	//print_r($d);
	$itemEntry->add($d);
	backToPage("logList&itemID=$itemID");
} elseif ($action=='EDIT'){
	$itemEntry->update($d);
	if (!$d['error']) echo "<font color='#0000FF'>$_DATA_UPDATED</font><hr>";
}
if ($d['error']) echo "<font color='#FF0000'>".$d['error']."</font><hr>";

if ($ID) {
	$db = $itemEntry->sql("ID = '$ID'");
	$db->next_record();
	$c_datetime = $db->f("createdTime");
	$c_date = date("Y-m-d",strtotime($c_datetime));
	$c_time = date("H:i",strtotime($c_datetime));
	$action = $_EDIT;
	echo "<input type='hidden' name='action' value='EDIT'>";
	echo "<input type='hidden' name='ID' value='$ID'>";
	echo "<input type='hidden' name='itemID' value='$itemID'>";
} else {
	$_name = $d['name'];
	$c_datetime = date("Y-m-d H:i:s",time());
	$c_date = date("Y-m-d",strtotime($c_datetime));
	$c_time = date("H:i",strtotime($c_datetime));
	$action = $_ADD;
	echo "<input type='hidden' name='action' value='ADD'>";
	echo "<input type='hidden' name='itemID' value='$itemID'>";
}
?>
  <div align="right"></div>
<table width="90%" border="0">
  <tr>
    <th height="30" colspan="2" bgcolor="#333333" scope="row"><font color="#FFFFFF"><?=$_LOG_ENTRY;?></font> </th>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_DATETIME;?></th>
    <td bgcolor="#FFFFFF"><input name="c_date" id="c_date" type="text" size="10" maxlength="10" value="<?=$c_date; ?>" />
    	<input name="c_time" id="c_time" type="text" size="10" maxlength="10" value="<?=$c_time; ?>" /></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_ITEM;?></th>
    <td bgcolor="#FFFFFF"><?=$itemName; ?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_CATEGORY;?></th>
    <td bgcolor="#FFFFFF"><?=$cate_select; ?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_ARTICLE;?></th>
    <td bgcolor="#FFFFFF"><?=$article_inputs; ?></td>
  </tr>
  <tr>
    <th bgcolor="#999999" scope="row"><?=$_NOTES;?></th>
    <td bgcolor="#FFFFFF"><input name="note" type="text" id="note" size="60" value="<?=$note; ?>" /></td>
  </tr>
  <tr>
    <th colspan="2" bgcolor="#333333" scope="row"><input type="submit" name="button" id="button" value="<?=$action; ?>" /></th>
  </tr>
</table>
</form>

        <script type="text/javascript">
        $(function() {
                $("#c_date").datepicker(datePickerOpt);
                $("#c_date").datepicker("setDate","<?=$c_date=='0000-00-00'?'':$c_date;?>");
                $("#c_time").timepicker();
                $("#c_time").timepicker("option","timeFormat","H:i");
                $("#c_time").timepicker("setTime","<?=$c_time=='00:00'?'':$c_time;?>");
        });
        </script>

        <div align="right"></div>
