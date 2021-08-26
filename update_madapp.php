<?php
require 'common.php';

$continue = (i($QUERY, 'continue', 'No') === 'Yes') ? true : false;

if(!$continue) {
	render();
	exit;
}

// Asumption : Script will be run in the new year. 
$all_cities = keyFormat($common->getCities(), ['id', 'name']);

$selected = [];

// First, unset all last year fellow's ka email id.
$last_year = $year - 1;
$prev_fellows = $sql->getAll("SELECT U.id as 'user_id', G.name
								FROM User U
								INNER JOIN `UserGroup` UG ON U.id = UG.user_id AND UG.year = $last_year
								INNER JOIN `Group` G ON G.id = UG.group_id
								WHERE G.type = 'fellow' AND G.group_type = 'normal' AND G.status='1' AND U.mad_email IS NOT NULL"); // :TODO: Strats?

foreach($prev_fellows as $fellow_id){
	$sql->update('User',[ 'mad_email' => NULL ], 'id='.$fellow_id['user_id']);
}

$mentor_group_id = 8;
$selected = $sql->getAll("SELECT U.id as 'user_id', U.name as 'name', C.name as 'city', G.name as 'role', G.id as 'group_id'
								FROM User U
									INNER JOIN FAM_UserStage US ON US.user_id = U.id
									INNER JOIN `Group` G ON G.id = US.group_id
									INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
									INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
								WHERE US.stage_id = 4 AND US.status = 'selected' AND US.year = $last_year AND US.group_id != $mentor_group_id
								GROUP BY U.id
								ORDER BY C.name ASC");

// This part sets up the new emails of the new fellows. REQUIRES A sheet with the user id => email mapping.
// $email_ids = getEmailFromSheet(
// 				"https://docs.google.com/spreadsheets/d/e/2PACX-1vTQE8sKcse5jEOAhOC5onBvLJxjIlFWC8n66Ced9WMte5uKjsUcVHs-N3j1aNZW_Uj0VupbNsQRFjHC/pub?gid=1773549663&single=true&output=csv",
// 				'A','F');
// foreach ($selected as $key => $fellow) {
// 	$selected[$key]['new_email'] = $email_ids[$fellow['user_id']];
// }

$group_updated = [];
$email_updated = [];

foreach ($selected as $fellow) {
	$group_updated[$fellow['user_id']] = 'Failed';
	$exsiting = $sql->getOne("SELECT id FROM UserGroup WHERE user_id=".$fellow['user_id']." AND group_id=".$fellow['group_id']." AND year = $year");
	if(!$exsiting) {
		$sql->insert("UserGroup", [
			'user_id'	=> $fellow['user_id'],
			'group_id'	=> $fellow['group_id'],
			'year'		=> $year
		]);
		$group_updated[$fellow['user_id']] = 'Updated';
	} else {
		// echo "User $fellow[user_id] Set already. No Updated necessary.<br />";
		$group_updated[$fellow['user_id']] = 'Not Needed';
	}

	$email_updated[$fellow['user_id']] = 'Not Updated';
	if(isset($fellow['new_email'])) {
		$sql->update('User',[ 'mad_email' => $fellow['new_email'] ],'id='.$fellow['user_id']);
		$email_updated[$fellow['user_id']] = 'Updated';
	}
}

render();
