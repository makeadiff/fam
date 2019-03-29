<?php
require 'common.php';

$action = i($QUERY, 'action');
$applicants = [];

$verticals[0] = 'None';
$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Not Moving';
$city_id = i($QUERY, 'city_id', 0);
$group_id = i($QUERY, 'group_id', 0);

if($action == 'Find Applicant') {
	$params = [];

	$email = i($QUERY, 'email');
	if($email) {
		$params['email'] = $email;
		$params['mad_email'] = $email;
	}
	if(i($QUERY, 'phone')) $params['phone'] = i($QUERY, 'phone');
	if(i($QUERY, 'name')) $params['name'] = i($QUERY, 'name');
	if(i($QUERY, 'id')) $params['id'] = i($QUERY, 'id');
	if(i($QUERY, 'city_id')) $params['city_id'] = i($QUERY, 'city_id');

	$applicants = $fam->findUser($params, ' OR ', true);
}

render();
