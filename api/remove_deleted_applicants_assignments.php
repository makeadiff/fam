<?php
$_SESSION['user_id'] = 1;
require '../common.php';

/// Purpose: Delete the evaluator assignments for people who have withdrawn the application.

$ugp = $sql->getCol("SELECT DISTINCT user_id FROM FAM_UserGroupPreference WHERE evaluator_id != 0");

$ue = $sql->getCol("SELECT DISTINCT user_id FROM FAM_UserEvaluator");

$diff = array_diff($ue, $ugp);

dump($diff);
