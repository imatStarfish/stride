<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'categoryView.php';
require_once 'categoryModel.php';
require_once 'Project/Model/Articles/article_categories/article_category.php';

class categoryController extends applicationsSuperController
{
	public function viewAction()
	{
		$permalink = strip_tags($this->getValueOfURLParameterPair("view"));
		
		//check if there is permalink
		if($permalink)
		{
			
			$article_category = new article_category();
			$article_category->select($permalink);
			
			//check if permalink is in the database then display the article listing
			//under this category
			if($article_category->article_category_id)
			{
				$view = new categoryView();
				
				//select all articles within this categoryf
				$query_helper   		= new articles_query_helper();
				$query_helper->category = array("permalink" => $permalink);
				
				$view->articles = $query_helper->selectAllArticles();
				$view->displayArticleListingTemplate();
			}
			else
				header("Location: /404");
		}
		
	}
}