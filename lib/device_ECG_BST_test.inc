<?php
if (!defined("DEVICE_ECG_BST")) {

class Device_ECG_BST {
	var $deviceCode = "ECG_BST";
	var $tbl = "ECG_BSTData";
	var $db_schema = array("measureTime","serial","ECG_BSTValue");
	var $db_dup_check = array(array("measureTime",0),array("serial",1)); // reference to $db_schema
	var $import_schema = array("value");
	var $export_schema;
	
	var $delimiter = " ";
	var $percision = 2;
	
	function export($dest) {
	}
	
	function import($d, $lines) {
		$PDIDs = array($d['PDID1'],$d['PDID2'],$d['PDID3']);
		$file_name = $d['file_name'];
		preg_match("/^([^_]+)_([^.]+)\.vts$/",$file_name,$matches);
		$ts = $matches[2];
		$tms = strtotime(substr($ts,0,4)."/".substr($ts,4,2)."/".substr($ts,6,2)." ".substr($ts,8,2).":".substr($ts,10,2).":".substr($ts,12,2));
		$data = substr(implode($lines),100);
		$dlen = strlen($data);
		$db = new ps_DB;
		for ($i=0;$i<$dlen;$i++) {
			$val = unpack("C",$data[$i]);
			$vars[0] = $tms;
			$vars[1] = $i;
			$vars[2] = intval($val[1]?$val[1]:0);
			if ($PDIDs[0])
				$this->add($db,$PDIDs[0],$vars);
		}
	}
	
	function draw($PDID, $fromTime="", $toTime="", $imgwidth="800", $imgheight="180", $imgtype="LineChart") {
		$db = new ps_DB;
		$limits = 300*60*0.1;
$data = " [";
	      $where = "PDID=$PDID ";
		  
		  if ($fromTime) {
			  $where .= "AND measureTime>='".strtotime($fromTime." 00:00:00")."' ";
		  }
		  if ($toTime) {
			  $where .= "AND measureTime<='".strtotime($toTime." 23:59:59")."' ";
		  }
		  $q = "SELECT * FROM $this->tbl WHERE $where ORDER BY serial Limit 0, $limits";
		  $db->query($q);
		  $c=0;
		  $more = "";
		  while ($db->next_record()) {
			  $data .= $more.$db->f("ECG_BSTValue");
			  $more = ",";
			  $c++;
		  }
		  $data .= "]\n";
	
$output_header .= "
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'></script>
		<script type='text/javascript' src='js/highcharts.js'></script>
		<script type='text/javascript'>
			$(document).ready(function() {
				
					var chart{$PDID} = new Highcharts.Chart({
						chart: {
							renderTo: 'container{$PDID}'
						},
						xAxis: {
						},
						series: [{
							data: {$data}
						}]
					});
			});
		</script>
";
$output = "
		<div id='container{$PDID}' style='width: 800px; height: 400px; margin: 0 auto'></div>
";
		return array($output_header, $output);
	}
	
	function add($db, $PDID, &$d) {
		if (count($d)!=count($this->db_schema)) {
			return false;
		}
		//$q = "SELECT COUNT(*) as rows FROM $this->tbl WHERE PDID='$PDID' ";
		//for ($c=0; $c<count($this->db_dup_check);$c++) {
		//	$q .= "AND ".$this->db_dup_check[$c][0]."='".$d[$this->db_dup_check[$c][1]]."' ";
		//}
		//$db->query($q);
		//$db->next_record();
		//if ($db->f('rows')==0) {
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
		//}
	}
		
	
}

define("DEVICE_ECG_BST",true);

} //END defined DEVICE_ECG_BST

?>