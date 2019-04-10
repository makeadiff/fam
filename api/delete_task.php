<?php
require '../common.php';

$file_name = $_GET['file'];
$applicant_id = $_GET['applicant_id'];
if(isset($file_name) && isset($applicant_id)) {
  $check_entry = $sql->getAll('SELECT id,common_task_files FROM FAM_UserTask WHERE user_id='.$applicant_id.' AND year='.$year);
  if($check_entry){
    $task_files = $check_entry[0];
    $new_files = str_replace($file_name,'',str_replace('%20',' ',$task_files['common_task_files']));
    $update_db = $sql->update('FAM_UserTask',array(
      'common_task_files' => $new_files
    ),'id='.$task_files['id']);

    $base_name = basename($file_name);

    if($_SERVER['HTTP_HOST'] == 'makeadiff.in'){
      $parent = 'https://makeadiff.in/apps/fellowship-signup/files/index.php?p=';
      $replace = 'http://makeadiff.in/apps/fellowship-signup/tasks/';
    }
    else{
      $parent = 'http://localhost/makeadiff/apps/fellowship-signup/files/index.php?p=';
      $replace = 'http://localhost/makeadiff/apps/fellowship-signup/tasks/';
    }

    $directory = str_replace($base_name,'',str_replace($replace,'',$file_name));
    $url=$parent.$directory.'&dl='.$base_name;


    $dir = '../fellowship-signup/tasks/'.$directory;
    $dirHandle = opendir($dir);
    while ($file = readdir($dirHandle)) {
        if($file==$base_name) {
            unlink($dir.$file);
        }
    }
    closedir($dirHandle);
  }
}
