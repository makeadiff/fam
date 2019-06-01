<?php
require '../common.php';
/// Purpose : Get a CSV list of all the people shortlisted for mentor profile. 

// Find all people who have been marked as rejected
$rejected = $sql->getAll("SELECT DISTINCT U.id, U.name, U.email,GROUP_CONCAT(UGP.status) AS ugp_status FROM User U 
								INNER JOIN FAM_UserGroupPreference UGP ON U.id=UGP.user_id
								WHERE UGP.year=$year AND UGP.status='rejected' AND U.user_type='volunteer' AND U.status='1'
								GROUP BY UGP.user_id"); // Add the ' AND UGP.group_id=8' to the where part to get only people who have applied for the mentor profile.

$shortlist = [];

foreach ($rejected as $row) {
	if(str_replace(['rejected',','], '', $row['ugp_status'])) { // Some preferences were not rejected. Ignore this row.
		continue;
	}
	unset($row['ugp_status']);
	$shortlist[] = $row;
}

print array2csv($shortlist);
// print count($rejected) . ":" . count($shortlist) . "\n";
