<?php
$aid = $_POST ['accountID'];
$apw = $_POST ['accountPW'];
// directory to upload
$dirname = "uploads";
// save the uploaded file
if ($_FILES) {
	// mkdir($dirname, 0777, true);
	$file = $dirname . "/" . md5 ( date ( 'Ymdgisu' ) ) . ".txt";
	if (@move_uploaded_file ( $_FILES ["iKare_file"] ["tmp_name"], $file )) {
		echo "Upload success ! " . "\n" . $aid . "/" . $apw;
	} else
		echo "Upload failed !!, please try again.";
}
?>
