<?php
require '../../common/common.php';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);

foreach ($all_cities as $city_id => $city_name) {
	foreach ($verticals as $vertical_id => $vertical_name) {

		$fellows = $sql->getCol("SELECT DISTINCT U.id
			FROM User U
			INNER JOIN FAM_UserStage US ON US.user_id = U.id
			INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
			INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
			WHERE US.stage_id = 4
				AND US.status = 'selected'
				AND C.id = ".$city_id."
				AND US.group_id = ".$vertical_id);

		foreach ($fellows as $fellow_user_id) {
			$sql->insert("UserGroup", [
				'user_id'	=> $fellow_user_id,
				'group_id'	=> $vertical_id,
				'year'		=> 2018
			]);
		}

	}
}

// render();
