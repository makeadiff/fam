<?php
$_SESSION['user_id'] = 1;
require '../common.php';

/// Needed to do this when we created the FAM_UserEvaluator table - needed to recreate the data - because they wanted to assign multiple evaluators to an applicant.

$applicant_eval_connections = $sql->getAll("SELECT user_id, evaluator_id, group_id FROM FAM_UserGroupPreference WHERE evaluator_id != 0");

$i = 0;
$total_connections = count($applicant_eval_connections);
print "Total Connections : " . $total_connections . "\n";

exit;
foreach ($applicant_eval_connections as $conn) {
	$id = $sql->insert("FAM_UserEvaluator", [
		'user_id'		=> $conn['user_id'],
		'evaluator_id'	=> $conn['evaluator_id'],
		'group_id'		=> $conn['group_id'],
	]);

	progessBar($i, $total_connections, 3);
	$i++;
}


function progessBar($current, $total, $once_every = 1) {
	if($current % $once_every != 0) return; // Run this function only once every x times.

	$block_count = 50;

	$percentage = intval(($current + 1) / $total * 100);
	$progress_bar_blocks = floor($percentage / (100 / $block_count));

	if(!$progress_bar_blocks) $progress_bar_blocks = 0;
	$space_count = $block_count - $progress_bar_blocks;

	// dump($progress_bar_blocks, $space_count); exit;
	$progress_bar = '|' . @str_repeat('=', $progress_bar_blocks) . '>';
	$progress_bar.= str_repeat(' ', $space_count) . '|';

	print "Progress: $current / $total		$progress_bar	$percentage%\r";
}
