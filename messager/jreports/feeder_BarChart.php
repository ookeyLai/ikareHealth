<?php 
/*
THIS FILE IS FOR EXAMPLE PURPOSES ONLY
*/
include 'Adl/Configuration.php';
include 'Adl/Config/Parser.php';
include 'Adl/Config/JasperServer.php';
include 'Adl/Integration/RequestJasper.php';

try {
/*  Initial variables */
	$rptName = "/reports/test1/C7T5_Feeder_BarChart";
	$outType = 'PDF';
	$rptParams = array('accountID' => '6', 'DateBegin' => '2012-09-01',
		'DateEnd' => '2012-09-30');
	$saveFile = false;
/****************/
	$jasper = new Adl\Integration\RequestJasper();

	header('Content-type: application/pdf');
	echo $jasper->run($rptName,$outType,$rptParams,$saveFile);
	
} catch (\Exception $e) {
	echo $e->getMessage();
	die;
}

?>
