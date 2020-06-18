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

$prevfellows = $sql->getAll("SELECT U.id as 'user_id'
								FROM User U
									INNER JOIN FAM_UserStage US ON US.user_id = U.id
									INNER JOIN `Group` G ON G.id = US.group_id
									INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
									INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
								WHERE US.stage_id = 4 AND US.status = 'selected' AND US.year = ($year-1) and US.group_id <> 8
								GROUP BY U.id
								ORDER BY C.name ASC");

foreach($prevfellows as $fellow_id){
	$email_update = $sql->update('User',[
		'mad_email' => NULL
	],'id='.$fellow_id['user_id']);
}

// exit;

$applications = $sql->getAll("SELECT U.id as 'user_id', U.name as 'name', C.name as 'city', G.name as 'role', G.id as 'group_id'
								FROM User U
									INNER JOIN FAM_UserStage US ON US.user_id = U.id
									INNER JOIN `Group` G ON G.id = US.group_id
									INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
									INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
								WHERE US.stage_id = 4 AND US.status = 'selected' AND US.year = $year and US.group_id <> 8
								GROUP BY U.id
								ORDER BY C.name ASC");


$email_ids = getEmailFromSheet("https://docs.google.com/spreadsheets/d/e/2PACX-1vTQE8sKcse5jEOAhOC5onBvLJxjIlFWC8n66Ced9WMte5uKjsUcVHs-N3j1aNZW_Uj0VupbNsQRFjHC/pub?gid=1773549663&single=true&output=csv",'A','F');


// dump($applications);
// dump($email_ids);
// exit;

foreach ($applications as $key => $fellows) {
	$applications[$key]['new_email'] = $email_ids[$fellows['user_id']];
}

if($continue == false){
	$new_year = ++$year;
	// dump($new_year);exit;

	clear_current_email($sql);

	foreach ($applications as $fellows) {
		// echo "<script>console.log('fellow_user_id:".$fellows['user_id']."; group_id:".$fellows['group_id']."');</script>";
		$exsiting = $sql->getOne("SELECT id FROM UserGroup WHERE user_id=".$fellows['user_id']." AND group_id=".$fellows['group_id']." AND year=".$new_year."");
		if(!$exsiting) {
			$sql->insert("UserGroup", [
				'user_id'	=> $fellows['user_id'],
				'group_id'	=> $fellows['group_id'],
				'year'		=> $new_year
			]);
			// echo "<script>console.log('Updated');</script>";
		} else {
			// echo "<script>console.log('Exists Already');</script>";
		}
		$email_update = $sql->update('User',[
			'mad_email' => $fellows['new_email']
		],'id='.$fellows['user_id']);
		// echo "<script>console.log('Email Updated');</script>";
	}
}

$multiplication_factor = 3;
render();
