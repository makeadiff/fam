<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);

// Removing Inactive Cities from the list.
unset($all_cities[14]); // Removing Kolkata from the City Array
unset($all_cities[26]); // Removing Leadership from the City Array

// dump($all_cities);

$all_cities[0] = 'All';
$all_verticals = $verticals;
$all_verticals[0] = 'All';
$city_id = i($QUERY, 'city_id', 0);
$group_id = i($QUERY, 'group_id', 0);

$city_check = '';
$city_check_ugp = '';
$group_check_ugp = '';
if($city_id) {
	$city_check = "U.city_id=$city_id AND ";
	$city_check_ugp = "((UGP.city_id != 0 AND UGP.city_id=$city_id) OR (UGP.city_id = 0 AND U.city_id=$city_id)) AND ";
}
if($group_id) {
	$group_check_ugp = "UGP.group_id=$group_id AND ";
}

$total_volunteers = $sql->getOne("SELECT COUNT(id) FROM User U WHERE $city_check U.status='1' AND U.user_type='volunteer'");

$requirements = getRequirementFromSheet();
$requirements['total_group'][0] = array_sum($requirements['total_group']);
$requirements['total_city'][0] = array_sum($requirements['total_city']);

$applicants = [];
$total_filled = 0;
foreach ($verticals as $this_group_id => $name) {
	// We have a U.city_id and a UGP.city_id because we want to capture details of people who are moving to the given city as well.
	$applicant_counts = $sql->getAll("SELECT U.city_id AS u_city_id, UGP.city_id AS ugp_city_id,COUNT(DISTINCT user_id) AS applicant_count FROM FAM_UserGroupPreference UGP
		INNER JOIN User U ON UGP.user_id=U.id
		INNER JOIN City C ON ((UGP.city_id <> 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
		WHERE $city_check_ugp preference=1 AND UGP.status!='withdrawn' AND UGP.year=$year AND UGP.group_id=$this_group_id AND C.id <>26 AND C.id <> 14
		GROUP BY C.id");

	// Initialize the array
	$applicants[0][$this_group_id] = 0;
	$applicants[0][0] = 0;
	foreach ($all_cities as $this_city_id => $city_name) {
		$applicants[$this_city_id][0] = 0;
		$applicants[$this_city_id][$this_group_id] = 0;
	}

	foreach($applicant_counts as $row) {
		$user_or_ugp_city_id = ($row['ugp_city_id']) ? $row['ugp_city_id'] : $row['u_city_id'];
		$applicants[$user_or_ugp_city_id][$this_group_id] += $row['applicant_count'];
		$applicants[$user_or_ugp_city_id][0] += $row['applicant_count'];
		$applicants[0][$this_group_id] += $row['applicant_count'];
		$applicants[0][0] += $row['applicant_count'];
	}

	// The total people who applied is basically a sum of all the shown numbers.
	if($group_id) {
		if($this_group_id == $group_id) $total_filled += $applicants[0][$this_group_id];
	} else {
		if($this_group_id != GROUP_ID_MENTOR){
			$total_filled += $applicants[0][$this_group_id];
		}
	}

}

$multiplication_factor = 3;
$mentor_multiplication_factor = 1;

render();
