<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class contentView  extends applicationsSuperView
{
	public $article;
	public $articles;
	public $article_categories;
	
	public $template_location;
	public $block_location;
	
	public function __construct()
	{
		$current_application_id  = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$this->template_location = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Controllers/content";
		$this->block_location    = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Blocks";
	}
	
	//-------------------------------------------------------------------------------------------------	
	
	private function displayMainTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'application_header.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));

	}
	
	//-------------------------------------------------------------------------------------------------	

	public function displayArticleListingTemplate()
	{
		require_once "Project/".DOMAIN."/Code/Applications/".applicationsRoutes::getInstance()->getCurrentApplicationID()."/Blocks/articlesBlockController.php";
		$articleBlockController = new articlesBlockController();
		
		$content = $articleBlockController->renderArticleListingTemplate($this->articles);
		response::getInstance()->addContentToTree(array('APPLICATION_CONTENT'=>$content));
	}
	
	//-------------------------------------------------------------------------------------------------	
	
	public function displayArticleDetailsTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'/content_details.phtml');
		response::getInstance()->addContentToTree(array("APPLICATION_CONTENT"=>$content));
	}
}