<?php
$colors = array("FF1F1F","009246","EDBF00","0055A5","AE3C51","6298FF","070707","D97529","89000F","356C20");
$val_colors = array("FF9F9F","90F2D6","FDFF90","90E5F5","FECCE1","E2B8BF","979797","F9F5B9","F9909F","C5FCB0");

$item = load_class("Item");
$itemArticle = load_class("ItemArticle");
$itemEntry = load_class("ItemEntry");
$category = load_class("Category");
$ID = $_REQUEST['ID'];
$d['accountID']=$userID;
$d['itemID'] = $itemID = $_REQUEST['itemID'];
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
$itemName = $item->get_field($itemID,$item_fld);
$article_arr = $itemArticle->get_articles($itemID);
$article_HiLow_arr = $itemArticle->get_article_HiLow($itemID);

$q = "accountID='$userID' AND createdTime>='$fromDate 00:00:00' AND createdTime<='$toDate 23:59:59'";
if ($multi_person) $q .= " AND (fieldP=0 OR fieldP='$def_person')";
if ($itemID) $q .= " AND itemID='$itemID'";
if ($categoryID) $q .= " AND categoryID='$categoryID'";
$db = $itemEntry->sql($q,"createdTime");
$data_str = array();
$Hi_str = array();
$Low_str = array();
$isVisible = array();
$first_date = "";
$last_date = "";
while ($db->next_record()) {
	$ddate = date("Y",strtotime($db->f("createdTime"))).",".(date("m",strtotime($db->f("createdTime")))-1).",".date("d",strtotime($db->f("createdTime")));
	$ddate .= ",".date("H",strtotime($db->f("createdTime"))).",".date("i",strtotime($db->f("createdTime"))).",".date("s",strtotime($db->f("createdTime")));
	if (!$first_date) $first_date = $ddate;
	$last_date = $ddate;
        $psn = $db->f("fieldP");
        $psn = ($psn?"(P$psn)":"");
	$vals_arr = $itemEntry->vals_arr($db->f("ID"));
	foreach ($vals_arr as $k => $v) {
		$data_str[$k] .= "[Date.UTC($ddate), $v],";
	}
	/*
	foreach ($article_HiLow_arr as $k => $val_arr) {
		if ($val_arr['value1']!='0.00') {
			$Low_str[$k] .= "[Date.UTC($ddate), ".$val_arr['value1']."],";
		}
		if ($val_arr['value2']!='0.00') {
			$Hi_str[$k] .= "[Date.UTC($ddate), ".$val_arr['value2']."],";
		}
	}
	*/
}
	foreach ($article_HiLow_arr as $k => $val_arr) {
		if ($val_arr['value1']!='0.00') {
			$Low_str[$k] .= "[Date.UTC($first_date), ".$val_arr['value1']."],";
			$Low_str[$k] .= "[Date.UTC($last_date), ".$val_arr['value1']."],";
		}
		if ($val_arr['value2']!='0.00') {
			$Hi_str[$k] .= "[Date.UTC($first_date), ".$val_arr['value2']."],";
			$Hi_str[$k] .= "[Date.UTC($last_date), ".$val_arr['value2']."],";
		}
		$isVisible[$k] = $val_arr['display_default'];
	}

?>
<script type='text/javascript' src='js/highcharts.js'></script>
<script type='text/javascript' src='js/highstock.js'></script>
<script type='text/javascript'>
	var chart;
	var chart1;
	var options = {
			chart: {
				renderTo: 'container',
				zoomType: 'x'
			},
			credits: {
				enabled: false
			},
			title: {
				text: ''
			},
			subtitle: {
				text: '<?=$fromDate; ?> ~ <?=$toDate; ?>'
			},
			xAxis: {
				type: 'datetime',
				labels: {
                	formatter: function() {
                               return  Highcharts.dateFormat('%m/%d_%H', this.value);
                	}
                }
			},
			yAxis: [ { //Primary yAxis
				labels: {
					formatter: function() {
						return this.value;
					},
					style: {
						color: '#89A5E4'
					}
				},
				title: {
					text: '<?=$_MEASUREMENT;?>',
					style: {
						color: '#89A5E4'
					}
				}
			}],
			
			tooltip: {
				crosshairs: [ {color: '#FF8888'}, {color: '#FF8888'} ],
				formatter: function() {
					return ''+Highcharts.dateFormat('%Y-%m-%d %H:%M:%S',this.x)+'<br>'+
						this.series.name+': '+this.y;
				}
			},
			
			series: [
			<?php
			$c = 0;
			$vc = 0;
			foreach ($article_arr as $k=> $v) {
		    ?>
			{
				name: '<?=$v; ?>',
				color: '#<?=$colors[$c%10]; ?>',
				type: 'spline',
				visible: <?=$isVisible[$k]; ?>,
				zIndex: <?=(99-$c); ?>,
				data: [<?=$data_str[$k]; ?>]
			},
			<?php if ($Hi_str[$k]!="") { ?>
			{
				name: '<?=$v; ?>_<?=$_HIGH;?>',
				color: '#<?=$val_colors[$vc%10]; ?>',
				type: 'line',
				visible: false,
				lineWidth: 1,
				data: [<?=$Hi_str[$k]; ?>]
			},
			<?php $vc++;} ?>
			<?php if ($Low_str[$k]!="") { ?>
			{
				name: '<?=$v; ?>_<?=$_LOW;?>',
				color: '#<?=$val_colors[$vc%10]; ?>',
				type: 'line',
				visible: false,
				lineWidth: 1,
				data: [<?=$Low_str[$k]; ?>]
			},
			<?php $vc++;} ?>
			<?php $c++; } ?>
			]
		};
		
	$(document).ready(function() {
		options.title.text = "<?=$itemName; ?>";
		chart = new Highcharts.Chart(options);		
	});
        $(function() {
                $("#fromDate").datepicker();
                $("#fromDate").datepicker("option","dateFormat","yy-mm-dd");
                $("#fromDate").datepicker("setDate","<?=$fromDate;?>");
                $("#toDate").datepicker();
                $("#toDate").datepicker("option","dateFormat","yy-mm-dd");
                $("#toDate").datepicker("setDate","<?=$toDate;?>");
        });

</script>
<!--
Log Chart <?php if ($itemName) echo "of $itemName"; ?>
        <hr color="#000099" />
// -->
        <div align="right">
               <form action="index.php" method="post" name="search" >
               <?=$_LIST_DATE;?>: <input name="fromDate" id="fromDate" type="text" size="12" value="<?=$fromDate; ?>" />
               ~ <input name="toDate" id="toDate" type="text" size="12" value="<?=$toDate; ?>" />
               <?=$_CATEGORY;?>:<?=$cate_select; ?>
               <input type="hidden" name="page" value="logChart" >
               <input type="hidden" name="itemID" value="<?=$itemID; ?>" >
               <input type="submit" name="submit" value="<?=$_SUBMIT;?>" />
               </form>
		</div>
        <hr color="#000099" />
		<div id="container" align="center" style='width:95%; height:360px;'>
		</div>
