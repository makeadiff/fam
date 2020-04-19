<?php
require 'common.php';

$file_name = $_GET['file'];

if(isset($file_name)) {

  $base_name = basename($file_name);

  if($_SERVER['HTTP_HOST'] == 'makeadiff.in'){
    $parent = 'https://makeadiff.in/apps/continuation_signup/files/index.php?p=';
    $replace = 'http://makeadiff.in/apps/continuation_signup/tasks/';
  }
  else{
    $parent = 'http://localhost/makeadiff/apps/continuation_signup/files/index.php?p=';
    $replace = 'http://localhost/makeadiff/apps/continuation_signup/tasks/';
  }

  $directory = str_replace($base_name,'',str_replace($replace,'',$file_name));
  $url=$parent.$directory.'&dl='.$base_name;
  header('location: '.$url);
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
