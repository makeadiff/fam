<?php
require '../common.php';

$user_id = i($QUERY, 'user_id');
$group_id = i($QUERY, 'group_id');
$status = i($QUERY, 'status');

$fam->setSelectionStatus($user_id, $group_id, $status);

echo json_encode(['status' => 'success']);