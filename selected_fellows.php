<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);

$total_volunteers = $sql->getOne("SELECT COUNT(id) FROM User WHERE status='1' AND user_type='volunteer'");
$total_filled = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
	INNER JOIN User U ON UGP.user_id=U.id
	WHERE preference=1 AND UGP.year=$year");

$applications = [];
$selected = [];

foreach ($all_cities as $city_id => $city_name) {
	foreach ($verticals as $vertical_id => $vertical_name) {
		$applications[$city_id][$vertical_id] = $sql->getOne("SELECT GROUP_CONCAT(DISTINCT U.name) as fellow_names
			FROM User U
			INNER JOIN FAM_UserStage US ON US.user_id = U.id
			INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
			INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
			WHERE US.stage_id = 4
				AND US.status = 'selected'
				AND UGP.year = $year
				AND US.year = $year
				AND C.id = $city_id
				AND US.group_id = $vertical_id
			ORDER BY C.name, U.name ASC");
	}
}

render();
