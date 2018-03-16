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

<?php foreach($categories as $category) { 
$parameters = $fam->getParameters($stage_id, $category['id']);

if(!$parameters) continue;
?>
<form action="" method="post">
<div class="x_panel">

<div class="x_title">
<h2><?php echo $category['name'] ?></h2>
<div class="clearfix"></div>
</div>

<div class="x_content">
<?php
foreach($parameters as $para) { 
	$response = $fam->getResponse($applicant_id, $para['id']);
	?>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3" ><?php echo $para['name'] ?>
			<?php if($para['required']) { ?><span class="required">*</span><?php } ?></label>
		<?php if($para['type'] == 'yes-no') { ?>
		<div class="row">
		  <div class="btn-group" data-toggle="buttons">
		    <label class="btn btn-success <?php if($response == '1') echo 'active'; ?>">
		    	<input name="response[<?php echo $para['id'] ?>]" type="radio"
		    	value="1" <?php if($response == '1') echo 'checked="true"'; ?>>Yes</label>
		    <label class="btn btn-danger <?php if($response == '0') echo 'active'; ?>">
		    	<input name="response[<?php echo $para['id'] ?>]" type="radio" 
		    	value="0" <?php if($response == '0') echo 'checked="true"'; ?>>No</label>
		    <label class="btn btn-dark <?php if($response == '-1') echo 'active'; ?>">
		    	<input name="response[<?php echo $para['id'] ?>]" type="radio" 
		    	value="-1" <?php if($response == '-1') echo 'checked="true"'; ?>>N/A</label>
		  </div>
		</div>
		<?php } elseif($para['type'] == '1-5') { ?>
		<div class="row">
		  <div class="btn-group" data-toggle="buttons">
		    <label class="btn btn-danger <?php if($response == '1') echo 'active'; ?>">
		    	<input name="response[<?php echo $para['id'] ?>]" type="radio"
		    	value="1" <?php if($response == '1') echo 'checked="true"'; ?>>1</label>

		    <label class="btn btn-warning <?php if($response == '2') echo 'active'; ?>">
		    	<input name="response[<?php echo $para['id'] ?>]" type="radio" 
		    	value="2" <?php if($response == '2') echo 'checked="true"'; ?>>2</label>

		    <label class="btn btn-dark <?php if($response == '3') echo 'active'; ?>">
		    	<input name="response[<?php echo $para['id'] ?>]" type="radio" 
		    	value="3" <?php if($response == '3') echo 'checked="true"'; ?>>3</label>

		    <label class="btn btn-info <?php if($response == '4') echo 'active'; ?>">
		    	<input name="response[<?php echo $para['id'] ?>]" type="radio" 
		    	value="4" <?php if($response == '4') echo 'checked="true"'; ?>>4</label>

		    <label class="btn btn-primary <?php if($response == '5') echo 'active'; ?>">
		    	<input name="response[<?php echo $para['id'] ?>]" type="radio" 
		    	value="5" <?php if($response == '5') echo 'checked="true"'; ?>>5</label>
		  </div>
		</div>

		<?php } elseif($para['type'] == 'text') { ?>
			<div class="col-md-7">
				<textarea rows="3" cols="50" name="response[<?php echo $para['id'] ?>]" class="form-control col-md-7 col-xs-12"
					<?php if($para['required']) echo 'required="required"'; ?>><?php echo $response ?></textarea>
			</div>
		<?php } ?>
	</div>
<?php } ?>
</div>

<div class="x-content">
	<button class="btn btn-primary" value="Save" name="action">Save</button>
</div>
</div>
</form>
<?php } ?>

