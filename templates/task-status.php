<div class="x_panel">

<div class="x_title">
<h2>Task Submission Status for Applicants</h2>
<div class="clearfix"></div>
</div>

<div class="x_content">
<form action="" method="get">
<?php 
$html->buildInput("group_id", 'Applicants for ', 'select', $group_id, ['options' => $all_groups, 'no_br' => 1]); echo '&nbsp;';
$html->buildInput("city_id", 'City ', 'select', $city_id, ['options' => $all_cities, 'no_br' => 1]); echo '&nbsp;';
$html->buildInput("stage_id", 'Stage ', 'select', $stage_id, ['options' => $all_stages_input]);
$html->buildInput("common_video_task", 'Common Video Task', 'select', $common_video_task, ['options' => $all_tasks_status, 'no_br' => 1]); echo '&nbsp;';
$html->buildInput("common_written_task", 'Common Written Task', 'select', $common_written_task, ['options' => $all_tasks_status, 'no_br' => 1]); echo '&nbsp;';
$html->buildInput("vertical_task", 'Vertical Task', 'select', $vertical_task, ['options' => $all_tasks_status, 'no_br' => 1]); echo '&nbsp;';
?>
<button class="btn btn-success btn-sm" value="Filter" name="action">Filter</button>
</form>

<?php
$count = ($applicants_pager->page - 1) * $applicants_pager->items_per_page;
require 'templates/partials/applicants_table.php'; 

if(isset($QUERY['city_id'])) $applicants_pager->opt['parameters']['city_id'] = $QUERY['city_id'];
if(isset($QUERY['group_id'])) $applicants_pager->opt['parameters']['group_id'] = $QUERY['group_id'];
$applicants_pager->link_template = '<a href="%%PAGE_LINK%%" class="page-%%CLASS%%">%%TEXT%%</a>';
if($applicants_pager->total_pages > 1) {
	print $applicants_pager->getLink("first") . $applicants_pager->getLink("back");
	$applicants_pager->printPager();
	print $applicants_pager->getLink("next") . $applicants_pager->getLink("last") . '<br />';
}
if($applicants_pager->total_items) print $applicants_pager->getStatus();

?>
</div>
</div>
