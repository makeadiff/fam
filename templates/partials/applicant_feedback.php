<?php
$feedback = $fam->getApplicantFeedback($applicant_id);
$all_questions = $fam->getApplicantFeedbackQuestions();

/* 
// Reviewer focused feedback
foreach ($feedback as $reviewer_id => $fb) { ?>
<div class="x_panel">
	<div class="x_title">
		<h2>Feedback <?php 
						if($is_director) echo ' by ' . $common->getUserName($reviewer_id); 
						else echo '#' . $count;
					?></h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<dl>
		<?php foreach ($fb as $row) { 
				if(!trim($row['feedback'])) continue;
			?>
			<dt><?php echo $all_questions[$row['question_id']]['question']	; ?></dt>
			<dd><?php echo $row['feedback']; ?></dd>
		<?php } ?>
		</dl>
	</div>
</div>
<?php } */

// Question focused feedback
foreach ($all_questions as $question_id => $ques) { ?>
<div class="x_panel">
	<div class="x_title">
		<h2><?php echo $ques['question']; ?></h2>
		<div class="clearfix"></div>
	</div>

	<div class="x_content">
		<dl>
		<?php 
		$count = 1;
		foreach ($feedback as $reviewer_id => $responses)  {
			foreach ($responses as $row) {
				if($row['question_id'] == $question_id) {
					if(!trim($row['feedback'])) continue;
				?>
					<dt>Feedback <?php if($is_director) echo 'by ' . $common->getUserName($reviewer_id); 
										else echo '#' . $count; ?></dt>
					<dd><?php echo $row['feedback'] ?></dd>
		<?php 	}
			}
			$count++;
		} ?>
		</dl>
	</div>
</div>
<?php
}