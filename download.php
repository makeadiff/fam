<?php

$file_name = $_GET['file'];

if(isset($file_name)) {

	if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}

	// get the file mime type using the file extension
	switch(strtolower(substr(strrchr($file_name, '.'), 1))) {
		case 'pdf': $mime = 'application/pdf'; break;
		case 'zip': $mime = 'application/zip'; break;
    case 'doc':
    case 'docx': $mime = 'application/octet-stream'; break;
		case 'jpeg':
		case 'jpg': $mime = 'image/jpg'; break;
		default: $mime = 'application/force-download';
	}
	header('Content-Type: '.$mime);
	readfile($file_name);
	exit();
}
