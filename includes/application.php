<?php
$user_info = check_user();
$user = $user_info['current_user'];

$user_id = 1;// $user['id'];
$fam = new FAM;

require '../driller/models/Common.php';
$common = new Common;
$html = new HTML;

