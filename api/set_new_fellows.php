<?php
require '../../common/common.php';

/// Purpose : Get all the people marked as selected in FAM and give them the appropriate usergroup in MADApp. Basically sets all the new fellows.

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$new_year = 2018;

foreach ($all_cities as $city_id => $city_name) {
	foreach ($verticals as $group_id => $vertical_name) {

		$fellows = $sql->getCol("SELECT DISTINCT U.id
			FROM User U
			INNER JOIN FAM_UserStage US ON US.user_id = U.id
			INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
			INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
			WHERE US.stage_id = 4
				AND US.status = 'selected'
				AND C.id = ".$city_id."
				AND US.group_id = ".$group_id);

		foreach ($fellows as $fellow_user_id) {
			print "Fellow ID : $fellow_user_id  -  UserGroup ID : $group_id - ";
			$exsiting = $sql->getOne("SELECT id FROM UserGroup WHERE user_id=$fellow_user_id AND group_id=$group_id AND year=$new_year");
			if(!$exsiting) {
				$sql->insert("UserGroup", [
					'user_id'	=> $fellow_user_id,
					'group_id'	=> $group_id,
					'year'		=> $new_year
				]);
				print "Inserted\n";
			} else {
				print "Exists Already\n";
			}
		}

	}
}

// render();
