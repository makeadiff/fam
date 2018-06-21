<?php
require 'common.php';

$highest_group = $common->getHighestGroup($user_id);
$vertical_id = i($QUERY,'vertical_id', $highest_group['vertical_id']);

$verticals = keyFormat($common->getVerticals(), ['id', 'name']);
if(!$vertical_id) $vertical_id = @reset(array_keys($verticals));
$fellows = getUsersInVertical($vertical_id, 'fellow');
$strats = getUsersInVertical($vertical_id, 'strat');

render();

function getUsersInVertical($vertical_id, $type) {
	global $sql, $year;

	return $sql->getAll("SELECT DISTINCT U.id,U.name,U.phone,U.email,U.mad_email,C.name AS city, G.name AS group_name,G.vertical_id
		FROM User U 
		INNER JOIN UserGroup UG ON U.id=UG.user_id
		INNER JOIN `Group` G ON G.id=UG.group_id
		INNER JOIN City C ON C.id=U.city_id
		WHERE G.vertical_id=$vertical_id AND G.type='$type' AND G.group_type='normal' AND G.status='1' AND UG.year=$year 
			AND U.user_type='volunteer' AND U.status='1'
		ORDER BY C.name, U.name");
}
