<?php

require '../common.php';

$applicant_id = i($QUERY,'applicant_id',0);
$group_id = i($QUERY,'group_id',0);
$city_id = 0;

$applicant = $fam->getApplications($applicant_id);

foreach ($applicant as $preference) {
  if($preference['group_id']!=$group_id && $preference['preference']!='1'){
    $city_id = $preference['city_id'];
    $update = $fam->setSelectionStatus($applicant_id,$preference['group_id'],'rejected');
  }
}
