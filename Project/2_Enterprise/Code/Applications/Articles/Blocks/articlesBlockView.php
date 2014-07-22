<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Model/Articles/articles/articles_query_helper.php';

class articlesBlockView  extends applicationsSuperView
{
	
	public $articles;
	public $categories;
	public $category_parameters;
	
	public function __construct()
	{
		$current_application_id  = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$this->block_location    = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Blocks";
	}
	
	//-------------------------------------------------------------------------------------------------	

	public function renderArticleListingTemplate()
	{
		return  $this->renderTemplate($this->block_location.'/article_listing.phtml');
	}
	
	//-------------------------------------------------------------------------------------------------	

	public function renderArticleCategoryTemplate()
	{
		return  $this->renderTemplate($this->block_location.'/article_category.phtml');
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
?>