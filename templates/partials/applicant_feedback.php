<?php
$feedback = $fam->getApplicantFeedback($applicant_id);
$all_questions = $fam->getApplicantFeedbackQuestions();

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
					if(!trim($row['feedback'])){
						if($row['feedback']==0){
							$row['feedback'] = 'Cannot Say';
						}
					}
				?>
					<dt>Feedback <?php if($is_director) echo 'by ' . $common->getUserName($reviewer_id);
										else echo '#' . $count; ?></dt>
					<dd><?php
						echo $row['feedback'];
						if(i($row, 'comment'))
							echo ' (' . $row['comment'] . ')';
					?></dd>
		<?php 	}
			}
			$count++;
		} ?>
		</dl>
	</div>
</div>
<?php
}
