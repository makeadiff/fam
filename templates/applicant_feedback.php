<div class="x_panel">
	<div class="x_title">
		<h2>Applicants with Feedback</h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<ul>
		<?php foreach($applicants_with_feedback as $app) { ?>
			<li><a href="evaluate.php?stage_id=2&applicant_id=<?php echo $app['id'] ?>"><?php echo $app['name'] ?></a></li>
		<?php } ?>
		</ul>
	</div>
</div>
