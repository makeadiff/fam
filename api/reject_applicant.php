<?php

require '../common.php';

$applicant_id = i($QUERY,'applicant_id',0);
$group_id = i($QUERY,'group_id',0);
$applicant = $fam->getApplications($applicant_id);

foreach ($applicant as $preference) {
  if($preference['group_id']==$group_id && $preference['preference']=='1'){
    // No Code
  }else{
    $update = $fam->setSelectionStatus($applicant_id,$preference['group_id'],'rejected');
  }

}
echo json_encode(['status' => 'success']);
