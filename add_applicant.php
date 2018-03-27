<?php
require 'common.php';

$action = i($QUERY, 'action');

if($action == 'Find Applicant') {
	$email = i($QUERY, 'email');
	$phone = i($QUERY, 'phone');

	$params = [];
	if($email) {
		$params['email'] = $email;
		$params['mad_email'] = $email;
	}
	if($phone) $params['phone'] = $phone;

	$applicants = $fam->findUser($params, ' OR ');
}

render();
