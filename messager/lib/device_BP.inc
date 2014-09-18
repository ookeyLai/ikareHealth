<?php
if (!defined("DEVICE_BP")) {

class Device_BP {
	var $deviceCode = "BP";
	var $tbl = "BPData";
	var $db_schema = array("measureTime","BPValue1","BPValue2","BPValue3");
	var $db_dup_check = array(array("measureTime",0)); // reference to $db_schema
	var $import_schema = array("date","time","AMPM","values");
	var $export_schema;
	
	var $fragDelimiter = "Zone";
	var $delimiter1 = " ";
	var $delimiter2 = "/";
	var $percision = 2;
	
	function export($dest) {
	}
	
	function import($d, $lines) {
		$PDIDs = array($d['PDID1'],$d['PDID2'],$d['PDID3']);
		$fID = 0;
		$paraCount = count($this->import_schema);
		for ($i=0;$i<count($lines);$i++) {
			$lines[$i] = trim($lines[$i]);
			if (empty($lines[$i])) continue;
			if (substr($lines[$i],0,4)==$this->fragDelimiter) {
				$fID++;
				continue;
			}
			$f = explode($this->delimiter1,$lines[$i],$paraCount);
			$t = explode(":",$f[1]);
			$t[0] = $t[0]+($f[2]=="PM"?12:0);
			$vals = explode($this->delimiter2,$f[3],3);
			$vars[0] = strtotime($f[0]." ".$t[0].":".$t[1].":00");
			$vars[1] = is_numeric($vals[0])?$vals[0]:0;
			$vars[2] = is_numeric($vals[1])?$vals[1]:0;
			$vars[3] = is_numeric($vals[2])?$vals[2]:0;
			if ($PDIDs[$fID-1])
				$this->add($PDIDs[$fID-1],$vars);
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
			  data.addColumn('number', '收縮壓');
			  data.addColumn('number', '舒張壓');
			  data.addColumn('number', '心跳');
";
	      $where = "PDID=$PDID ";
		  
		  if ($fromTime) {
			  $where .= "AND measureTime>='".strtotime($fromTime." 00:00:00")."' ";
		  }
		  if ($toTime) {
			  $where .= "AND measureTime<='".strtotime($toTime." 23:59:59")."' ";
		  }
		  $q = "SELECT * FROM $this->tbl WHERE $where ORDER BY measureTime";
		  $db->query($q);
		  $c=0;
		  while ($db->next_record()) {
			  $output .= "			  data.addRows([['".DATE("m/d",$db->f("measureTime"))."',".$db->f("BPValue1").",".$db->f("BPValue2").",".$db->f("BPValue3")."]]);\n";
			  $c++;
		  }
	
$output .= "
			var chart = new google.visualization.".$imgtype."(document.getElementById('chart_div$PDID'));
			chart.draw(data, {width: $imgwidth, height: $imgheight});
		}
		</script>
		<div id='chart_div$PDID'>No Data</div>
";
		// echo $output;
		if (!$c) $output="<hr><div align='center'>No Data Found<br></div>";
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

define("DEVICE_BP",true);

} //END defined DEVICE_BP

?>