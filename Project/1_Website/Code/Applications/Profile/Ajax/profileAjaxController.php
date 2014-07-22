<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
	 	
class profileAjaxController extends applicationsSuperController
{
	public function saveAction()
	{
		var_dump($_POST);
// 		$section		= $_POST["section"];
// 		$field_name		= $_POST["field_name"];
// 		$value 	 		= $_POST["value"];
// 		$applicant_id 	= $_POST["applicant_id"];
// 		$column			= $_POST["column"];
		
// 		//select the section to the database
// 		$result = $this->getApplicantInfo($applicant_id);
// 		$result	=	json_decode($result, TRUE);
		
// 		if($result[$column] != NULL)
// 			$decoded_json	=	json_decode($result[$column], TRUE);
// 		else
// 			$decoded_json	=	array();
		
// 		$decoded_json[$section][$field_name]	=	$value;
// 		$value	=	json_encode($decoded_json);
		
// 		$this->updateApplicationInfo($applicant_id, $column, $value);
		
		jQuery::getResponse();
	}
	
}