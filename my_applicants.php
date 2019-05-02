<?php
require 'common.php';

$applicants = $fam->getApplicants(['evaluator_id' => $user_id]);
$all_groups = keyFormat($fam->getGroups(0), ['id', 'name']);
$all_groups[0] = 'Unknown';
$stage_id = i($QUERY,'stage_id',0);
render();
