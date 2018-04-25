<?php
require 'common.php';

$vertical_task_status = $sql->getAll('SELECT * FROM FAM_UserStage WHERE stage_id = 5 AND group_id = 0');

dump($vertical_task_status);
updateData($sql,$vertical_task_status);


$pi_status = $sql->getAll('SELECT * FROM FAM_UserStage WHERE stage_id = 4 AND group_id = 0');

// dump($vertical_task_status);
// updateData($sql,$pi_status);



function updateData($sql,$array){
  foreach ($array as $key => $user) {
    $group_id = getFirstPreference($sql,$user['user_id']);
    $sql->update('FAM_UserStage',array(
      'group_id' => $group_id
    ),'id='.$user['id']);
  }
}


function getFirstPreference($sql,$user_id){
  $group_id = $sql->getOne('SELECT group_id FROM FAM_UserGroupPreference WHERE user_id='.$user_id.' AND preference=1');
  return $group_id;
}
