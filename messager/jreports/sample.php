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
	$rptName = "/reports/devMyHealth/HealthLogReport_BP";
	$outType = 'PDF';
	$rptParams = array('accountID' => '6', 'DateBegin' => '2012-09-01', 
		'DateEnd' => '2012-09-30');
	$saveFile = false;
/****************/

	$jasper = new Adl\Integration\RequestJasper();

	/*
	To send PDF to browser
	*/
	header('Content-type: application/pdf');
	echo $jasper->run($rptName,$outType,$rptParams,$saveFile);
	
	/*
	To Save content to a file in the disk
	The path where the file will be saved is registered into config/data.ini
	*/
//	$jasper->run($rptName,'PDF', $rptParams, true);

} catch (\Exception $e) {
	echo $e->getMessage();
	die;
}

?>
