<?php
if (!defined("DEVICE_BG")) {

class Device_BG {
	var $deviceCode = "BG";
	var $tbl = "BGData";
	var $db_schema = array("measureTime","measureType","BGValue");
	var $db_dup_check = array(array("measureTime",0),array("measureType",1)); // reference to $db_schema
	var $import_schema = array("line","year","month","day","AMPM","hour","min","value","extra");
	var $export_schema;
	
	var $analysis_modules = array("interday","intraday");
	
	var $delimiter = ",";
	var $percision = 2;
	
	function export($dest) {
	}
	
	function import($d, $lines) {
		$PDID = $d['PDID1'];
		$paraCount = count($this->import_schema);
		for ($i=0;$i<count($lines);$i++) {
			$f = explode($this->delimiter,$lines[$i],$paraCount);
			$vars[0] = strtotime($f[1]."/".$f[2]."/".$f[3]." ".(($f[4]=="PM"?12:0)+$f[5]).":".$f[6]);
			$vars[1] = "A";
			$vars[2] = is_numeric($f[7])?$f[7]:0;
			$this->add($PDID,$vars);
		}
	}
	
	function draw($PDID, $fromTime="", $toTime="", $imgwidth="800", $imgheight="180", $imgtype="LineChart") {
		$db = new ps_DB;
$output = "
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
		<script type='text/javascript'>
		  google.load('visualization', '1', {'packages':['corechart']});
		  google.setOnLoadCallback(drawChart$PDID);
		  function drawChart$PDID() {
			  var data = new google.visualization.DataTable();
			  // Create our data table.
			  data.addColumn('string', '日期');
			  data.addColumn('number', '血糖濃度');
			  data.addColumn('number', '平均值');
			  //data.addColumn('number', '標準差+');
			  //data.addColumn('number', '標準差-');
";
	      $where = "PDID=$PDID ";
		  
		  if ($fromTime) {
			  $where .= "AND measureTime>='".strtotime($fromTime." 00:00:00")."' ";
		  }
		  if ($toTime) {
			  $where .= "AND measureTime<='".strtotime($toTime." 23:59:59")."' ";
		  }
		  $q = "SELECT * FROM BGData WHERE $where ORDER BY measureTime";
		  $db->query($q);
		  $arrTime = array();
		  $arr = array();
		  while ($db->next_record()) {
			  $arrTime[] = $db->f("measureTime");
			  $arr[] = $db->f("BGValue");
			  $c++;
		  }
		  $avg = round(sh_avg($arr),$this->percision);
		  $sd = round(sh_sd($arr,$avg),$this->percision);
		  $avgUp = $avg+$sd;
		  $avgDn = $avg-$sd;
		  
		  for ($i=0;$i<count($arr);$i++) {
			  // $output .= "			  data.addRows([['".DATE("m/d",$db->f("measureTime"))."',".$db->f("BGValue").",$avg,$avgUp,$avgDn]]);\n";
			  $output .= "			  data.addRows([['".DATE("m/d",$arrTime[$i])."',".$arr[$i].",$avg]]);\n";
		  }
	
$output .= "
			var chart = new google.visualization.".$imgtype."(document.getElementById('chart_div$PDID'));
			chart.draw(data, {width: $imgwidth, height: $imgheight});
		}
		</script>
		<div id='chart_div$PDID'></div>
		<div align='center'>平均值: $avg, 標準差: $sd (PDID:$PDID)</div>
";
		// echo $output;
		if (!$c) $output="<hr><div align='center'>No Data Found<br></div>";
		return $output;
	}
	
	function draw_interday($PDID, $fromTime="", $toTime="", $days="30", $imgwidth="800", $imgheight="180", $imgtype="LineChart") {
		$module_names = array("interday_sd","interday_cv");
		$db = new ps_DB;
$output_sd = "
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
		<script type='text/javascript'>
		  google.load('visualization', '1', {'packages':['corechart']});
		  google.setOnLoadCallback(draw".$module_names[0]."Chart$PDID);
		  function draw".$module_names[0]."Chart$PDID() {
			  var ".$module_names[0]."data = new google.visualization.DataTable();
			  // Create our data table.
			  ".$module_names[0]."data.addColumn('string', '日期');
			  ".$module_names[0]."data.addColumn('number', 'SD 值');
";
$output_cv = "
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
		<script type='text/javascript'>
		  google.load('visualization', '1', {'packages':['corechart']});
		  google.setOnLoadCallback(draw".$module_names[1]."Chart$PDID);
		  function draw".$module_names[1]."Chart$PDID() {
			  var ".$module_names[1]."data = new google.visualization.DataTable();
			  // Create our data table.
			  ".$module_names[1]."data.addColumn('string', '日期');
			  ".$module_names[1]."data.addColumn('number', 'CV 值');
";
	      $where = "PDID=$PDID ";
		  
		  if ($fromTime) {
			  $where .= "AND measureTime>='".strtotime($fromTime." 00:00:00")."' ";
		  }
		  if ($toTime) {
			  $toTime = date("Y-m-d",strtotime($toTime." 00:00:00")+$days*24*60*60);
			  $where .= "AND measureTime<='".strtotime($toTime." 23:59:59")."' ";
		  }
		  $q = "SELECT * FROM BGData WHERE $where ORDER BY measureTime";
		  $db->query($q);
		  $arrTime = array();
		  $arr = array();
		  while ($db->next_record()) {
			  $arrTime[] = $db->f("measureTime");
			  $arr[] = $db->f("BGValue");
		  }
		  $c=count($arr);
		  if ($c>$days) {
			  for ($i=0;$i<($c-$days);$i++) {
				  $avg[$i] = round(sh_avg(array_slice($arr,$i,$days)),$this->percision);
				  $sd[$i] = round(sh_sd(array_slice($arr,$i,$days),$avg[$i]),$this->percision);
				  $cv[$i] = round($sd[$i]/$avg[$i],$this->percision);
				  $output_sd .= "			  ".$module_names[0]."data.addRows([['".DATE("m/d",$arrTime[$i])."',".$sd[$i]."]]);\n";
				  $output_cv .= "			  ".$module_names[1]."data.addRows([['".DATE("m/d",$arrTime[$i])."',".$cv[$i]."]]);\n";
			  }
		  }
		  
	
$output_sd .= "
			var chart = new google.visualization.".$imgtype."(document.getElementById('".$module_names[0]."chart_div$PDID'));
			chart.draw(".$module_names[0]."data, {width: $imgwidth, height: $imgheight});
		}
		</script>
		<div id='".$module_names[0]."chart_div$PDID'></div>
";
$output_cv .= "
			var chart = new google.visualization.".$imgtype."(document.getElementById('".$module_names[1]."chart_div$PDID'));
			chart.draw(".$module_names[1]."data, {width: $imgwidth, height: $imgheight});
		}
		</script>
		<div id='".$module_names[1]."chart_div$PDID'></div>
";
$output .= $output_sd . $output_cv;
		if (!$c || ($c<=$days)) $output="<hr><div align='center'>No Data Found<br></div>";
		return $output;
	}

	
	function draw_intraday($PDID, $fromTime="", $toTime="", $imgwidth="800", $imgheight="180", $imgtype="LineChart") {
		$module_names = array("intraday_sd","intraday_cv");
		$db = new ps_DB;
$output_sd = "
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
		<script type='text/javascript'>
		  google.load('visualization', '1', {'packages':['corechart']});
		  google.setOnLoadCallback(draw".$module_names[0]."Chart$PDID);
		  function draw".$module_names[0]."Chart$PDID() {
			  var ".$module_names[0]."data = new google.visualization.DataTable();
			  // Create our data table.
			  ".$module_names[0]."data.addColumn('string', '日期');
			  ".$module_names[0]."data.addColumn('number', 'SD 值');
";
$output_cv = "
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
		<script type='text/javascript'>
		  google.load('visualization', '1', {'packages':['corechart']});
		  google.setOnLoadCallback(draw".$module_names[1]."Chart$PDID);
		  function draw".$module_names[1]."Chart$PDID() {
			  var ".$module_names[1]."data = new google.visualization.DataTable();
			  // Create our data table.
			  ".$module_names[1]."data.addColumn('string', '日期');
			  ".$module_names[1]."data.addColumn('number', 'CV 值');
";
	      $where = "PDID=$PDID ";
		  
		  if ($fromTime) {
			  $where .= "AND measureTime>='".strtotime($fromTime." 00:00:00")."' ";
		  }
		  if ($toTime) {
			  $where .= "AND measureTime<='".strtotime($toTime." 23:59:59")."' ";
		  }
		  $q = "SELECT * FROM BGData WHERE $where ORDER BY measureTime";
		  $db->query($q);
		  $arrTime = array();
		  $arr = array();
		  $c=-1;
		  $lastTime = "";
		  while ($db->next_record()) {
			  if (date("Ymd",$db->f("measureTime"))!=$lastTime) {
				  $arrTime[++$c] = $db->f("measureTime");
				  $arr[$c] = array();
				  $lastTime = date("Ymd",$db->f("measureTime"));
			  }
			  $arr[$c][] = $db->f("BGValue");
		  }
		  $c=count($arr);
		  
			  for ($i=0;$i<$c;$i++) {
				  $avg[$i] = round(sh_avg($arr[$i]),$this->percision);
				  $sd[$i] = round(sh_sd($arr[$i],$avg[$i]),$this->percision);
				  $cv[$i] = ($avg[$i]==0)?0:round($sd[$i]/$avg[$i],$this->percision);
				  $output_sd .= "			  ".$module_names[0]."data.addRows([['".DATE("m/d",$arrTime[$i])."',".$sd[$i]."]]);\n";
				  $output_cv .= "			  ".$module_names[1]."data.addRows([['".DATE("m/d",$arrTime[$i])."',".$cv[$i]."]]);\n";
			  }
		  
	
$output_sd .= "
			var chart = new google.visualization.".$imgtype."(document.getElementById('".$module_names[0]."chart_div$PDID'));
			chart.draw(".$module_names[0]."data, {width: $imgwidth, height: $imgheight});
		}
		</script>
		<div id='".$module_names[0]."chart_div$PDID'></div>
";
$output_cv .= "
			var chart = new google.visualization.".$imgtype."(document.getElementById('".$module_names[1]."chart_div$PDID'));
			chart.draw(".$module_names[1]."data, {width: $imgwidth, height: $imgheight});
		}
		</script>
		<div id='".$module_names[1]."chart_div$PDID'></div>
";
$output .= $output_sd . $output_cv;
		if (!$c || ($c<=$days)) $output="<hr><div align='center'>No Data Found<br></div>";
		return $output;
	}
	
	function add($PDID, &$d) {
		if (count($d)!=count($this->db_schema)) {
			return false;
		}
		$db = new ps_DB;
		$q = "SELECT COUNT(*) as rows FROM $this->tbl WHERE PDID='$PDID' ";
		for ($c=0; $c<count($this->db_dup_check);$c++) {
			$q .= "AND ".$this->db_dup_check[$c][0]."='".$d[$this->db_dup_check[$c][1]]."' ";
		}
		$db->query($q);
		$db->next_record();
		if ($db->f('rows')==0) {
			$q = "INSERT INTO $this->tbl ( PDID";
			for ($c=0;$c<count($this->db_schema);$c++) {
				$q .= ",".$this->db_schema[$c];
			}
			$q .= ") VALUES ('".$PDID."'";
			for ($c=0;$c<count($d);$c++) {
				$q .= ",'".$d[$c]."'";
			}
			$q .= ") ";
			$db->query($q);
		}
	}
	
}

define("DEVICE_BG",true);

} //END defined DEVICE_BG

?>