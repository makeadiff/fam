<div class="x_panel">
	<div class="x_title">
		<h2>Evaluate <strong><?php echo $applicant['name'] ?></strong>'s <?php echo $stage_name['name'] ?></h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<p class="text-muted font-13 m-b-30">City: <?php echo $applicant['city'] ?></p>
		
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
		Yes Count: <span id="yes-count"></span><br />
		No Count: <span id="no-count"></span><br />
		N/A Count: <span id="na-count"></span><br />
	</div>
</div>
<?php } ?>

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
    <label class="btn btn-success <?php if($stage_info['status'] == 'selected') echo 'active'; ?>">
    	<input name="status	" type="radio" class="input-yes" value="selected" <?php if($stage_info['status'] == 'selected') echo 'checked="true"'; ?> />Selected</label>
    <label class="btn btn-danger <?php if($stage_info['status'] == 'rejected') echo 'active'; ?>">
    	<input name="status" type="radio" class="input-no" value="rejected" <?php if($stage_info['status'] == 'rejected') echo 'checked="true"'; ?> />Rejected</label>
    <label class="btn btn-dark <?php if($stage_info['status'] == 'pending') echo 'active'; ?>">
    	<input name="status" type="radio" class="input-na" value="pending" <?php if($stage_info['status'] == 'pending') echo 'checked="true"'; ?> />Pending</label>
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
 