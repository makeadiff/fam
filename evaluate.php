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

$group_id = intval(i($QUERY, 'group_id', 0));
$categories = $fam->getCategories($stage_id, $group_id);
$reference_link = '';
$all_reference_links = [
	'2'		=> 'https://docs.google.com/document/d/1WRj5izax8xEZrCErLT-x4Y7Bq_eyFfdZF_55Kfa1Rrg', //City Team Lead
	'19'	=> 'https://docs.google.com/document/d/1vDE2Sl3GrozyMD1y0sdWNbNBzucuUrocN2ZXTjg4cEs', //Ed Support
	'378'	=> 'https://docs.google.com/document/d/1pXXCZMO2y5lEr_iAPXdEccyuIhtEC5CyQtROAU7-XlA', //Aftercare
	'272'	=> 'https://docs.google.com/document/d/1v4_5ry8xbRL-mc9KZNTaYTll26PlUDItFx_KwMAIfO8', //Transition Readiness
	'370'	=> 'https://docs.google.com/document/d/1MneFr4BCpnmCfxyL7DFIjHJ6RyKmAapTz3n-UKW3FOo', //Fundraising
	'269'	=> 'https://docs.google.com/document/d/10CrF5GyExazYvA5glGWPJVUXoRQxabGrvWnCccLh3Tk', //Shelter Operations
	'4'		=> 'https://docs.google.com/document/d/1BPFTgMY_HEaLyXYa8h4kOkL31NOA5CnHcKVuZsoi1wo', //Shelter Support
	'5'		=> 'https://docs.google.com/document/d/1x3L2pHKklMP1d5xaVBrXsPPN_phHeavBNBN6VCyVzvY', //Human Capital
	'15'	=> 'https://docs.google.com/document/d/1UF8cBAM1Nw01CXbQcsXsosSKB_EqOTZpgTJwLoJ5jkc', //Finance
	'11'	=> 'https://docs.google.com/document/d/1scKQ4y7yHWdWifxXuarMnY2mMLELi4RVnQxGpRDqI8M', //Campaigns and Communications
	'375'	=> 'https://docs.google.com/document/d/1ilaYYMsJ0oldG_MMaqn9xdnISmEWVCCMcL8eA3Q-8Gw', //Foundational Programme
];
if($group_id) $reference_link = $all_reference_links[$group_id];

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
			'group_id'	=> $group_id,
			'comment'	=> $comment,
			'evaluator_id' => $user_id
		]);
	}
	$QUERY['success'] = "Data saved.";
}
$stage_info = $fam->getStageStatus($applicant_id, $stage_id, $group_id);

// dump($stage_info);

if(i($QUERY, 'ajaxify')) {
	print json_encode(['status' => 'success', 'data' => $QUERY['success']]);
} else {
	render();
}
