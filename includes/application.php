<?php
$user_info = check_user();

$user_id = 1;// $user['id'];
$fam = new FAM;
$year = 2017;

require dirname(__FILE__) . '/../../driller/models/Common.php';
$common = new Common;
$html = new HTML;

$user = $user_info['current_user'];
$user['groups'] = $common->getUserGroups($user['id']);

function showApplicantStatus($user_id, $stage_id) {
	global $fam;

	$status = $fam->getStageStatus($user_id, $stage_id);
	if($status['status'] == 'selected') echo '<span class="fa fa-check-circle success-message">Selected</span>';
	else if($status['status'] == 'rejected') echo '<span class="fa fa-times-circle error-message">Rejected</span>';
}
