<?php
/// Common model. Includes all the neccessay data interactions. Not enough to make seperate models yet.
class FAM {
	private $sql;

	function __construct() {
       global $sql;
       $this->sql = $sql;
	}

	public function getGroups($vertical_id = 0)
	{
		$vertical_check = '';
		if($vertical_id) $vertical_check = "vertical_id=$vertical_id AND";
		return $this->sql->getAll("SELECT id, name FROM `Group` 
			WHERE $vertical_check status='1' AND group_type='normal' AND (type='fellow' OR type='strat')");
	}

	public function getApplicants($source) {
		$checks = ['1=1'];

		if(!empty($source['group_id'])) $checks[] = "group_id=" . $source['group_id'];
		if(!empty($source['city_id'])) $checks[] = "city_id=" . $source['city_id'];

		if(isset($source['evaluator_id'])) {
			if(!$source['evaluator_id']) return [];

			$checks[] = "E.evaluator_id=" . $source['evaluator_id'];
			return $this->sql->getAll("SELECT U.id,U.name, U.email, U.mad_email, U.phone, E.group_id, 0 AS preference 
				FROM User U
				INNER JOIN FAM_Evaluator E ON E.user_id=U.id
				WHERE " . implode(" AND ", $checks));
		}

		return $this->sql->getAll("SELECT U.id, U.name, U.email, U.mad_email, U.phone, UGP.group_id, UGP.preference
				FROM User U
				INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id=U.id
				WHERE " . implode(" AND ", $checks));
	}


	public function getCategories($stage_id)
	{
		return $this->sql->getAll("SELECT id, name FROM FAM_Parameter_Category WHERE stage_id=$stage_id AND status='1'");
	}

	public function getParameters($stage_id, $category_id = 0)
	{
		return $this->sql->getAll("SELECT * FROM FAM_Parameter WHERE stage_id=$stage_id AND status='1' AND category_id=$category_id");
	}

	public function getResponse($applicant_id, $parameter_id)
	{
		return $this->sql->getOne("SELECT response FROM FAM_Evaluation WHERE user_id=$applicant_id AND parameter_id=$parameter_id");
	}
}
