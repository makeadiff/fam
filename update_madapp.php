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


$applications = $sql->getAll("SELECT U.id as 'user_id', U.name as 'name', C.name as 'city', G.name as 'role', G.id as 'group_id'
								FROM User U
									INNER JOIN FAM_UserStage US ON US.user_id = U.id
									INNER JOIN `Group` G ON G.id = US.group_id
									INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
									INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
								WHERE US.stage_id = 4 AND US.status = 'selected' AND US.year = $year and US.group_id <> 8
								GROUP BY U.id
								ORDER BY C.name ASC");


$email_ids = getEmailFromSheet("https://docs.google.com/spreadsheets/d/e/2PACX-1vSiGf_vxmXrfxRS5Rg05sWTlxyaZ28WVEgc26v7_As_ike744TYuBPcpVRdSSS50Y7uIwA25h5kmCHz/pub?gid=137281857&single=true&output=csv");


foreach ($applications as $key => $fellows) {
	$applications[$key]['new_email'] = $email_ids[$fellows['user_id']];
}

if($continue == false){
	$new_year = ++$year;

	clear_current_email($sql);

	foreach ($applications as $fellows) {
		print "Fellow ID : ".$fellows['user_id']." -  UserGroup ID : ".$fellows['group_id']." - ";
		$exsiting = $sql->getOne("SELECT id FROM UserGroup WHERE user_id=".$fellows['user_id']." AND group_id=".$fellows['group_id']." AND year=".$new_year."");
		if(!$exsiting) {
			$sql->insert("UserGroup", [
				'user_id'	=> $fellows['user_id'],
				'group_id'	=> $fellows['group_id'],
				'year'		=> $new_year
			]);
			print "Inserted\n";
		} else {
			print "Exists Already\n";
		}
		$email_update = $sql->update('User',[
			'mad_email' => $fellows['new_email']
		],'id='.$fellows['user_id']);
		print "Email Updated\n";
	}
}

$multiplication_factor = 3;
render();
