<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Model/Articles/articles/article.php';
require_once 'Project/Model/Routes/route.php';
require_once 'Project/Model/Articles/article_categories/article_category.php';
	 	
class articlesAjaxController extends applicationsSuperController
{
	
	public function checkPermalinkAction()
	{
		$route 	   = new route();
		$permalink = $this->createPermalink($_POST["article_title"]);
		$route->ifPermalinkExists($permalink);
	
		if(!$route->getPermalink())
		{
			jQuery("#addArticleForm")->submit();
		}
		else
		{
			jQuery("#addArticlePopup #addError")->html("Permalink already exists");
		}
	
		jQuery::getResponse();
	}
	
	//----------------------------------------------------------------------------------------------------
	
	public function checkCategoryPermalinkAction()
	{

		$article_category = new article_category();
		$article_category->select($this->createPermalink($_POST["category_name"]));
		
		if(!$article_category->article_category_id)
		{
			jQuery("#addCategoryForm")->submit();
		}
		else
		{
			jQuery("#addArticlePopup #addError")->html("Permalink already exists");
		}
	
		jQuery::getResponse();
	}
	
	//----------------------------------------------------------------------------------------------------
	
	private function createPermalink($permalink)
	{
		//remove the extra spaces
		$permalink = preg_replace('/\s\s+/', ' ', $permalink);
	
		//old way
		$characters = array(' ','_',',','\'','.',':',';','?','!', '/');
		$string = strtolower(str_replace($characters, '-', $permalink));
	
		//remove all special characters
		$string = preg_replace("/[^-sA-Za-z0-9_]/", "", $string);
	
		//remove extra dashed dashed
		$string = preg_replace('/\-\-+/', '-', $string);
	
		return trim($string, '-');
	}
	
}