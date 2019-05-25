<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
unset($all_cities[26]);

$total_volunteers = $sql->getOne("SELECT COUNT(id) FROM User WHERE status='1' AND user_type='volunteer'");
$total_filled = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
	INNER JOIN User U ON UGP.user_id=U.id
	WHERE preference=1 AND UGP.year=$year AND UGP.status <> 'withdrawn'");
$total_selected = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserStage US WHERE US.stage_id = 4 AND US.year=$year AND US.status = 'selected'");

// Data source
$requirements = getRequirementFromSheet();

$applications = [];
$selected = [];

foreach ($all_cities as $city_id => $city_name) {
	// $applications[$city_id] = array_combine(array_keys($verticals), array_fill(0, count($verticals), 0)); // Create init values.

	$applications[$city_id] = $sql->getById("SELECT UGP.group_id, COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
		INNER JOIN User U ON UGP.user_id=U.id
		WHERE preference=1 AND UGP.year=$year AND ((UGP.city_id != 0 AND UGP.city_id=$city_id) OR (UGP.city_id = 0 AND U.city_id=$city_id)) AND UGP.status <> 'withdrawn'
		GROUP BY UGP.group_id");

	$selected[$city_id] = $sql->getById("SELECT UGP.group_id, COUNT(DISTINCT UGP.user_id)
			FROM User U
			INNER JOIN FAM_UserStage US ON US.user_id = U.id
			INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
			INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
			WHERE US.stage_id = 4
				AND UGP.status = 'pending'
				AND US.status = 'selected'
				AND UGP.year = $year
				AND US.year = $year
				AND C.id = $city_id
				AND US.group_id = UGP.group_id
			GROUP BY UGP.group_id");

}

$multiplication_factor = 3;
render();
