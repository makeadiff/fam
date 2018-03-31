<?php
/// Common model. Includes all the neccessay data interactions. Not enough to make seperate models yet.
class FAM {
	private $sql;

	function __construct() {
       global $sql;
       $this->sql = $sql;
	}

	public function getSelectionStatus($user_id, $group_id)
	{
		return $this->sql->getOne("SELECT status FROM FAM_UserGroupPreference WHERE user_id=$user_id AND group_id=$group_id");
	}

	public function setSelectionStatus($user_id, $group_id, $status)
	{
		return $this->sql->execQuery("UPDATE FAM_UserGroupPreference SET status='$status' WHERE user_id=$user_id AND group_id=$group_id");
	}

	public function getStage($stage_id)
	{
		return $this->sql->getAssoc("SELECT * FROM FAM_Stage WHERE id=$stage_id");
	}
	public function getStages()
	{
		return $this->sql->getAll("SELECT * FROM FAM_Stage WHERE name!='Done'");
	}

	public function getCategory($category_id)
	{
		return $this->sql->getAssoc("SELECT * FROM FAM_Parameter_Category WHERE id=$category_id");
	}

	public function getCategories($stage_id)
	{
		return $this->sql->getAll("SELECT id, name FROM FAM_Parameter_Category WHERE stage_id=$stage_id AND status='1'");
	}

	public function getParameters($stage_id, $category_id = 0)
	{
		return $this->sql->getAll("SELECT * FROM FAM_Parameter WHERE stage_id=$stage_id AND status='1' AND category_id=$category_id");
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
			'parameter_id'	=> $data['parameter_id']
		]);

		$this->sql->insert('FAM_Evaluation', [
			'user_id'		=> $data['applicant_id'],
			'parameter_id'	=> $data['parameter_id'],
			'evaluator_id'	=> $data['evaluator_id'],
			'response'		=> $data['response'],
			'added_on'		=> 'NOW()'
		]);
	}

	public function resetAssignments($evaluator_id, $amount_users)
	{
		$this->sql->execQuery("DELETE FROM FAM_UserEvaluator WHERE evaluator_id=$evaluator_id AND user_id IN (" . implode(',', $amount_users) . ")");
	}

	public function assignEvaluators($user_id, $evaluator_id, $group_id)
	{
		$this->sql->insert("FAM_UserEvaluator", [
			'user_id'		=> $user_id,
			'evaluator_id'	=> $evaluator_id,
			'group_id'		=> $group_id
		]);
	}

	public function getStageStatus($user_id, $stage_id)
	{
		$stage = $this->sql->getAssoc("SELECT * FROM FAM_UserStage WHERE user_id=$user_id AND stage_id=$stage_id");

		if(!$stage) $stage = ['status' => 'pending', 'comment' => ''];

		return $stage;
	}
	public function saveStageStatus($data)
	{
		$existing = $this->getStageStatus($data['user_id'], $data['stage_id']);

		if(!isset($existing['id'])) $this->sql->insert("FAM_UserStage", $data);
		else $this->sql->update("FAM_UserStage", [
				'comment'		=> $data['comment'],
				'status'		=> $data['status'],
				'evaluator_id'	=> $data['evaluator_id'],
			], ['id' => $existing['id']]);
	}

	public function getUnassignedApplicants()
	{
		return $this->sql->getAll("SELECT U.id, U.name, U.phone, U.email, GROUP_CONCAT(UGP.group_id ORDER BY UGP.preference SEPARATOR ',') AS groups, 
											UGP.preference, UGP.status, UGP.id AS ugp_id, C.name AS city
										FROM FAM_UserGroupPreference UGP
										INNER JOIN User U ON U.id=UGP.user_id 
										LEFT JOIN FAM_UserEvaluator UE ON UE.user_id=U.id
										INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
										WHERE UE.evaluator_id IS NULL AND U.status='1' AND (U.user_type='volunteer' OR U.user_type='alumni') AND UGP.status != 'withdrawn'
										GROUP BY UGP.user_id");
	}

	public function getApplicants($source) {
		$checks = ['1=1'];
		$join = '';

		if(!empty($source['group_id'])) $checks[] = "group_id=" . $source['group_id'];
		if(!empty($source['city_id'])) $checks[] = "((UGP.city_id != 0 AND UGP.city_id={$source['city_id']}) OR (UGP.city_id = 0 AND U.city_id={$source['city_id']}))";
		if(isset($source['evaluator_id'])) {
			if(!$source['evaluator_id']) return [];
	 		$checks[] = "UE.evaluator_id=" . $source['evaluator_id'];
	 		$join = "INNER JOIN FAM_UserEvaluator UE ON U.id=UE.user_id";
	 	}

	 	$query = "SELECT U.id, U.name, U.email, U.mad_email, U.phone, GROUP_CONCAT(UGP.group_id ORDER BY UGP.preference SEPARATOR ',') AS groups, UGP.preference, C.name AS city, UGP.id AS ugp_id
				FROM User U
				INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id=U.id
				$join
				INNER JOIN City C ON ((UGP.city_id != 0 AND UGP.city_id=C.id) OR (UGP.city_id = 0 AND U.city_id=C.id))
				WHERE " . implode(" AND ", $checks) . " AND UGP.status != 'withdrawn'
				GROUP BY UGP.user_id
				ORDER BY C.name, U.name";

		return $this->sql->getAll($query);
	}

	public function getEvaluators($applicant_id, $group_id)
	{
		return $this->sql->getAll("SELECT U.id, U.name 
				FROM User U 
				INNER JOIN FAM_UserEvaluator E ON E.evaluator_id=U.id
				WHERE E.user_id=$applicant_id AND E.group_id=$group_id AND U.status='1' AND U.user_type='volunteer'");
	}

	public function getResponse($applicant_id, $parameter_id)
	{
		return $this->sql->getOne("SELECT response FROM FAM_Evaluation WHERE user_id=$applicant_id AND parameter_id=$parameter_id");
	}

	public function getApplications($applicant_id)
	{
		return $this->sql->getAll("SELECT preference, group_id, city_id FROM FAM_UserGroupPreference WHERE user_id=$applicant_id");
	}

	public function findUser($parameters, $and_or = ' AND ')
	{
		$checks = [];
		foreach ($parameters as $field => $value) {
			$checks[] = "`$field` = '" . $this->sql->escape($value) . "'";
		}

		$query = "SELECT id,name,email,mad_email,phone,user_type FROM User 
					WHERE (user_type='volunteer' OR user_type='alumni') AND status='1'";

		if($checks) $query .= " AND (" . implode($and_or, $checks) . ")";

		return $this->sql->getAll($query);
	}


	public function getApplicantFeedback($applicant_id)
	{
		$feedback = $this->sql->getAll("SELECT reviewer_user_id, question_id, feedback, comment FROM FAM_ApplicantFeedback WHERE applicant_user_id=$applicant_id");

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
		return $this->sql->getById("SELECT id,question,type FROM FAM_ApplicantFeedbackQuestions");
	}
}
