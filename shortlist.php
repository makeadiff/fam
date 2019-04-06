<?php

require 'common.php';

$referer_url = $_SERVER['HTTP_REFERER'];

$applicant_id = i($QUERY,'applicant_id',0);
$group_id = i($QUERY,'group_id',0);
$city_id = 0;

$applicant = $fam->getApplications($applicant_id);

// Rejecting Applicant for Non Mentor Profile
foreach ($applicant as $preference) {
  if($preference['group_id']!=$group_id && $preference['preference']!='1'){
    $city_id = $preference['city_id'];
    $update = $fam->setSelectionStatus($applicant_id,$preference['group_id'],'rejected');
  }
}

if($city_id==0){
  $city_id = $sql->getOne("SELECT city_id FROM User where id=$applicant_id");
}
$check_mentor = $sql->getOne("SELECT id FROM FAM_UserGroupPreference WHERE user_id=$applicant_id AND group_id=$group_id AND year=$year AND status<>'rejected'");

if($check_mentor==''){
  $insert_application = $fam->addApplicant($applicant_id,$group_id,'1',$city_id);
  header('location: '.$referer_url.'&success=Updated');
}
else{
  header('location: '.$referer_url);
}
