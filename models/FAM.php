<?php
/// Common model. Includes all the neccessay data interactions. Not enough to make seperate models yet.
class FAM {
	private $sql;
	private $year;

	function __construct() {
       global $sql, $year;

       $this->sql = $sql;
			 $this->year = $year;			 
	}

	public function addApplicant($user_id,$group_id,$preference,$city_id=0)
	{
		$check_entry = $this->sql->getOne("SELECT id FROM FAM_UserGroupPreference WHERE user_id=$user_id AND preference=$preference AND group_id=$group_id AND year=$this->year AND status<>'withdrawn' AND status<>'rejected'");

		if(!$check_entry){
			return $this->sql->insert('FAM_UserGroupPreference',array(
					'user_id'		=> $user_id,
					'group_id' 		=> $group_id,
					'evaluator_id'	=> 0,
					'preference'	=> $preference,
					'city_id'		=> $city_id,
					'added_on'		=> 'NOW()',
					'year'			=> $this->year,
					'taskfolder_link' => '',
					'status'		=> 'pending'
			));
		}
		else{
			return false;
		}

	}

	public function getSelectionStatus($user_id,$group_id = false)
	{
		if($group_id)
			return $this->sql->getOne("SELECT status FROM FAM_UserGroupPreference WHERE user_id=$user_id AND status<>'withdrawn' AND  group_id=$group_id AND year={$this->year}");
		else
			return $this->sql->getOne("SELECT status FROM FAM_UserGroupPreference WHERE user_id=$user_id AND status<>'withdrawn' AND year={$this->year}");
	}

	public function setSelectionStatus($user_id, $group_id, $status)
	{
		return $this->sql->execQuery("UPDATE FAM_UserGroupPreference SET status='$status' WHERE user_id=$user_id AND group_id=$group_id AND year={$this->year}");
	}

	public function getStage($stage_id)
	{
		return $this->sql->getAssoc("SELECT * FROM FAM_Stage WHERE id=$stage_id");
	}
	public function getStages()
	{
		return $this->sql->getAll("SELECT * FROM FAM_Stage WHERE name!='Done' AND status='1'");
	}

	public function getCategory($category_id)
	{
		return $this->sql->getAssoc("SELECT * FROM FAM_Parameter_Category WHERE id=$category_id");
	}

	public function getCategories($stage_id, $group_id=0)
	{
		$condition='';
		if($group_id!=0){
			$condition = ' AND group_id='.$group_id;
		}
		return $this->sql->getAll("SELECT id, name FROM FAM_Parameter_Category WHERE stage_id=$stage_id AND status='1'".$condition);
	}

	public function getParameters($stage_id, $category_id = 0)
	{
		return $this->sql->getAll("SELECT * FROM FAM_Parameter WHERE stage_id=$stage_id AND status='1' AND category_id=$category_id ORDER BY sort ASC");
	}

