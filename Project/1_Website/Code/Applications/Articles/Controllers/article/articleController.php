<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'articleView.php';
require_once 'articleModel.php';
require_once 'Project/Model/Articles/articles/articles_query_helper.php';

class articleController extends applicationsSuperController
{
	public function indexAction()
	{
		$permalink = strip_tags($this->getValueOfURLParameterPair(routes::getInstance()->getCurrentTopLevelURLName()));

		//if there is permalink in the url display the article details
		if($permalink)
		{
			$view = new articleView();
			$view->article = articles_query_helper::selectByColumn("r.permalink", $permalink);
			$view->displayArticleDetailsTemplate();
		}
		//display article listing
		else
		{
			$view = new articleView();
			
			//select all articles
			$query_helper   = new articles_query_helper();
			$view->articles = $query_helper->selectAllArticles();
			
			$view->displayArticleListingTemplate();
		}
	}
}