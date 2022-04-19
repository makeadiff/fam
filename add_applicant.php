<?php
require 'common.php';

$action = i($QUERY, 'action');
$applicants = [];

$verticals[0] = 'None';
$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Not Moving';

$all_cities_user = keyFormat($common->getCities(), ['id', 'name']);
$all_cities_user[0] = 'Any City';


$city_id = i($QUERY, 'city_id', 0);
$group_id = i($QUERY, 'group_id', 0);
$stage_id = i($QUERY, 'stage_id', 0);


if($action == 'Search') {
	$params = [];

	if(i($QUERY, 'email')) $params['email'] = i($QUERY, 'email');
	if(i($QUERY, 'phone')) $params['phone'] = i($QUERY, 'phone');
	if(i($QUERY, 'name')) $params['name'] = i($QUERY, 'name');
	if(i($QUERY, 'id')) $params['id'] = i($QUERY, 'id');
	if(i($QUERY, 'city_id')) $params['city_id'] = i($QUERY, 'city_id');

	$applicants = $fam->findAllUsers($params, ' AND ');
}
else if($action== 'Add'){
	$user_id = i($QUERY,'user_id',0);
	if($user_id!=0){
		$user = $fam->getUser($user_id);
	}
}
else if($action == "Add Application"){	
	$preference = [];
	$user_id = i($QUERY,'user_id',0);
	$city_id = i($QUERY,'fellowship_city_id',0);

	$preference[1] = i($QUERY, 'preference_1',0);
	$preference[2] = i($QUERY, 'preference_2',0);
	$preference[3] = i($QUERY, 'preference_3',0);
	for($i=1; $i<=3; $i++){
		if($preference[$i]!=0){
			$fam->addApplicant($user_id,$preference[$i],$i,$city_id);
		}
	}
}

unset($verticals[0]); //Removing Option None from the Vertical List


render();
