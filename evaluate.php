<?php
require 'common.php';

$applicant_id = intval(i($QUERY, 'applicant_id', 0));
if(!$applicant_id) {
	header("Location: my_applicants.php");
}
$my_applicants = $fam->getApplicants(['evaluator_id' => $user_id]);

// Make sure the current applicant has been assigned to the current evaluator
if(!in_array($applicant_id, colFormat($my_applicants)) and !$is_director) {
	header("Location: my_applicants.php");
}

$stage_id = intval(i($QUERY, 'stage_id'));
$stage_name = $fam->getStage($stage_id);
$applicant = $common->getUser($applicant_id);
$all_status = [
	'pending'	=> 'Pending',
	'selected'	=> 'Selected',
	'rejected'	=> 'Rejected'
];
$categories = $fam->getCategories($stage_id);

if(i($QUERY, 'action') == 'Save') {
	$response = i($QUERY, 'response');

	if($response) {
		foreach ($response as $parameter_id => $value) {
			$fam->saveEvaluation([
				'applicant_id'	=> $applicant_id,
				'parameter_id'	=> $parameter_id,
				'evaluator_id'	=> $user_id,
				'response'		=> $value,
			]);
		}
	}

	$status = i($QUERY, 'status');
	$comment = i($QUERY, 'comment');
	if($status) {
		$fam->saveStageStatus([
			'user_id'	=> $applicant_id,
			'stage_id'	=> $stage_id,
			'status'	=> $status,
			'comment'	=> $comment,
			'evaluator_id' => $user_id
		]);
	}

	$QUERY['success'] = "Data saved.";
}
$stage_info = $fam->getStageStatus($applicant_id, $stage_id);

if(i($QUERY, 'ajaxify')) {
	print json_encode(['status' => 'success', 'data' => $QUERY['success']]);
} else {
	render();
}
