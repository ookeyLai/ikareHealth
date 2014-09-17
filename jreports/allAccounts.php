<?php 
/*
THIS FILE IS FOR EXAMPLE PURPOSES ONLY
*/

include 'Adl/Configuration.php';
include 'Adl/Config/Parser.php';
include 'Adl/Config/JasperServer.php';
include 'Adl/Integration/RequestJasper.php';

/*  Initial variables */
        $rptName = "/reports/samples/AllAccounts";

/*  Print report */
try {
	$jasper = new Adl\Integration\RequestJasper();
	/*
	To send output to browser
	*/
	header('Content-type: application/pdf');
	echo $jasper->run($rptName);


} catch (\Exception $e) {
	echo $e->getMessage();
	die;
}

?>
