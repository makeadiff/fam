<?php
exit; // No idea what script does, so don't run online.
session_start();
$_SESSION['user_id'] = 1;
require '../common.php';

$category_id = i($QUERY, 'category_id', 0);

$categories = $sql->getAll("SELECT id, group_id, name FROM FAM_Parameter_Category WHERE stage_id=5");
$all_categories = ['None'];
foreach ($categories as $cat) {
	$all_categories[$cat['id']] = $verticals[$cat['group_id']] . ':' . $cat['name'];
}

$insert_count = 0;

if(i($QUERY, 'action') == 'Add' and $category_id) {
	$parameters = explode("\n", i($QUERY, 'parameters'));//['Understanding/Real world modelling of the project', 'Ideation and creativity', 'Presentation, confidence and pitching skills'];

	$count = 1;
	foreach ($parameters as $para) {
		$insert_id = $sql->insert("FAM_Parameter", [
			'stage_id' 		=> 5,
			'category_id'	=> $category_id,
			'name'			=> trim($para),
			'type'			=> '1-5',
			'required'		=> 1,
			'sort'			=> 10 * $count,
			'status'		=> '1'
		]);
		if($insert_id) $insert_count++;
		$count++;
	}

	if($insert_count) $QUERY['success'] = 'Inserted ' . $insert_count . ' parameters into "' . $all_categories[$category_id] . '"';
}

showTop('Add Parameters');
?>

<h1>Add Parameters</h1>

<form action="" method="post" class="form-area">
<?php $html->buildInput("category_id", "Category", 'select', $category_id, array('options' => $all_categories)); ?>
<a href="categories.php?action=add&stage_id=5&status=1">Add new Category</a><br />
<strong>List all Parameters - seperated by new line...</strong><br />
<textarea name="parameters" rows="5" cols="50"></textarea><br />

<input type="submit" name="action" value="Add" class="btn btn-primary" />
</form>
<?php
showEnd();
