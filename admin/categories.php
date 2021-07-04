<?php
require '../common.php';

$crud = new Crud("FAM_Parameter_Category");

$verticals[0] = 'All';
$crud->addField("group_id", 'Role', 'enum', array(), $verticals);
$crud->addListDataField("stage_id", 'FAM_Stage');

$crud->render();