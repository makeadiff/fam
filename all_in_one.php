<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
unset($all_cities[26]);

$total_volunteers = $sql->getOne("SELECT COUNT(id) FROM User WHERE status='1' AND user_type='volunteer'");
$total_filled = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
	INNER JOIN User U ON UGP.user_id=U.id
	WHERE preference=1 AND UGP.year=$year");

// Data source
$requirements = getRequirementFromSheet("https://docs.google.com/spreadsheets/d/e/2PACX-1vRzSwv2Yr5vT9YCjqRpraem2ZBpVKy2VT_UU9L2iA3364MIBiN1zhdVCX2bIq_3CIg7owI2yQx86q1q/pub?gid=675197629&single=true&output=csv");

$applications = [];
$selected = [];

foreach ($all_cities as $city_id => $city_name) {
	// $applications[$city_id] = array_combine(array_keys($verticals), array_fill(0, count($verticals), 0)); // Create init values.

	$applications[$city_id] = $sql->getById("SELECT UGP.group_id, COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
		INNER JOIN User U ON UGP.user_id=U.id
		WHERE preference=1 AND UGP.year=$year AND ((UGP.city_id != 0 AND UGP.city_id=$city_id) OR (UGP.city_id = 0 AND U.city_id=$city_id))
		GROUP BY UGP.group_id");

	// $selected[$city_id] = $sql->getById("SELECT UGP.group_id, COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
	// 	INNER JOIN User U ON UGP.user_id=U.id
	// 	WHERE U.city_id=$city_id AND preference=1 AND UGP.status='selected'
	// 	GROUP BY UGP.group_id");
}

// $template->addResource("js/library/DataTables/datatables.min.css", 'css');
// $template->addResource("js/library/DataTables/datatables.js", 'js');

$multiplication_factor = 3;
render();
