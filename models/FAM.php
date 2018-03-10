<?php
/// Common model. Includes all the neccessay data interactions. Not enough to make seperate models yet.
class FAM {
	private $sql;

	function __construct() {
       global $sql;
       $this->sql = $sql;
	}

	public function getGroups($vertical_id)
	{
		return $this->sql->getAll("SELECT id, name FROM `Group` 
			WHERE vertical_id=$vertical_id AND status='1' AND group_type='normal' AND (type='fellow' OR type='strat')");
	}

	public function getEvaluators($group_id, $city_id=0)
	{
		return $this->sql->getCol("SELECT user_id FROM FAM_Evaluator WHERE group_id=$group_id AND city_id=$city_id");
	}

}
