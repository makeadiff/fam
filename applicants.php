<?php
require 'common.php';

$all_groups = $verticals;
$all_groups[0] = 'Any';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Any';

$group_id = i($QUERY, 'group_id', 0);
$city_id = i($QUERY, 'city_id', 0);
$action = i($QUERY, 'action', '');

if($action == 'delete') {
	if(!$is_director) die("You have to be a director to delete applicants.");

	$sql->update("FAM_UserGroupPreference", ['status' => 'withdrawn'], ['id' => i($QUERY, 'ugp_id')]);
	$QUERY['success'] = "Applicant Deleted Successfully";
	// header("Location: applicants.php?city_id=$city_id&group_id=$group_id");
}

$checks = ['1=1'];
$join = '';
$selects = '';
$join_condition = '';
if($group_id) {
	$checks[] = "UGP.group_id=$group_id";
	$join_condition = "AND UE.group_id=$group_id";
}
if($city_id) $checks[] = "((UGP.city_id != 0 AND UGP.city_id={$city_id}) OR (UGP.city_id = 0 AND U.city_id={$city_id}))";

$query = "SELECT U.id, U.name, U.email, U.mad_email, U.phone, GROUP_CONCAT(DISTINCT G.name ORDER BY UGP.preference SEPARATOR ', ') AS applied_groups, 
					C.name AS city, UGP.preference, UGP.id AS ugp_id, E.name AS evaluator
			FROM User U
			INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id=U.id
			INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
			LEFT JOIN FAM_UserEvaluator UE ON U.id=UE.user_id $join_condition
			LEFT JOIN User E ON E.id=UE.evaluator_id
			INNER JOIN `Group` G ON UGP.group_id=G.id
			WHERE " . implode(" AND ", $checks) . " AND UGP.status != 'withdrawn'
			GROUP BY UGP.user_id";
if($group_id) $query .= " ORDER BY UGP.preference, U.name";
else $query .= " ORDER BY C.name, U.name";

$applicants_pager = new SqlPager($query, 25);

$applicants = $applicants_pager->getPage();

render();
