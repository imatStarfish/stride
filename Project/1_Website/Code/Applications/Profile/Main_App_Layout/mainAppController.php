<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once('mainAppModel.php');
require_once('mainAppView.php');
require_once "Project/1_Website/Code/Modules/userSession.php";

class mainAppController extends applicationsSuperController
{
	public function getMainLayout()
	{	
		if(userSession::getSession("logged_in") == true)
		{
			$mainAppView = new mainAppView();
			$mainAppView->displayApplicationMainLayout();
		}
		else
		{
			die("Not logged in.");
		}
	}
}

?>	