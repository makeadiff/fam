<?php

$file_name = $_GET['file'];


https://makeadiff.in/apps/fellowship-signup/files/index.php?p=Delhi%2FCommon%2FTushar+goel&dl=Tushar_goel_common_Blank.docx
// make sure it's a file before doing anything!
$base_name = basename($file_name);


$directory = str_replace($base_name,'',str_replace('https://makeadiff.in/apps/fellowship-signup/tasks/','',$file_name));
$url='https://makeadiff.in/apps/fellowship-signup/files/index.php?p='.$directory.'&dl='.$base_name;

if(isset($file_name)) {

  header('location: '.$url); break;
  exit;

	switch(strtolower(substr(strrchr($file_name, '.'), 1))) {
		case 'pdf': $mime = 'application/pdf'; header('location: '.$file_name); break;
		case 'zip': $mime = 'application/zip'; header('location: '.$file_name); break;
    case 'doc':
		case 'docx': $mime = 'application/octet-stream'; break;
		case 'jpeg':
		case 'jpg': $mime = 'image/jpg'; header('location: '.$file_name); break;
		default: $mime = 'application/force-download';
	}
	header('Pragma: public'); 	// required
	// header('Expires: 0');		// no cache
	// header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	// header('Cache-Control: private',false);
	header('Content-Type: '.$mime);
	header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
	header('Content-Transfer-Encoding: binary');
	header('Connection: close');
	readfile($file_name);		// push it out
	exit();

}
