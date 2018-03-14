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