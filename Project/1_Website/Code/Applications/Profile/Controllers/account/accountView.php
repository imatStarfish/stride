<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Model/Articles/articles/articles_query_helper.php';

class accountView  extends applicationsSuperView
{
	public $applications;
	
	public $template_location;
	public $block_location;
	
	public function __construct()
	{
		$current_application_id  = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$this->template_location = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Controllers/account";
		$this->block_location    = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Blocks";
	}
	
	public function displayApplicationListing()
	{
		$content = $this->renderTemplate($this->template_location."/application_listing.phtml");
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	
}