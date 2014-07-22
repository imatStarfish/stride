<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Model/Articles/articles/articles_query_helper.php';

class categoryView  extends applicationsSuperView
{
	public $parent_categories;
	public $categories;
	public $category;
	
	public $template_location;
	public $block_location;
	
	public function __construct()
	{
		$current_application_id  = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$this->template_location = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Controllers/category/";
		$this->block_location    = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Blocks";
	}
	
	//-------------------------------------------------------------------------------------------------	
	
	private function displayMainTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'application_header.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));
	}
	
	//-------------------------------------------------------------------------------------------------	

	public function displayCategoryListingTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'category_listing.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_CONTENT'=>$content));
	}
	
	//-------------------------------------------------------------------------------------------------	
	
	public function displayCategoryEditorTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'category_editor.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_CONTENT'=>$content));
	}
	
	//-------------------------------------------------------------------------------------------------	

	public function displayArticleByCategoryTemplate()
	{
		require_once "Project/".DOMAIN."/Code/Applications/".applicationsRoutes::getInstance()->getCurrentApplicationID()."/Blocks/articlesBlockController.php";
		$articleBlockController = new articlesBlockController();
		
		$content = $articleBlockController->renderArticleListingTemplate($this->articles);
		response::getInstance()->addContentToTree(array('APPLICATION_CONTENT'=>$content));
	}
	
	//-------------------------------------------------------------------------------------------------	
	
	public function  shorten_string($string, $word_count)
	{
		$retval = $string;
		$string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
		$string = str_replace("\n", " ", $string);
		$array  = explode(" ", $string);
	
		if (count($array) <= $word_count)
		{
			$retval = $string;
		}
		else
		{
			array_splice($array, $word_count);
			$retval = implode(" ", $array)." ...";
		}
	
		return $retval;
	}
}