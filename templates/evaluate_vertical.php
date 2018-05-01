<div class="x_panel">

<div class="x_title">
<h2>Vertical Evaluation of <?php echo $applicant['name'] ?></h2>
<div class="clearfix"></div>
</div>


<div class="x_content">
	<h4>Select Vertical that you wish to evaluate...</h4>

	<ol>
	<?php
	foreach ($applications as $application) {
		extract($application);
		echo "<li><a href='evaluate.php?stage_id=$stage_id&group_id=$group_id&applicant_id=$applicant_id'>$verticals[$group_id]</a></li>";
	}
	?>
	</ol>
</div>

</div>
