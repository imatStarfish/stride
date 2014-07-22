<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Model/Articles/articles/articles_query_helper.php';

class applicationsView  extends applicationsSuperView
{
	public $application;
	
	public $template_location;
	public $block_location;
	
	public function __construct()
	{
		$current_application_id  = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$this->template_location = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Controllers/applications";
		$this->block_location    = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Blocks";
	}
	
	//---------------------------------------------------------------------------------------------------------
	
	public function displayApplicationTemplate()
	{
		$this->displayGrantSideNavTemplate();
		
		$content = $this->renderTemplate($this->template_location."/application_template.phtml");
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	
	//---------------------------------------------------------------------------------------------------------
	
	private function displayGrantSideNavTemplate()
	{
		$content = $this->renderTemplate($this->template_location."/grants/side_nav.phtml");
		response::getInstance()->addContentToTree(array('SIDE_NAV'=>$content));
	}
	
	//---------------------------------------------------------------------------------------------------------
	
	public function displayFormsTemplate($user_type, $application_type)
	{
		$content = $this->renderTemplate($this->template_location."/".$user_type."/".$application_type.".phtml");
		response::getInstance()->addContentToTree(array('FORMS'=>$content));
	}
	
	
	
}