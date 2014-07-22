<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Model/Articles/articles/articles_query_helper.php';

class articleView  extends applicationsSuperView
{
	public $article;
	public $articles;
	
	public $template_location;
	public $block_location;
	
	public function __construct()
	{
		$current_application_id  = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$this->template_location = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Controllers/article";
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
		
		$content = $this->renderTemplate($this->template_location.'/article_listing_container.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
		
		$content  = $articleBlockController->renderSideCategoryTemplate();
		response::getInstance()->addContentToTree(array('LEFT_SIDE_NAV'=>$content));
		
		$content = $articleBlockController->renderArticleListingTemplate($this->articles);
		response::getInstance()->addContentToTree(array('ARTICLE_LISTING'=>$content));
		
	}
	
	public function displayArticleDetailsTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'/article_details.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
}