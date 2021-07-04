<?php
require '../common.php';

$crud = new Crud("FAM_Parameter");

$vertical_categories = $sql->getAll("SELECT C.id,C.group_id,C.name, G.name AS gname FROM FAM_Parameter_Category C LEFT JOIN `Group` G ON G.id=C.group_id WHERE C.status='1'");

$all_categories = ['All'];
$all_groups = ['All'];
foreach ($vertical_categories as $cat) {
	if(trim($cat['gname'])) {
		$all_categories[$cat['id']] = $cat['gname'] . ' : ' . $cat['name'];
	} else {
		$all_categories[$cat['id']] = $cat['name'];
	}
	if(!isset($all_groups[$cat['group_id']])) {
		$all_groups[$cat['group_id']] = $cat['gname'];
	}
}

$filter = ['1=1'];
if(i($QUERY, 'group_id')) {
	$allowed = [];
	foreach($vertical_categories as $cat) {
		if($cat['group_id'] == $QUERY['group_id']) {
			$allowed[] = $cat['id'];
		}
	}

	if(count($allowed) > 0) {
		$filter[] = 'P.category_id IN ('.implode(',', $allowed).')';
	}
}

$crud->addListDataField("stage_id", 'FAM_Stage');
$crud->addField("category_id", 'Category', 'enum', [], $all_categories);
$crud->addField("type", 'Type', 'enum', array(), ['yes-no' => 'Yes/No', '1-5' => '1-5', 'text' => 'Text']);
$crud->setListingQuery("SELECT P.* FROM FAM_Parameter P 
							INNER JOIN FAM_Parameter_Category C ON C.id=P.category_id
							WHERE C.status='1' AND P.status='1' AND " . implode(" AND ", $filter));

$group_filters = $all_groups;
array_walk($group_filters, function(&$group_name, $group_id) {
	$group_name = "<a href='?group_id=$group_id'>$group_name</a>";
});
$crud->code['top'] = "<br /><br /><br />Filter By: " . implode(", ", array_values($group_filters));

$crud->render();