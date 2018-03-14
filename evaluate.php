<?php
require 'common.php';

$applicant_id = intval(i($QUERY, 'applicant_id', 0));
if(!$applicant_id) {
	header("Location: my_applicants.php");
}
$my_applicants = colFormat($fam->getApplicants(['evaluator_id' => $user_id]));

// Make sure the current applicant has been assigned to the current evaluator
if(!in_array($applicant_id, $my_applicants)) {
	header("Location: my_applicants.php");
}

$stage_id = intval(i($QUERY, 'stage_id'));
$stage_name = $fam->getStage($stage_id);
$applicant = $common->getUser($applicant_id);

$categories = $fam->getCategories($stage_id);

if(i($QUERY, 'action') == 'Save') {
	$response = i($QUERY, 'response');

	foreach ($response as $parameter_id => $value) {
		// Clear existing evaluations, if any.
		$sql->remove("FAM_Evaluation", [
			'user_id'		=> $applicant_id,
			'parameter_id'	=> $parameter_id
		]);

		$sql->insert('FAM_Evaluation', [
			'user_id'		=> $applicant_id,
			'parameter_id'	=> $parameter_id,
			'evaluator_id'	=> $user_id,
			'response'		=> $value,
			'added_on'		=> 'NOW()'
		]);
	}
}

render();