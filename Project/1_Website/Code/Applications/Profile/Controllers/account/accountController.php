<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'accountView.php';
require_once 'accountModel.php';
require_once "Project/1_Website/Code/Modules/userSession.php";


class accountController extends applicationsSuperController
{
	public function indexAction()
	{
		$model = new accountModel();
		$model->user_id = userSession::getSession("user_id");
		$applications   = $model->getAllApplications();
		
		$view = new accountView();
		$view->applications = $applications;
		$view->displayApplicationListing();
	}
}