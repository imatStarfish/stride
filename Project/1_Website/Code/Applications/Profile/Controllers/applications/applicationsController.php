<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'applicationsView.php';
require_once 'applicationsModel.php';
require_once "Project/1_Website/Code/Modules/userSession.php";

class applicationsController extends applicationsSuperController
{
	public function indexAction()
	{
		if(isset($_GET["app_id"]))		
		{
			$application_id = $_GET["app_id"];
			
			$model = new applicationsModel();
			$model->application_id = $application_id;
			$application 		   = $model->getApplicationData();
			
			if($application)
			{
				$view = new applicationsView();
				$view->application = $application;
				$view->displayApplicationTemplate();	
				$view->displayFormsTemplate(userSession::getSession("user_type"), $application["application_type"]);
			}
		}
		
	}
	
	
	
	
}