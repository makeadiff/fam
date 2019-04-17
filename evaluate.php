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
	'2'		=> 'https://drive.google.com/open?id=1iSyOTpJ8WvqcmjRZBUu0peoTdonLKU-wEXcZ4Sd6zyI', //City Team Lead, Updated for 2019
	'19'	=> 'https://drive.google.com/open?id=1Lr4PS6dnxfIohi66QN1fdxe4l7mwJcRk6V-RtZrsHWk', //Ed Support, Updated for 2019
	'378'	=> 'https://drive.google.com/open?id=1dtwb0gb1xagSF5ARPSVsaUv06v6sRRs_UELSTRNf-1E', //Aftercare, Updated for 2019
	'272'	=> 'https://drive.google.com/open?id=1br8QUK-CHkqZ4HxbveBpVGlvXVRveIeNWcHIdVEaFlM', //Transition Readiness, Updated for 2019
	'370'	=> 'https://drive.google.com/open?id=1r6Q-Ghs4s-Bl6_EoGpuTImO_HV45yqlVbHWpsEaH2kE', //Fundraising, Updated for 2019
	'269'	=> 'https://drive.google.com/open?id=10CrF5GyExazYvA5glGWPJVUXoRQxabGrvWnCccLh3Tk', //Shelter Operations, Updated for 2019
	'4'		=> 'https://drive.google.com/open?id=13_RLBONIr_hjJvgjvP7eIhijKoAmaRwf9gXKuFct2V0', //Shelter Support, Updated for 2019
	'5'		=> 'https://drive.google.com/open?id=1O96IHYGUfHccT43ZzMuBoTZP8fhYX6YPWZXRqZLwXRg', //Human Capital, Updated for 2019
	'15'	=> 'https://drive.google.com/open?id=1YB5ryflVV0LFLRedo-Sh2Ggdpkf5BZ8up03ovpt-XFY', //Finance, Updated for 2019
	'11'	=> 'https://drive.google.com/open?id=1KbGYtwkF1TqAw_mHL_6osN0Clf22OB142mfxAKeYsyY', //Campaigns and Communications, Updated for 2019
	'375'	=> 'https://drive.google.com/open?id=1ilaYYMsJ0oldG_MMaqn9xdnISmEWVCCMcL8eA3Q-8Gw', //Foundational Programme, Updated for 2019
	'8'		=> 'https://drive.google.com/open?id=1aJLmCeTxNO-YbX7XfAN0m4WFjygc7kooFjCrdAj1npQ', // Mentors, Updated for 2019
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

$applicant_status = $fam->getSelectionStatus($applicant_id);

if(i($QUERY, 'ajaxify')) {
	print json_encode(['status' => 'success', 'data' => $QUERY['success']]);
} else {
	render();
}
