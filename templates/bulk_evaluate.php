<div class="x_panel">
	<div class="x_title">
		<h2>Evaluate <?php echo $stage_name['name'] ?> &gt; <?php echo $category_name['name'] ?></h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<p class="text-muted font-13 m-b-30">Evaluate all your applicants together here for any given day...</p>
		
<div class="message-area" id="error-message" <?php echo ($QUERY['error']) ? '':'style="display:none;"';?>><?php
	if(!empty($PARAM['error'])) print strip_tags($PARAM['error']); //It comes from the URL
	else print $QUERY['error']; //Its set in the code(validation error or something).
?></div>
<div class="message-area" id="success-message" <?php echo ($QUERY['success']) ? '':'style="display:none;"';?>><?php echo strip_tags(stripslashes($QUERY['success']))?></div>
	</div>
</div>

<?php 
foreach($applicants as $applicant_id) { 
	$applicant = $common->getUser($applicant_id);
?>
<form action="" method="post" class="ajaxify">
<input type="hidden" name="applicant_id" value="<?php echo $applicant['id'] ?>" />

<div class="x_panel">
<div class="x_title">
<h2><?php echo $applicant['name'] ?></h2>
<div class="clearfix"></div>
</div>

<div class="x_content">
<?php
	// dump($config);
	require(joinPath($config['site_folder'], 'templates', 'partials', 'parameters.php'));
?>
</div>

<div class="x-content">
	<input type="submit" class="btn btn-primary" value="Save" name="action" />
</div>
</div>
</form>
<?php } ?>
