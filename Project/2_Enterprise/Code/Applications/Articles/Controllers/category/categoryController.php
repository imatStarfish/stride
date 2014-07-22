<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'categoryView.php';
require_once 'categoryModel.php';
require_once 'Project/Model/Articles/article_categories/article_category.php';
require_once 'Project/Model/Articles/article_categories/article_categories.php';

class categoryController extends applicationsSuperController
{
	public function indexAction()
	{
		//select all categories
		$article_categories = new article_categories();
		$article_categories->select(routes::getInstance()->getCurrentTopLevelURLName());

		$view = new categoryView();
		$view->categories = $article_categories->categories;
		$view->displayCategoryListingTemplate();
	}

	//-----------------------------------------------------------------------------------------------

	public function viewAction()
	{
		//select all articles within this category
		$query_helper   			= new articles_query_helper();
		$query_helper->category 	= array("article_category_id" => $this->getValueOfURLParameterPair("view"));
		$query_helper->article_type = routes::getInstance()->getCurrentTopLevelURLName();
		
		$view = new categoryView();
		$view->articles = $query_helper->selectAllArticles();
		$view->displayArticleByCategoryTemplate();
	}
	
	//-----------------------------------------------------------------------------------------------
	
	public function deleteAction()
	{
		if(isset($_POST["article_category_id"]))
		{
			//delete the category
			$article_category = new article_category();
			$article_category->article_category_id = $_POST["article_category_id"];
			$article_category->delete();
			
			//remove the category of all articles affected
			$model = new categoryModel();
			$model->removeArticleCategory($_POST["article_category_id"]);
			
			//remove the parent category of all categories affected
			$model->removeParentCategory($_POST["article_category_id"]);
			
			header("Location: /".routes::getInstance()->getCurrentTopLevelURLName()."/category");
		}
	}
	
	//-----------------------------------------------------------------------------------------------
	
	public function editAction()
	{
		$article_category_id = $this->getValueOfURLParameterPair("edit");
		
		//select category information
		$article_category = new article_category();
		$article_category->article_category_id = $article_category_id;
		$article_category->select();
		
		//select all parent categories to be displayed in dropdowns
		$model = new categoryModel();
		$parent_categories = $model->selectParentCategories(routes::getInstance()->getCurrentTopLevelURLName());
		
		$view 		  	= new categoryView();
		$view->category = $article_category;
		$view->parent_categories = $parent_categories;
		$view->displayCategoryEditorTemplate();
	}
	
	//-----------------------------------------------------------------------------------------------
	
	public function saveAction()
	{
		if(isset($_POST["article_category_id"]))
		{
			$article_category = new article_category();
			$article_category->article_category_id = $_POST["article_category_id"];
			$article_category->category_name 	   = $_POST["category_name"];
			$article_category->category_type 	   = routes::getInstance()->getCurrentTopLevelURLName();
			$article_category->description 		   = $_POST["description"];
			$article_category->metadata 		   = $_POST["metadata"];
			$article_category->permalink 		   = $_POST["permalink"];
			$article_category->parent_id 		   = $_POST["parent_id"];
			$article_category->update();
			
			header("Location: /".routes::getInstance()->getCurrentTopLevelURLName()."/category/edit/".$_POST["article_category_id"]);
		}
	}
	
	//-----------------------------------------------------------------------------------------------
	
	public function addAction()
	{
		if(isset($_POST["category_name"]))
		{
			$article_category = new article_category();
			$article_category->parent_id     = 0;
			$article_category->category_type = routes::getInstance()->getCurrentTopLevelURLName();;
			$article_category->category_name = $_POST["category_name"];
			$article_category->description   = "";
			$article_category->metadata		 = "";
			$article_category->permalink 	 = $this->createPermalink($_POST["category_name"]);
			$article_category->insert();
			
			header("Location: /".routes::getInstance()->getCurrentTopLevelURLName()."/category/edit/".$article_category->article_category_id);
		}
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