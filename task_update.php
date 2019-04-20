<?php
require 'common.php';

$action = i($QUERY, 'action');
$applicants = [];
$all_tasks = [];

$update = false;

$verticals[0] = 'None';
$all_cities = keyFormat($common->getCities(), ['id', 'name']);
$all_cities[0] = 'Not Moving';
$city_id = i($QUERY, 'city_id', 0);
$group_id = i($QUERY, 'group_id', 0);

if($action == 'Find Applicant') {
	$params = [];

	$email = i($QUERY, 'email');
	if($email) {
		$params['email'] = $email;
		$params['mad_email'] = $email;
	}
	if(i($QUERY, 'phone')) $params['phone'] = i($QUERY, 'phone');
	if(i($QUERY, 'name')) $params['name'] = i($QUERY, 'name');
	if(i($QUERY, 'id')) $params['id'] = i($QUERY, 'id');
	if(i($QUERY, 'city_id')) $params['city_id'] = i($QUERY, 'city_id');

	if($email != '' || i($QUERY,'phone') || i($QUERY,'id')){
		$applicants = $fam->findUser($params, ' OR ', true);
		if(!empty($applicants)){
			$applicant = $applicants[0];
		}
	}
}


if($action == 'Update Tasks') {

	$params = [];

	$email = i($QUERY, 'email');
	if($email) {
		$params['email'] = $email;
		$params['mad_email'] = $email;
	}
	if(i($QUERY, 'phone')) $params['phone'] = i($QUERY, 'phone');
	if(i($QUERY, 'name')) $params['name'] = i($QUERY, 'name');
	if(i($QUERY, 'id')) $params['id'] = i($QUERY, 'id');
	if(i($QUERY, 'city_id')) $params['city_id'] = i($QUERY, 'city_id');

	$applicants = $fam->findUser($params, ' OR ', true);
	if(!empty($applicants)){
		$applicant = $applicants[0];
		if(i($QUERY, 'task_id')) $task_id = i($QUERY, 'task_id');

		if(isset($task_id) && $task_id){
			$update = $sql->update('FAM_UserTask',array(
				'common_task_url' => i($QUERY, 'common_task_url')
			),'id='.$task_id);
		}

		if(isset($_FILES['common_task_files'])){
		  $totalFiles = count($_FILES["common_task_files"]["name"]);
		  for($k=0;$k<$totalFiles;$k++){
		    if($_FILES["common_task_files"]["name"][$k]==''){
		      continue;
		    }
		    $city_name = getCity($applicant['city_id'],$sql);
		    $target_dir = '../fellowship-signup/tasks/'.$city_name.'/Common'.'/';
		    if (!is_dir($target_dir.$applicant['name'])){
		      mkdir($target_dir.$applicant['name'], 0777, true);
		    }
		    $target_dir .= $applicant['name'].'/';
		    $target_file = $target_dir .str_replace(' ','_',$applicant['name']).'_common_'.str_replace(' ','_',basename($_FILES["common_task_files"]["name"][$k]));

		    $uploadOk = 1;

		    if($_SERVER['HTTP_HOST'] == 'makeadiff.in'){
		      $parent = 'http://makeadiff.in/apps';
		    }
		    else{
		      $parent = 'http://localhost/makeadiff/apps';
		    }

		    if(move_uploaded_file($_FILES["common_task_files"]["tmp_name"][$k], $target_file)) {
					$check_entry = $sql->getOne('SELECT common_task_files FROM FAM_UserTask WHERE user_id='.$applicant['id'].' AND year='.$year);
					$common_task_files = $check_entry.$parent.str_replace(' ','%20',str_replace('../','/',$target_file));
				  $inserted = $sql->update("FAM_UserTask", array(
						'common_task_files' => $common_task_files
					),'id='.$task_id);
		    }
		    else {
		      echo '<h2 class="fs-title">Oops, Files were not uploaded</h2><hr>
		      <h3 class="fs-subtitle">
		        Try again.
		      </h3>';
		    }
		  }
		}		
	}



	// for($j=0;$j<3;$j++){
	//   if(!isset($_FILES['task_'.($j+1)])){
	//     continue;
	//   }
	//   $totalFiles = count($_FILES["task_".($j+1)]["name"]);
	//   for($k=0;$k<$totalFiles;$k++){
	//     if($_FILES["task_".($j+1)]["name"][$k]==''){
	//       continue;
	//     }
	//     $city_name = getCity($user['city_id'],$sql);
	//     $target_dir = '../tasks/'.$city_name.'/'.$verticals[$_POST['group_id_'.($j+1)]].'/';
	//     if (!is_dir($target_dir.$user['name'])) {
	//       mkdir($target_dir.$user['name'], 0777, true);
	//     }
	//     $target_dir .= $user['name'].'/';
	//     $target_file = $target_dir .str_replace(' ','_',$user['name']).'_'.str_replace(' ','_',$verticals[$_POST['group_id_'.($j+1)]]).'_'.str_replace(' ','_',basename($_FILES["task_".($j+1)]["name"][$k]));
	//
	//     // dump($target_file);
	//
	//     $uploadOk = 1;
	//
	//     if($_SERVER['HTTP_HOST'] == 'makeadiff.in'){
	//       $parent = 'http://makeadiff.in/apps/fellowship-signup';
	//     }
	//     else{
	//       $parent = 'http://localhost/makeadiff/apps/fellowship-signup';
	//     }
	//
	//     if (move_uploaded_file($_FILES["task_".($j+1)]["tmp_name"][$k], $target_file)) {
	//       $data['preference_' . ($j+1) . '_task_files'] .= $parent.str_replace(' ','%20',str_replace('../','/',$target_file));
	//
	//       $message = '<h2 class="fs-title">YOUR TASK IS SAFE WITH US! </h2><hr>
	//       <h3 class="fs-subtitle">The task has been successfully uploaded and saved.</h3>
	//       <p>
	//         <strong>Done with all tasks?<br/></strong>
	//         That’s awesome! Keep checking your email for updates.
	//       <p>
	//       <p>
	//         <strong>More tasks to upload?<br/></strong>
	//         Finish them before deadlines and get back here to upload!
	//       <p>
	//       <p>
	//         All the best :)
	//       </p>
	//       <hr>';
	//     }
	//     else {
	//       $message = '<h2 class="fs-title">Oops, Files were not uploaded</h2><hr>
	//       <h3 class="fs-subtitle">
	//         Try again.
	//       </h3>';
	//     }
	//   }
	// }
	//
	// if(isset($_POST['group_id_1'])){
	//   $data['preference_1_group_id'] = $_POST['group_id_1'];
	// }
	//
	// if(isset($_POST['group_id_2'])){
	//   $data['preference_2_group_id'] = $_POST['group_id_2'];
	// }
	//
	// if(isset($_POST['group_id_3'])){
	//   $data['preference_3_group_id'] = $_POST['group_id_3'];
	// }
	//
	//
	// $check_entry = $sql->getOne('SELECT id FROM FAM_UserTask WHERE user_id='.$user_id.' AND year='.$year);
	// if($check_entry == ''){
	//   $inserted = $sql->insert("FAM_UserTask", $data);
	// }
	// else{
	//   $inserted = $sql->update("FAM_UserTask", $data,'id='.$check_entry);
	// }

}


if(!empty($applicants)){
	$id = $applicant['id'];
	$all_tasks = $fam->getTask($id,'all');

	if(!empty($all_tasks)){
		$all_tasks = $all_tasks[0];
	}
}

render();
