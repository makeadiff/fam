<?php
require 'common.php';
$debug = true;

$all_groups = $verticals;
$all_groups[0] = 'Any';

$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Any';

$query = "SELECT U.id, U.name, U.email, C.name AS city, U.phone, 
			(SELECT group_id FROM FAM_UserGroupPreference WHERE user_id=U.id AND year=$year AND preference = 1 AND status='pending') AS first_preference, 
			(SELECT group_id FROM FAM_UserGroupPreference WHERE user_id=U.id AND year=$year AND preference = 2 AND status='pending') AS second_preference, 
			G.name AS current_role, UGP.added_on as added_on 
			FROM User U
			INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id=U.id AND UGP.year=$year
			INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
			INNER JOIN UserGroup UG ON UG.user_id=U.id AND UG.main='1' AND UG.year = $year
			INNER JOIN `Group` G ON UG.group_id=G.id 
			WHERE U.user_type = 'volunteer'";
if($debug) $query .= " LIMIT 0, 10";

$raw_data = $sql->getAll($query);
$data = [];

$applicant_feedback_recommend_question_id = 23;
$applicant_feedback_question_ids = [
	'mobilization' => 16,
	'communication' => 17,
	'ability' => 18,
	'commitment' => 19,
	'responsibility' => 20,
];
$day_3_parameter_ids = $sql->getById("SELECT id,name FROM FAM_Parameter WHERE category_id = 3 AND status = '1' AND type='yes-no' ORDER BY sort");
$yes_no = [
	'1' => 'Yes',
	'0' => 'No',
	'-1' => 'N/A'
];
$common_task_parameter_ids = $sql->getById("SELECT id,name FROM FAM_Parameter WHERE category_id IN (31,32) AND status = '1' AND type='1-5' ORDER BY sort");

foreach($raw_data as $row) {
	$user_id = $row['id'];
	$details = [
		'id'				=> $row['id'],
		'name'				=> $row['name'],
		'city'				=> $row['city'],
		'first_preference'	=> i($all_groups, $row['first_preference'], $row['first_preference']),
		'second_preference'	=> i($all_groups, $row['second_preference'], $row['second_preference']),
		'blank_1'			=> '',	
		'current_role'		=> $row['current_role'],
	];

	// Applicant background check / volunteer given feedback
	$recommendation_aggregate = $sql->getById("SELECT feedback, COUNT(id) AS count FROM FAM_ApplicantFeedback 
												WHERE applicant_user_id=$user_id AND question_id = $applicant_feedback_recommend_question_id AND year = $year
												GROUP BY feedback");
	$details['recommend_by_count'] = i($recommendation_aggregate, 'Yes', 0);
	$details['not_recommend_by_count'] = i($recommendation_aggregate, 'No', 0);

	$feedback_aggregate = $sql->getById("SELECT question_id, ROUND(AVG(feedback), 2) AS average FROM FAM_ApplicantFeedback 
												WHERE applicant_user_id=$user_id 
												AND question_id IN (" . implode(',', array_values($applicant_feedback_question_ids)) . ") 
												AND year = $year
												GROUP BY question_id");
	foreach ($applicant_feedback_question_ids as $question_key => $question_id) {
		$details[$question_key . '_avg'] = i($feedback_aggregate, $question_id, 'No Data');
	}

	// Evaluation Data
	$day_3_evaluation = $sql->getById("SELECT parameter_id,response FROM FAM_Evaluation 
				WHERE user_id=$user_id AND year = $year AND parameter_id IN (" . implode(',', array_keys($day_3_parameter_ids)) . ")");
	foreach ($day_3_parameter_ids as $parameter_id => $name) {
		$details[unformat($name)] = i($yes_no, i($day_3_evaluation, $parameter_id, -2), 'No Data');
	}
	$details['total_day_3'] = '';
	$details['blank_2'] = '';

	// Evaluation Data
	$common_task_evaluation = $sql->getById("SELECT parameter_id,response FROM FAM_Evaluation 
				WHERE user_id=$user_id AND year = $year AND parameter_id IN (" . implode(',', array_keys($common_task_parameter_ids)) . ")");
	foreach ($common_task_parameter_ids as $parameter_id => $name) {
		$details[unformat($name)] = i($common_task_evaluation, $parameter_id, 'No Data');
	}
	$details['blank_3'] = '';

	$data[] = $details;
}

if($debug) {
	dump($data);
} else {
	header("Content-type: text/csv");
	eader('Content-Disposition: attachment; filename="Applicant_Data.csv"');
	print array2csv($data);
}