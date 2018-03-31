<?php
require 'common.php';

$applicants_with_feedback = $sql->getAll("SELECT DISTINCT U.id, U.name 
	FROM User U 
	INNER JOIN FAM_ApplicantFeedback AF ON AF.applicant_user_id=U.id
	WHERE U.status='1' AND U.user_type='volunteer'");

render();
