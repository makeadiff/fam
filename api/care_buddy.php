<?php
require '../../common/common.php';

/// Purpose: For LC Pre-engagement, we needed a system that will pair up participants in a way that the pair is from different cities and different verticals. Outputs a CSV.

$all_cities = keyFormat($common->getCities(), ['name', 'id']);
$all_groups = [
	'Campaigns'		=> 11,
	'Ed Support'	=> 19,
	'Finance'		=> 15,
	'Fundraising'	=> 370,
	'Human Capital'	=> 5,
	'Shelter Operations'	=> 269,
	'Shelter Support'		=> 4,
	'TRA'			=> 272,
];
$all_verticals = [
	'Campaigns'		=> 7,
	'Ed Support'	=> 3,
	'Finance'		=> 9,
	'Fundraising'	=> 17,
	'Human Capital'	=> 8,
	'Shelter Operations'	=> 2,
	'Shelter Support'		=> 4,
	'TRA'			=> 5,
];

$fellows = $sql->getAll("SELECT DISTINCT U.id, U.name, US.group_id, G.vertical_id, C.id AS city_id, U.sex
	FROM User U
	INNER JOIN FAM_UserStage US ON US.user_id = U.id
	INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
	INNER JOIN `Group` G ON G.id=US.group_id
	INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
	WHERE US.stage_id = 4 AND US.status = 'selected'");
// $strats = $sql->getAll("SELECT DISTINCT U.id, U.name, U.sex, U.city_id, 
// 	FROM User U
// 	INNER JOIN UserGroup UG ON UG.user_id=U.id 
// 	INNER JOIN `Group` G ON G.group_id=UG.group_id
// 	WHERE UG.year=2018 AND U.status='1' AND U.type='volunteer' AND ")

// dump($fellows);exit;

require '../includes/classes/ParseCSV.php';
$sheet = new ParseCSV('https://docs.google.com/spreadsheets/d/e/2PACX-1vRmGv9KynEB4ttU89SR6NMOsXpJfihRWs0677ZRnpuWzH0U2AlKezrVnc8nPTmVtvQI336lV9m383Cl/pub?gid=159515614&single=true&output=csv');
foreach($sheet as $row_index => $row) {
	if($row_index == 1) continue;

	$strat = [
		'name'		=> $row['A'],
		'city_id'	=> $all_cities[$row['C']],
		'group_id'	=> $all_groups[$row['B']],
		'vertical_id'	=> $all_verticals[$row['B']],
	];

	$fellows[] = $strat;
}
print '<pre>';

$pairs = [];
$run = 0;

while($run < 10) {
	for($i = 0; $i < count($fellows); $i++) {
		$f1 = i($fellows, $i, false);
		if(!$f1) {
			// print $i . '|';
			continue;
		}
		$try = 0;

		while($try < 10) {
			$j = rand(0, count($fellows));
			$f2 = i($fellows, $j, false);
			if(!$f2) {
				// print $j . ';';
				continue;
			}

			if(($f1['city_id'] != $f2['city_id']) and ($f1['vertical_id'] != $f2['vertical_id'])) {
				$pairs[] = [$f1, $f2];

				unset($fellows[$i]);
				unset($fellows[$j]);

				$fellows = array_values($fellows);
				break;
			} else {
				$try++;
			}
		}
	}

	$run++;

	if(count($fellows) < 2) {
		break;
	}
}

print "Buddy A,City,Vertical,,Buddy B,City,Vertical\n";
$all_cities_id_name = keyFormat($common->getCities(), ['id', 'name']);
foreach ($pairs as $couple) {
	print $couple[0]['name'] . "," . $all_cities_id_name[$couple[0]['city_id']] . "," . $verticals[$couple[0]['group_id']] . ",";
	print $couple[1]['name'] . "," . $all_cities_id_name[$couple[1]['city_id']] . "," . $verticals[$couple[1]['group_id']] . "\n";
}
// dump($pairs);
dump($fellows);
// print count($fellows);
