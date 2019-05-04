<div class="x_panel">

	<?php
		if(!$is_director) die("You don't have access to this view");
	?>
	
	<div class="x_title">
		<h2>Update Tasks for Applicant | Search Applicant</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<form action="task_update.php" method="get" class="form-horizontal form-label-left">

      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
				<input type="text" name="id" class="form-control has-feedback-left" placeholder="User ID" value="<?php echo i($QUERY, 'id'); ?>" />
				<span class="fa fa-male form-control-feedback left" aria-hidden="true"></span>
      </div>

			<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
				<input type="text" name="email" class="form-control has-feedback-left" placeholder="Email" value="<?php echo i($QUERY, 'email'); ?>" />
				<span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
      </div>

			<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
				<input type="text" name="phone" class="form-control has-feedback-left" placeholder="Phone" value="<?php echo i($QUERY, 'phone'); ?>" />
				<span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
      </div>

		<?php $html->buildInput("action", "&nbsp", 'submit', 'Find Applicant', ['class' => 'btn btn-primary']); ?>
		</form>
	</div>
</div>

<?php
	if($applicants) {
		if($all_tasks){
?>
		<div class="x_panel">
<?php
			if($update){
?>
			<div class="alert alert-success">Updated Successfully </div>
<?php
			}
?>
			<div class="x_title">
				<h2>Tasks for <?php echo $applicant['name'];?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form action="task_update.php" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data">
<?php
			if($all_tasks['common_task_url']){
?>
					<input type="hidden" name="id" class="form-control has-feedback-left" placeholder="User ID" value="<?php echo i($QUERY, 'id'); ?>" />
					<input type="hidden" name="email" class="form-control has-feedback-left" placeholder="Email" value="<?php echo i($QUERY, 'email'); ?>" />
					<input type="hidden" name="phone" class="form-control has-feedback-left" placeholder="Phone" value="<?php echo i($QUERY, 'phone'); ?>" />
					<input type="hidden" name="task_id" class="form-control has-feedback-left"  value="<?php echo i($all_tasks, 'id'); ?>" />

					<p>Common Task: Video URL</p>
					<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
						<input type="text" name="common_task_url" class="form-control has-feedback-left" placeholder="Common Task Video URL" value="<?php echo i($all_tasks, 'common_task_url',''); ?>" />
						<span class="fa fa-camera form-control-feedback left" aria-hidden="true"></span>
		      </div>

<?php
			}
?>
					<p>Common Task: Written Task Files</p>
<?php
			if($all_tasks['common_task_files']){

				$task = explode('http',str_replace(', ','',str_replace('#','%23',$all_tasks['common_task_files'])));
				$i=0;
?>
					<div class="row">
<?php
				foreach ($task as $file) {
					if($file!=''){
						$i++;
						$file = 'http'.$file;
						$file_name = basename($file);
?>
						<div class="col-md-2 col-sm-2 col-xs-2 no-overflow" id="task<?php echo $i; ?>">
							<a target="_blank" href="download.php?file=<?php echo $file?>"><h1><span class="fa fa-file" aria-hidden="true"></span><h1></a>
							<a target="_blank" href="download.php?file=<?php echo $file?>"><p><?php echo str_replace("_"," ",$file_name); ?></p></a>
							<a title="delete tasks" class="delete_task" id="<?php echo $i; ?>" href="api/delete_task.php?applicant_id=<?php echo $applicant['id'] ?>&file=<?php echo $file?>"><p class="icon"><span class="fa fa-trash" aria-hidden="true"></span></p></a>
			      </div>
<?php
					}
				}
?>
<?php
			}
?>
			</div>
			<div class="box__input">
				<input type="button" class="btn btn-warning" id="loadFileXml" value="Select File(s)" onclick="document.getElementById('common_task_files').click();" />
				<span class="file_name_label" id="file_name_label_common"></span>
				<input type="file" id="common_task_files" name="common_task_files[]" class="hidden" multiple accept="application/msword,application/msexcel,application/pdf,application/rtf,image/pdf,image/jpeg,image/tiff,image/x-png,text/plain,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,.pdf, .xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx"/>
			</div>
<?php
			echo '<hr/>';
			$html->buildInput("action", "&nbsp", 'submit', 'Update Tasks', ['class' => 'btn btn-primary']);
?>

				</form>
			</div>
		</div>
<?php
		}else{
?>
		<div class="x_panel">
			<div class="x_title">
				<h2>No Tasks were Submitted by <?php echo $applicant['name']; ?></h2>
				<div class="clearfix"></div>
			</div>
		</div>
<?php
		}
	}
?>
