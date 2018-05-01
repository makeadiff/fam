<?php
require 'common.php';


$stage_id = intval(i($QUERY, 'stage_id'));
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

$applications = $fam->getApplications($applicant_id);

render();
