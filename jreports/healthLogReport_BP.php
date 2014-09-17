<?php 
/*
THIS FILE IS FOR EXAMPLE PURPOSES ONLY
*/

include 'Adl/Configuration.php';
include 'Adl/Config/Parser.php';
include 'Adl/Config/JasperServer.php';
include 'Adl/Integration/RequestJasper.php';

/*  Initial variables */
	$rptName = "/reports/devMyHealth/HealthLogReport_BP";

/*  Print report */
try {
	$jasper = new Adl\Integration\RequestJasper();

	/*
	To send PDF to browser
	*/
	header('Content-type: application/pdf');
	echo $jasper->run($rptName);
	
	/*
	 To send HTML to browser
	*/
//	echo $jasper->run($rptName,'html',$page,$controls);

	/*
	To Save content to a file in the disk
	The path where the file will be saved is registered into config/data.ini
	*/
//	$jasper->run($rptName,'PDF', null, true);

} catch (\Exception $e) {
	echo $e->getMessage();
	die;
}

?>
