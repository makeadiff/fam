<?php

require '../common.php';

$applicant_id = i($QUERY,'applicant_id',0);
$group_id = i($QUERY,'group_id',0);
$applicant = $fam->getApplications($applicant_id);

foreach ($applicant as $preference) {
  $status = $fam->getSelectionStatus($applicant_id,$preference['group_id']);
  if($status=='rejected'){
    $update = $fam->setSelectionStatus($applicant_id,$preference['group_id'],'pending');
  }

}
echo json_encode(['status' => 'success']);
