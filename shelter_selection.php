<?php
require 'common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);

$total_volunteers = $sql->getOne("SELECT COUNT(id) FROM User WHERE status='1' AND user_type='volunteer'");
$total_filled = $sql->getOne("SELECT COUNT(DISTINCT user_id) FROM FAM_UserGroupPreference UGP
	INNER JOIN User U ON UGP.user_id=U.id
	WHERE preference=1 AND UGP.year = $year");

$verticals = [
	'269'	=> "Shelter Ops",
];


if(isset($_POST['user_id'])){
	$post_vars = $_POST;
	unset($post_vars['user_id']);
	foreach ($post_vars as $key => $shelter_id) {
		$applicant_id = str_replace('shelter_id_','',$key);
		if($shelter_id!=0){
			$sql->update('FAM_UserStage',['shelter_id' => $shelter_id],
				[
					'user_id' 	=> $applicant_id,
					'group_id' 	=> '269',
					'stage_id' 	=> '4',
					'year'		=> $year
				]);
		}
		else{
			$sql->update('FAM_UserStage',['shelter_id' => 0],
				[
					'user_id' 	=> $applicant_id,
					'group_id' 	=> '269',
					'stage_id' 	=> '4',
					'year'		=> $year
				]);
		}
	}
};

// Data source - https://docs.google.com/spreadsheets/d/150mVAUvisYObaW2MVUZfi2tjbKxvd2tZalB3gfr091o/edit?ts=5aacf12d#gid=675197629

$applications = [];
$selected = [];

foreach ($all_cities as $city_id => $city_name) {
	foreach ($verticals as $vertical_id => $vertical_name) {

		$applications[$city_id][$vertical_id] = $sql->getAll("SELECT DISTINCT U.id, U.name as fellow, US.shelter_id
			FROM User U
			INNER JOIN FAM_UserStage US ON US.user_id = U.id
			INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
			INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
			WHERE US.stage_id = 4
				AND US.status = 'selected'
				AND C.id = $city_id
				AND US.group_id = $vertical_id
				AND UGP.year = $year
				AND US.year = $year
			ORDER BY C.name, U.name ASC");
	}
}


// dump($applications);

// $template->addResource("js/library/DataTables/datatables.min.css", 'css');
// $template->addResource("js/library/DataTables/datatables.js", 'js');

$multiplication_factor = 3;
render();
