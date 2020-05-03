<?php

require '../common.php';

$applicant_id = i($QUERY,'applicant_id',0);
$group_id = i($QUERY,'group_id',0);
$applicant = $fam->getApplications($applicant_id);
$city_id = 0;

$is_mentor_applicant = false;

foreach ($applicant as $preference) {
  if($preference['group_id']==$group_id && $preference['preference']=='1'){
    // No Code
  }else{
    $update = $fam->setSelectionStatus($applicant_id,$preference['group_id'],'rejected');
  }

  if($preference['group_id']==$group_id && $preference['preference']!='1'){
    $is_mentor_applicant = true;
    $city_id = $preference['city_id'];
  }
}

// if($is_mentor_applicant){
$insert_fam = $fam->addApplicant($applicant_id,$group_id,1,$city_id);
// }

echo json_encode(['status' => 'success']);
