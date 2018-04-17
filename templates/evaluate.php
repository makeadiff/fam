<div class="x_panel">
	<div class="x_title">
		<h2>Evaluate <strong><?php echo $applicant['name'] ?></strong>'s <?php echo $stage_name['name'] ?><?php if($group_id) echo "(" . $verticals[$group_id] . ")"; ?></h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<p class="text-muted font-13 m-b-30">
			City: <?php echo $applicant['city'] ?><br />
			<?php if($reference_link) { ?>For more details on the scale used in this evaluation, refer to <a target="_blank" href="<?php echo $reference_link ?>">this document</a>.<?php } ?>

			<?php
			if($stage_id == 3) {
				$task_url = $fam->getTask($applicant_id, 'common');
				if($task_url) {
					echo "<h4>Click on the link(s) below to see $applicant[name]'s Common Task </h4>";
					$task = explode('http',str_replace(', ','',$task_url));
					$i=0;
					foreach ($task as $file) {
						if($file!=''){
							$i++;
							echo '<a target="_blank" class="badge badge-info" href="http'.$file.'">'.'Common Task '.$i.'</a>';
						}
					}

				}
				else{
					echo "<h4 class='alert alert-warning'> $applicant[name] <strong>hasn't updated</strong> Common Task yet</h4>";
				}
			}

			if($stage_id == 5) {
				$task_url = $fam->getTask($applicant_id, 'vertical', $group_id);
				if($task_url){
					echo "<h4>Click on the link(s) below to see $applicant[name]'s $verticals[$group_id] Task </h4>";
					$task = explode('http',str_replace(', ','',$task_url));
					$i=0;
					foreach ($task as $file) {
						if($file!=''){
							$i++;
							echo '<a target="_blank" class="badge badge-info" href="http'.$file.'">'.$verticals[$group_id].' Task '.$i.'</a>';
						}
					}
					// echo "<h4><a href='$task_url'>View $applicant[name]'s Vertical Task</a></h4>";
				}
				else{
					echo "<h4 class='alert alert-warning'> $applicant[name] <strong>hasn't updated</strong> Vertical Tasks yet</h4>";
				}

				$task_video_url = $fam->getTask($applicant_id, 'vertical_video_task', $group_id);
				if($task_video_url) echo "<h4><a href='$task_url'>View $applicant[name]'s Vertical Task Video</a></h4>";
			}
			?>
		</p>

<div class="message-area" id="error-message" <?php echo ($QUERY['error']) ? '':'style="display:none;"';?>><?php
	if(!empty($PARAM['error'])) print strip_tags($PARAM['error']); //It comes from the URL
	else print $QUERY['error']; //Its set in the code(validation error or something).
?></div>
<div class="message-area" id="success-message" <?php echo ($QUERY['success']) ? '':'style="display:none;"';?>><?php echo strip_tags(stripslashes($QUERY['success']))?></div>
	</div>
</div>

<?php if($stage_id == 1) { ?>
<div class="x_panel">
	<div class="x_title">
		<h2>Collated Scores</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<p class="text-muted">This section shows the total score of the applicant - you cannot interact with it directly</p>
		<script type="text/javascript">
			var counts = {};
		</script>
		<table>
		<?php
		$parameters = $fam->getParameters($stage_id, reset($categories)['id']);
		foreach($parameters as $para) { ?>
			<script type="text/javascript">
				counts['<?php echo unformat($para['name']) ?>'] = {
					'yes': 0,
					'no': 0,
					'na': 0,
				};
			</script>
			<tr><td width="300"><?php echo $para['name']; ?></td>
				<td>
				<div class="btn-group" data-toggle="buttons">
				    <label class="btn btn-success" disabled>Yes - <span id="<?php echo unformat($para['name']) ?>-yes-count"></span></label>
				    <label class="btn btn-danger" disabled>No - <span id="<?php echo unformat($para['name']) ?>-no-count"></span></label>
				    <label class="btn btn-dark" disabled>N/A - <span id="<?php echo unformat($para['name']) ?>-na-count"></span></label>
				</div>
			</td></tr>
		<?php } ?>
		</table>
	</div>
</div>
<?php } elseif ($stage_id == 2) {
	require 'templates/partials/applicant_feedback.php';
} ?>

<?php foreach($categories as $category) {
$parameters = $fam->getParameters($stage_id, $category['id']);

if(!$parameters) continue;
?>
<form action="" method="post" class="ajaxify">
<div class="x_panel">

<div class="x_title">
<h2><?php echo $category['name'] ?></h2>
<div class="clearfix"></div>
</div>

<div class="x_content">
<?php
require(joinPath($config['site_folder'], 'templates', 'partials', 'parameters.php'));
?>
</div>

<div class="x-content">
	<input type="submit" class="btn btn-primary" value="Save" name="action" />
</div>
</div>
</form>
<?php } ?>


<form action="evaluate.php" method="post" class="ajaxify">
	<input type="hidden" name="applicant_id" value="<?php echo $applicant_id ?>" />
	<input type="hidden" name="stage_id" value="<?php echo $stage_id ?>" />
<div class="x_panel">

<div class="x_title">
<h2>Stage Status</h2>
<div class="clearfix"></div>

<div class="row" style="margin-left: 10px;">
  <div class="btn-group" data-toggle="buttons">
  	<label class="btn btn-dark <?php if($stage_info['status'] == 'pending') echo 'active'; ?>">
    	<input name="status" type="radio" class="input-na" value="pending" <?php if($stage_info['status'] == 'pending') echo 'checked="true"'; ?> />Pending</label>
    <label class="btn btn-success <?php if($stage_info['status'] == 'selected') echo 'active'; ?>">
    	<input name="status" type="radio" class="input-yes" value="selected" <?php if($stage_info['status'] == 'selected') echo 'checked="true"'; ?> />Selected</label>
    <label class="btn btn-primary <?php if($stage_info['status'] == 'free-pool') echo 'active'; ?>" title="Your Vertical doesn't need this applicant - but other verticals can take them">
    	<input name="status" type="radio" class="input-no" value="free-pool" <?php if($stage_info['status'] == 'free-pool') echo 'checked="true"'; ?> />Free Pool</label>
    <label class="btn btn-warning <?php if($stage_info['status'] == 'maybe') echo 'active'; ?>">
    	<input name="status" type="radio" class="input-no" value="maybe" <?php if($stage_info['status'] == 'maybe') echo 'checked="true"'; ?> />Maybe</label>
    <label class="btn btn-danger <?php if($stage_info['status'] == 'rejected') echo 'active'; ?>">
    	<input name="status" type="radio" class="input-no" value="rejected" <?php if($stage_info['status'] == 'rejected') echo 'checked="true"'; ?> />Rejected</label>

  </div>
</div>
</div>

<div class="x_content">
	<div class="col-md-7">
		<label>Comments</label>
		<textarea rows="3" cols="50" name="comment" class="form-control col-md-7 col-xs-12"><?php echo $stage_info['comment'] ?></textarea>
	</div>
	<br /><br />
	<input type="submit" class="btn btn-primary" value="Save" name="action" />
</div>
</div>
</form>
