<?php
require 'common.php';

$applicants = $fam->getApplicants(['evaluator_id' => $user_id]);
$all_groups = keyFormat($fam->getGroups(0), ['id', 'name']);
$all_groups[0] = 'Unknown';
$all_status = [
	'pending'	=> 'Pending',
	'selected'	=> 'Selected',
	'rejected'	=> 'Rejected'
];

render();