	public function getGroups($vertical_id = 0)
	{
		$vertical_check = '';
		if($vertical_id) $vertical_check = "vertical_id=$vertical_id AND";
		return $this->sql->getAll("SELECT id, name FROM `Group`
			WHERE $vertical_check status='1' AND group_type='normal' AND (type='fellow' OR type='strat' OR type='volunteer')");
	}

	public function saveEvaluation($data)
	{
		// Clear existing evaluations, if any.
		$this->sql->remove("FAM_Evaluation", [
			'user_id'		=> $data['applicant_id'],
			'parameter_id'	=> $data['parameter_id'],
			'year'			=> $this->year
		]);

		$this->sql->insert('FAM_Evaluation', [
			'user_id'		=> $data['applicant_id'],
			'parameter_id'	=> $data['parameter_id'],
			'evaluator_id'	=> $data['evaluator_id'],
			'response'		=> $data['response'],
			'added_on'		=> 'NOW()',
			'year'			=> $this->year,
		]);
	}

	public function resetAssignments($evaluator_id, $amount_users)
	{
		$this->sql->execQuery("DELETE FROM FAM_UserEvaluator WHERE year={$this->year} AND evaluator_id=$evaluator_id AND user_id IN (" . implode(',', $amount_users) . ")");
	}

	public function assignEvaluators($user_id, $evaluator_id, $group_id)
	{
		$this->sql->insert("FAM_UserEvaluator", [
			'user_id'		=> $user_id,
			'evaluator_id'	=> $evaluator_id,
			'group_id'		=> $group_id,
			'year'			=> $this->year
		]);
	}

	public function getStageStatus($user_id, $stage_id, $group_id=0)
	{		
		$q = "SELECT * FROM FAM_UserStage WHERE user_id=$user_id AND stage_id=$stage_id AND year={$this->year}";
		if($group_id!=0){
			$q .= " AND group_id=$group_id";
		}
		$q .= " ORDER BY FIELD(status,'selected','free-pool','rejected','maybe','pending')";
		$stage = $this->sql->getAssoc($q);
		if(!$stage) $stage = ['status' => 'pending', 'comment' => ''];

		return $stage;
	}

	public function getStageApplicantSelectedInfo($stage_id, $city_id = 0, $group_id = 0){

		$q = 'SELECT US.*
		 			FROM FAM_UserStage as US
					INNER JOIN FAM_UserGroupPreference as UGP ON UGP.user_id = US.user_id
					WHERE UGP.year = '.$this->year.'
						AND US.year = '.$this->year.'
						AND US.stage_id ='.$stage_id.'
						AND US.status = "selected"';

		return $this->sql->getAll($q);
	}

	public function saveStageStatus($data)
	{
		$existing = $this->getStageStatus($data['user_id'], $data['stage_id'],$data['group_id']);
		$data['year'] = $this->year;

		if(!isset($existing['id'])) $this->sql->insert("FAM_UserStage", $data);
		else $this->sql->update("FAM_UserStage", [
				'comment'		=> $data['comment'],
				'status'		=> $data['status'],
				'evaluator_id'	=> $data['evaluator_id'],
				'year'			=> $this->year
			], ['id' => $existing['id']]);
	}

	public function getUnassignedApplicants()
	{
		return $this->sql->getAll("SELECT U.id, U.name, U.phone, U.email, GROUP_CONCAT(UGP.group_id ORDER BY UGP.preference SEPARATOR ',') AS `groups`,
											UGP.preference, UGP.status, UGP.id AS ugp_id, C.name AS city
										FROM FAM_UserGroupPreference UGP
										INNER JOIN User U ON U.id=UGP.user_id
										LEFT JOIN FAM_UserEvaluator UE ON UE.user_id=U.id
										INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
										WHERE UE.evaluator_id IS NULL AND U.status='1' AND UGP.year={$this->year} AND UE.year={$this->year}
											AND (U.user_type='volunteer' OR U.user_type='alumni') AND UGP.status != 'withdrawn'
										GROUP BY UGP.user_id");
	}

	public function getUser($user_id){
		return $this->sql->getAssoc("SELECT * FROM User WHERE id=".$user_id);
	}

	public function isRejected($user_id){
		$status = $this->sql->getOne('SELECT * FROM FAM_UserGroupPreference WHERE user_id='.$user_id.' AND year='.$this->year.' AND status="rejected"');
		if($status){
			return true;
		}
		else{
			return false;
		}
	}

	public function getApplicants($source) {
		$checks = ['1=1'];
		$join = '';
		$selects = '';
		if(!empty($source['group_id'])) $checks[] = "UGP.group_id=" . $source['group_id'];
		if(!empty($source['preference']) and $source['preference']) $checks[] = "UGP.preference=" . $source['preference'];
		if(!empty($source['city_id'])) $checks[] = "((UGP.city_id != 0 AND UGP.city_id={$source['city_id']}) OR (UGP.city_id = 0 AND U.city_id={$source['city_id']}))";
		if(isset($source['evaluator_id'])) {
			if(!$source['evaluator_id']) return [];
	 		$checks[] = "UE.evaluator_id=" . $source['evaluator_id'];
	 		$checks[] = 'UE.year='.$this->year;
	 		$join .= "INNER JOIN FAM_UserEvaluator UE ON U.id=UE.user_id";
	 	}
		if(isset($source['stage_id']) && $source['stage_id']!=0){
			$selects .= ', US.status, US.stage_id';
			$join .= 'LEFT JOIN FAM_UserStage US ON US.user_id = U.id';
			$checks[] = 'US.stage_id='.$source['stage_id'];
			$checks[] = 'US.year='.$this->year;
			if(isset($source['status']) && $source['status']!='0'){
				$checks[] = 'US.status="'.$source['status'].'"';
			}
		}

	 	$query = "SELECT U.id, U.name, U.email, U.mad_email, U.phone, GROUP_CONCAT(UGP.group_id ORDER BY UGP.preference SEPARATOR ',') AS `groups`,
	 					UGP.preference, C.name AS city, UGP.id AS ugp_id, UGP.status as status, UT.common_task_files AS achivement_record $selects
					FROM User U
					INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id=U.id
					INNER JOIN FAM_UserTask UT ON UT.user_id=U.id
					$join
					INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
					WHERE " . implode(" AND ", $checks) . " AND UGP.status != 'withdrawn' AND UGP.year={$this->year}
					GROUP BY UGP.user_id
					ORDER BY C.name, U.name";


		return $this->sql->getAll($query);
	}

	public function getEvaluators($applicant_id, $group_id)
	{
		return $this->sql->getAll("SELECT U.id, U.name
				FROM User U
				INNER JOIN FAM_UserEvaluator E ON E.evaluator_id=U.id
				WHERE E.user_id=$applicant_id AND E.group_id=$group_id AND U.status='1' AND U.user_type='volunteer' AND E.year={$this->year}");
	}

	public function getEvaluatorsByGroup($applicant_id)
	{
		return $this->sql->getAll("SELECT E.id, E.group_id, U.name
				FROM User U
				INNER JOIN FAM_UserEvaluator E ON E.evaluator_id=U.id
				WHERE E.user_id=$applicant_id AND U.status='1' AND U.user_type='volunteer' AND E.year={$this->year}");
	}

	public function getApplicationInfo($applicant_id)
	{
		return $this->sql->getAll("SELECT US.group_id, U.name AS evaluator, US.status AS user_stage_status, US.stage_id
				FROM User U
				LEFT JOIN FAM_UserStage US ON US.evaluator_id=U.id
				WHERE US.user_id=$applicant_id AND U.status='1' AND U.user_type='volunteer' AND US.year={$this->year}");
	}

	public function getResponse($applicant_id, $parameter_id)
	{
		return $this->sql->getOne("SELECT response FROM FAM_Evaluation WHERE user_id=$applicant_id AND parameter_id=$parameter_id AND year={$this->year}");
	}

	public function getApplications($applicant_id,$status=false)
	{
		if(!$status){
			return $this->sql->getAll("SELECT preference, group_id, city_id FROM FAM_UserGroupPreference WHERE user_id=$applicant_id AND year={$this->year} AND status!='withdrawn'");
		}
		else{
			return $this->sql->getAll("SELECT preference, group_id, city_id FROM FAM_UserGroupPreference WHERE user_id=$applicant_id AND year={$this->year} AND status='$status'");
		}
	}

	public function getTask($applicant_id, $type = 'common', $group_id = 0)
	{
		if($type == 'common') {
			return $this->sql->getOne("SELECT common_task_url FROM FAM_UserTask WHERE user_id=$applicant_id AND year={$this->year}");
		} elseif($type == 'vertical') {
			return $this->sql->getOne("SELECT CASE $group_id
												WHEN preference_1_group_id THEN preference_1_task_files
												WHEN preference_2_group_id THEN preference_2_task_files
												WHEN preference_3_group_id THEN preference_3_task_files
												ELSE ''
												END
											FROM FAM_UserTask WHERE user_id=$applicant_id AND year={$this->year}");
		} elseif($type == 'vertical_video_task') {
			return $this->sql->getOne("SELECT CASE $group_id
												WHEN preference_1_group_id THEN preference_1_video_files
												WHEN preference_2_group_id THEN preference_2_video_files
												WHEN preference_3_group_id THEN preference_3_video_files
												ELSE ''
												END
											FROM FAM_UserTask WHERE user_id=$applicant_id AND year={$this->year}");
		} elseif($type == 'common_task_file') {
			return $this->sql->getOne("SELECT common_task_files FROM FAM_UserTask WHERE user_id=$applicant_id AND year={$this->year}");
		} elseif($type == 'all') {
			return $this->sql->getAll("SELECT * FROM FAM_UserTask WHERE user_id=$applicant_id AND year={$this->year}");
		}

		return false;
	}

	public function findUser($parameters, $and_or = ' AND ', $include_application_info = false)
	{
		global $year;
		$checks = [];
		foreach ($parameters as $field => $value) {
			if($field == 'name') {
				$checks[] = "U.`$field` LIKE '%" . $this->sql->escape($value) . "%'";
			} else {
				$checks[] = "U.`$field` = '" . $this->sql->escape($value) . "'";
			}
		}

		$query = "SELECT id,name,email,mad_email,phone,user_type FROM User U WHERE (user_type='volunteer' OR user_type='alumni') AND status='1'";
		if($checks) $query .= " AND (" . implode($and_or, $checks) . ")";

		if($include_application_info) {
			$query = "SELECT U.id, U.name, U.email, U.mad_email, U.phone, GROUP_CONCAT(DISTINCT UGP.group_id ORDER BY UGP.preference SEPARATOR ',') AS `groups`,
								C.name AS city,U.city_id, UGP.preference, UGP.id AS ugp_id, E.name AS evaluator, UGP.status as status, 
								UT.common_task_files AS achivement_record 
						FROM User U
						INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id=U.id
						INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
						LEFT JOIN FAM_UserEvaluator UE ON U.id=UE.user_id
						LEFT JOIN FAM_UserTask UT ON UT.user_id=U.id
						LEFT JOIN User E ON E.id=UE.evaluator_id
						INNER JOIN `Group` G ON UGP.group_id=G.id
						WHERE UGP.status != 'withdrawn' AND UGP.year=$year ";
			if($checks) $query .= " AND (" . implode($and_or, $checks) . ") ";
			$query .= "GROUP BY UGP.user_id";
		}

		return $this->sql->getAll($query);
	}

	public function findAllUsers($parameters, $and_or = ' AND '){
		global $year;

		$checks = [];
		foreach ($parameters as $field => $value) {
			if($field == 'name') {
				$checks[] = "U.`$field` LIKE '%" . $this->sql->escape($value) . "%'";
			} else {
				$checks[] = "U.`$field` = '" . $this->sql->escape($value) . "'";
			}
		}

		$query = "SELECT U.id as id,U.name as name,U.phone as phone,U.email as email,U.mad_email as mad_email,U.user_type as user_type,C.name as city 
					FROM User U INNER JOIN City C ON C.id = U.city_id WHERE user_type IN ('alumni','volunteer') AND status='1'";
		if($checks) $query .= " AND (" . implode($and_or, $checks) . ")";

		return $this->sql->getAll($query);
	}

	public function getApplicantFeedback($applicant_id)
	{
		$feedback = $this->sql->getAll("SELECT reviewer_user_id, question_id, feedback, comment FROM FAM_ApplicantFeedback 
							WHERE applicant_user_id=$applicant_id AND year={$this->year}");

		$return = [];

		foreach ($feedback as $fb) {
			$reviewer_user_id = $fb['reviewer_user_id'];
			if(!isset($return[$reviewer_user_id])) $return[$reviewer_user_id] = [];

			$return[$reviewer_user_id][] = $fb;
		}

		return $return;
	}

	public function getApplicantFeedbackQuestions()
	{
		return $this->sql->getById("SELECT id,question,type FROM FAM_ApplicantFeedbackQuestions WHERE status='1' ORDER BY FIELD(target,'all','fellow')");
	}

	public function getSurveyOptions($survey_question_id)
	{
		return $this->sql->getById("SELECT id,answer FROM SS_Answer WHERE question_id=$survey_question_id AND status='1'");
	}
	public function getSurveyResponse($survey_question_id, $applicant_id)
	{
		return $this->sql->getById("SELECT answer AS id, 1 AS checked FROM SS_UserAnswer WHERE user_id=$applicant_id AND question_id=$survey_question_id");
	}

	public function statusSelectOption($name,$label,$status){
		$status_array = array(
			'0'			=>	'Any',
			'pending'	=>	'Pending',
			'free-pool'	=>	'Free Pool',
			'maybe'		=>	'Maybe',
			'rejected'	=>	'Rejected',
			'selected'	=>	'Selected',
		);

		$input = '<label for="'.$name.'">'.$label.'</label>'.'<select id="'.$name.'" name="'.$name.'">';
		foreach ($status_array as $key => $s) {
			$selected='';
			if($key==$status){
				$selected='selected';
			}
			$input .= '<option value="'.$key.'" '.$selected.'>'.$s.'</option>';
		}
		$input .= '</select>';

		return $input;
	}
}
