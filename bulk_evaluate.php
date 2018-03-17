<?php
require 'common.php';

$stage_id = intval(i($QUERY, 'stage_id'));
$stage_name = $fam->getStage($stage_id);
$category_id = intval(i($QUERY, 'category_id'));
$category_name = $fam->getCategory($category_id);
$parameters = $fam->getParameters($stage_id, $category_id);

$applicants = colFormat($fam->getApplicants(['evaluator_id' => $user_id]));

if(i($QUERY, 'action') == 'Save') {
	$response = i($QUERY, 'response');

	// if($response) {
	// 	foreach ($response as $parameter_id => $value) {
	// 		$fam->saveEvaluation([
	// 			'applicant_id'	=> $applicant_id,
	// 			'parameter_id'	=> $parameter_id,
	// 			'evaluator_id'	=> $user_id,
	// 			'response'		=> $value,
	// 		]);
	// 	}
	// }

	// $status = i($QUERY, 'status');
	// $comment = i($QUERY, 'comment');
	// if($status and $comment) {
	// 	$fam->saveStageStatus([
	// 		'user_id'	=> $applicant_id,
	// 		'stage_id'	=> $stage_id,
	// 		'status'	=> $status,
	// 		'comment'	=> $comment,
	// 		'evaluator_id' => $user_id
	// 	]);
	// }

	$QUERY['success'] = "Data saved.";
}

render();