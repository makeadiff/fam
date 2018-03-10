<?php
require 'common.php';

require '../driller/models/Common.php';
$common = new Common;
$fam = new FAM;
$html = new HTML;

$strats = $common->getUsers(['group_id' => 350]);
$directors = $common->getUsers(['group_type' => 'national']);
$vertical_id = 3; // :TODO: Get the vertical Id of the current user. 
$vertical_name = $common->getVerticalName($vertical_id);
$users = array_merge($directors, $strats);

$groups_in_vertical = $fam->getGroups($vertical_id);
$all_groups = keyFormat($groups_in_vertical, ['id', 'name']);
$group_id = i($QUERY, 'group_id', $groups_in_vertical[0]['id']);

if(i($QUERY, 'action') == 'Assign') {
	$assignments = $QUERY['selected'];

	// Clear existing evaluator assignemts.
	$sql->remove("FAM_Evaluator", [
		'group_id' => $group_id
	]);

	// Add the newly marked ones.
	foreach ($assignments as $user_id) {
		$sql->insert("FAM_Evaluator", [
			'user_id'	=> $user_id,
			'group_id'	=> $group_id,
			'added_on'	=> 'NOW()'
		]);
	}
}

$existing_evaluators = $fam->getEvaluators($group_id);

render();
