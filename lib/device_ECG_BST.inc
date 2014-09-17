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
	var $CHKVAL=172;
	
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
	
	function draw($PDID, $fromTime="", $toTime="", $d="", $imgwidth="800", $imgheight="400", $page=1, $wsize=5) {
		$db = new ps_DB;
		if ($d['chk_count']) $limits = $d['chk_count']*300*60;
		else $limits = 300 * 60 * $wsize;
		if ($d['chk_val']) $chk_val=$d['chk_val'];
		else $chk_val = $this->CHKVAL;
		if ($d['fromTime']) $fTime = $d['fromTime'];
		else $fTime = "00:00:00";
		if ($d['toTime']) $tTime = $d['toTime'];
		else $tTime = "23:59:59";
		if ($d['chk_offset']) $offset = ($d['chk_offset']-1)*$limits;		
		else $offset = 0;
$data = " [";
$data_chk = array();
	      $where = "PDID=$PDID ";
		  
		  if ($fromTime) {
			  $where .= "AND measureTime>='".strtotime($fromTime." ".$fTime)."' ";
		  }
		  if ($toTime) {
			  $where .= "AND measureTime<='".strtotime($toTime." ".$tTime)."' ";
		  }
		  $q = "SELECT * FROM $this->tbl WHERE $where ORDER BY serial Limit $offset, $limits";
		  $db->query($q);
		  $c=0;
		  $cur_chk = 0;
		  $more = "";
		  while ($db->next_record()) {
			  $data .= $more.$db->f("ECG_BSTValue");
			  $more = ",";
			  $c++;
			  if ($db->f("ECG_BSTValue")>$chk_val && $c>$cur_chk) {
				  $data_chk[] = $c;
				  $cur_chk = $c+150;
			  }
		  }
		  $data .= "]\n";
$data2 = " [";
$c2 = 0;
		  $more2 = "";
		  $rr_inter = array();
		  for ($i=1;$i<count($data_chk);$i++) {
		  		$rr_inter[] = ($data_chk[$i]-$data_chk[$i-1])/300;
			  	$data2 .= $more2."[".number_format($data_chk[$i-1]/300,3).",".number_format(($data_chk[$i]-$data_chk[$i-1])/300,3)."]";
				$more2 = ",";
				$c2++;
		  }
		  $data2 .= "]\n";
		  if ($c>0) $beats = number_format($c2/(($c/300)/60),1);
		  $avg = number_format(sh_avg($rr_inter),4);
		  $sd = number_format(sh_sd($rr_inter,$avg),4);
	
$output_header .= "
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'></script>
		<script type='text/javascript' src='js/highcharts.js'></script>
		<script type='text/javascript'>
			var masterChart{$PDID},
				detailChart{$PDID},
				HBChart{$PDID};
				
			$(document).ready(function() {

   HBChart{$PDID} = new Highcharts.Chart({
      chart: {
         renderTo: 'HBcontainer{$PDID}',
							marginLeft: 50,
							marginRight: 20,
							reflow: false,
         defaultSeriesType: 'line'
      },
      title: {
         text: 'RR Interval (Heart Rate: $beats/min at value $chk_val,count: $c2)'
      },
      xAxis: {
      },
      yAxis: {
		title: {
			text: 'Seconds'
		},
		plotLines: [{
			color: '#FF0000',
			width: 1,
			label: 'Avg',
			value: {$avg}
			}],
      	min: 0
      },
      tooltip: {
         formatter: function() {
			var point = this.points[0];
            return ''+
               Highcharts.numberFormat(point.x,3) +': '+ Highcharts.numberFormat(point.y, 3);
         },
         crosshairs: [{color: 'green'},{color: 'green'}],
		 shared: true
       },
      credits: {
         enabled: false
      },
      plotOptions: {
         series: {
            //pointPadding: 0.2,
            //borderWidth: 0,
								marker: {
									enabled: false,
									states: {
										hover: {
											enabled: true,
											radius: 3
										}
									}
								}
         }
      },
	   series: [{
	   		name: 'RR Interval- Avg: $avg, SD: $sd',
			pointInterval: 1/300,
			type: 'area',
			data: {$data2}
		}]
   });

				// create the master chart
				function createMaster() {
					masterChart{$PDID} = new Highcharts.Chart({
						chart: {
							renderTo: 'master-container{$PDID}',
							reflow: false,
							borderWidth: 0,
							backgroundColor: null,
							marginLeft: 50,
							marginRight: 20,
							zoomType: 'x',
							events: {
								
								// listen to the selection event on the master chart to update the 
								// extremes of the detail chart
								selection: function(event) {
									var extremesObject = event.xAxis[0],
										min = extremesObject.min,
										max = extremesObject.max,
										detailData = [],
										xAxis = this.xAxis[0];
									
									// reverse engineer the last part of the data
									jQuery.each(this.series[0].data, function(i, point) {
										if (point.x > min && point.x < max) {
											detailData.push({
												x: point.x,
												y: point.y
											});
										}
									});
									// move the plot bands to reflect the new detail span
									xAxis.removePlotBand('mask-before');
									xAxis.addPlotBand({
										id: 'mask-before',
										from: max,
										to: min,
										color: 'rgba(0, 0, 0, 0.2)'
									});
									
									/*
									xAxis.removePlotBand('mask-after');
									xAxis.addPlotBand({
										id: 'mask-after',
										from: max,
										to: {$c},
										color: 'rgba(0, 0, 0, 0.2)'
									});
									*/
									detailChart{$PDID}.series[0].setData(detailData);
									
									return false;
								}
							}
						},
						title: {
							text: null
						},
						xAxis: {
							showLastTickLabel: true,
							maxZoom: 300 * 60 * 1, // 1 min
							//plotBands: [{
							//	id: 'mask-before',
							//	from: 0,
							//	to: 100,
							//	color: 'rgba(0, 0, 0, 0.2)'
							//}],
							title: {
								text: null
							}
						},
						yAxis: {
							gridLineWidth: 0,
							labels: {
								enabled: false
							},
							title: {
								text: null
							},
							min: 0.6,
							showFirstLabel: false
						},
						tooltip: {
							formatter: function() {
								return false;
							}
						},
						legend: {
							enabled: false
						},
						credits: {
							enabled: false
						},
						plotOptions: {
							series: {
								fillColor: {
									linearGradient: [0, 0, 0, 70],
									stops: [
										[0, '#4572A7'],
										[1, 'rgba(0,0,0,0)']
									]
								},
								lineWidth: 1,
								marker: {
									enabled: false
								},
								shadow: false,
								states: {
									hover: {
										lineWidth: 1						
									}
								},
								enableMouseTracking: false
							}
						},
					
						series: [{
							name: 'Value',
							pointInterval: 1/300,
							pointStart: 0,
							data: {$data}
						}],
						
						exporting: {
							enabled: false
						}
					
					}, function(masterChart{$PDID}) {
						createDetail(masterChart{$PDID})
					});
				}
				
				// create the detail chart
				function createDetail(masterChart{$PDID}) {
					
					// prepare the detail chart
					var detailData = [],
						detailStart;
						detailStart = {$c}/300-10;
						
					jQuery.each(masterChart{$PDID}.series[0].data, function(i, point) {
						if (point.x >= detailStart) {
							detailData.push(point.y);
						}
					});
					
					// create a detail chart referenced by a global variable
					detailChart{$PDID} = new Highcharts.Chart({
						chart: {
							marginBottom: 120,
							renderTo: 'detail-container{$PDID}',
							reflow: false,
							marginLeft: 50,
							marginRight: 20,
							style: {
								position: 'absolute'
							}
						},
						credits: {
							enabled: false
						},
						title: {
							text: 'Value'
						},
						subtitle: {
							text: 'Select an area by dragging across the lower chart'
						},
						xAxis: {
						},
						yAxis: {
							title: null,
							maxZoom: 0.1
						},
						tooltip: {
							formatter: function() {
								var point = this.points[0];
								return '<b>'+ Highcharts.numberFormat(point.y, 0) +'</b><br/> at '+
									Highcharts.numberFormat(point.x, 3);
							},
         					crosshairs: [{color: 'green'},{color: 'green'}],
							shared: true
						},
						legend: {
							enabled: false
						},
						plotOptions: {
							series: {
								marker: {
									enabled: false,
									states: {
										hover: {
											enabled: true,
											radius: 3
										}
									}
								}
							}
						},
						series: [{
							name: 'Value',
							pointStart: detailStart,
							pointInterval: 1/300,
							data: detailData
						}],
						
						exporting: {
							enabled: false
						}
					
					});
				}
					
				// make the container smaller and add a second container for the master chart
				var \$container{$PDID} = $('#container{$PDID}')
					.css('position', 'relative');
				
				var \$detailContainer = $('<div id=\"detail-container{$PDID}\">')
					.appendTo(\$container{$PDID});
				
				var \$masterContainer = $('<div id=\"master-container{$PDID}\">')
					.css({ position: 'absolute', top: 300, height: 80, width: '100%' })
					.appendTo(\$container{$PDID});
					
				// create master and in its callback, create the detail chart
				createMaster();
				
			});
		</script>
";
$output = "
		<div id='container{$PDID}' style='width: {$imgwidth}px; height: {$imgheight}px; margin: 0 auto'></div>
		<div id='HBcontainer{$PDID}' style='width: {$imgwidth}px; height: {$imgheight}px; margin: 0 auto'></div>
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