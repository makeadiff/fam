<?php
//Configuration file for iFrame
// Leave Empty - file extistance is needed for accurate directory matching.
$config = $config_data + array(
	'db_database'	=> 'makeadiff_madapp',
	'site_title'	=> 'FAM',
);
$config['site_home'] = $config_data['site_home'] . 'apps/fam/';

if($_SERVER['HTTP_HOST'] == 'localhost') {
	$config['mode'] = 'p';
}