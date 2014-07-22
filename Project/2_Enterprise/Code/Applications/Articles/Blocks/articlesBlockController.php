<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'articlesBlockView.php';
require_once 'articlesBlockModel.php';

class articlesBlockController extends applicationsSuperController
{
	public function renderArticleListingTemplate($articles)
	{
		$view 			= new articlesBlockView();
		$view->articles = $articles;
		
		return $view->renderArticleListingTemplate();
	}
	
	//------------------------------------------------------------------------------------------------------------
	
	public function renderSideCategoryTemplate()
	{
		$model = new articlesBlockModel();
		$categories = $model->selectArticleCategories();

		$view = new articlesBlockView();
		$view->categories = $categories;
		
		return $view->renderArticleCategoryTemplate();
	}
	
	//------------------------------------------------------------------------------------------------------------

	public function renderArticleListingByCategory($articles)
	{
		$view 			= new articlesBlockView();
		$view->articles = $articles;
		
		return $view->renderArticleListingTemplate();
	}
}