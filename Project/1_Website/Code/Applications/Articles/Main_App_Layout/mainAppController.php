<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once('mainAppModel.php');
require_once('mainAppView.php');

class mainAppController extends applicationsSuperController
{
	public function getMainLayout()
	{	
		$mainAppView = new mainAppView();
		$mainAppView->displayApplicationMainLayout();
	}
}

?>	