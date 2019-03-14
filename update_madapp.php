<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);

if(i($QUERY, 'approve') == "Yes") {
	$continue = false;
} else{
	$continue = true;
}

$applications = [];
$selected = [];

$applications = $sql->getAll("SELECT U.id as 'user_id', U.name as 'name', U.email as 'email', U.sex as 'sex', C.name as 'city', G.name as 'role', G.id as 'group_id'
								FROM User U
									INNER JOIN FAM_UserStage US ON US.user_id = U.id
									INNER JOIN `Group` G ON G.id = US.group_id
									INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
									INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
								WHERE US.stage_id = 4 AND US.status = 'selected' AND UGP.year = $year
								GROUP BY U.id
								ORDER BY C.name ASC");

$email_ids = getEmailFromSheet("https://docs.google.com/spreadsheets/d/e/2PACX-1vTNbNVE-D1eildScABsBTTzHU7nxONdT-Pnpo7jlrvsR9AbF8lSY2HZIliZW9erB9g1zmjq995SsAto/pub?gid=1516479643&single=true&output=csv");

$multiplication_factor = 3;
render();
