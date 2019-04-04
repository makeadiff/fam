<?php
require 'common.php';

$action = i($QUERY, 'action');
$applicants = [];
$all_tasks = [];

$update = false;

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
	if(!empty($applicants)){
		$applicant = $applicants[0];
	}
}


if($action == 'Update Tasks') {

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
	if(!empty($applicants)){
		$applicant = $applicants[0];
	}

	if(i($QUERY, 'task_id')) $task_id = i($QUERY, 'task_id');

	if(isset($task_id) && $task_id){
		$update = $sql->update('FAM_UserTask',array(
			'common_task_url' => i($QUERY, 'common_task_url')
		),'id='.$task_id);
	}

}


if(!empty($applicants)){
	$id = $applicant['id'];
	$all_tasks = $fam->getTask($id,'all');

	if(!empty($all_tasks)){
		$all_tasks = $all_tasks[0];
	}
}




render();
