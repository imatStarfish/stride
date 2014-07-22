<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'categoryView.php';
require_once 'categoryModel.php';
require_once 'Project/Model/Products/product_categories/product_category.php';
require_once 'Project/Model/Products/product_categories/product_categories.php';
require_once 'Project/Model/Products/products/products_query_helper.php';

class categoryController extends applicationsSuperController
{
	public function indexAction()
	{
		//select all categories
		$product_categories = new product_categories();
		$product_categories->select(routes::getInstance()->getCurrentTopLevelURLName());

		$view = new categoryView();
		$view->categories = $product_categories->categories;
		$view->displayCategoryListingTemplate();
	}

	//-----------------------------------------------------------------------------------------------

	public function viewAction()
	{
		//select all products within this category
		$query_helper   			= new products_query_helper();
		$query_helper->category 	= array("product_category_id" => $this->getValueOfURLParameterPair("view"));
		$query_helper->product_type = routes::getInstance()->getCurrentTopLevelURLName();
		
		$view = new categoryView();
		$view->products = $query_helper->selectAllproducts();
		$view->displayProductByCategoryTemplate();
	}
	
	//-----------------------------------------------------------------------------------------------
	
	public function deleteAction()
	{
		if(isset($_POST["product_category_id"]))
		{
			//delete the category
			$product_category = new product_category();
			$product_category->product_category_id = $_POST["product_category_id"];
			$product_category->delete();
			
			//remove the category of all products affected
			$model = new categoryModel();
			$model->removeproductCategory($_POST["product_category_id"]);
			
			//remove the parent category of all categories affected
			$model->removeParentCategory($_POST["product_category_id"]);
			
			header("Location: /".routes::getInstance()->getCurrentTopLevelURLName()."/category");
		}
	}
	
	//-----------------------------------------------------------------------------------------------
	
	public function editAction()
	{
		$product_category_id = $this->getValueOfURLParameterPair("edit");
		
		//select category information
		$product_category = new product_category();
		
		$product_category->product_category_id = $product_category_id;
		$product_category->select();
		
		//select all parent categories to be displayed in dropdowns
		$model = new categoryModel();
		$parent_categories = $model->selectParentCategories(routes::getInstance()->getCurrentTopLevelURLName());
		
		$view 		  	= new categoryView();
		$view->category = $product_category;
		$view->parent_categories = $parent_categories;
		$view->displayCategoryEditorTemplate();
	}
	
	//-----------------------------------------------------------------------------------------------
	
	public function saveAction()
	{
		if(isset($_POST["product_category_id"]))
		{
			
			$product_category = new product_category();
			$product_category->product_category_id = $_POST["product_category_id"];
			$product_category->category_name 	   = $_POST["category_name"];
			$product_category->category_type 	   = routes::getInstance()->getCurrentTopLevelURLName();
			$product_category->description 		   = $_POST["description"];
			$product_category->metadata 		   = $_POST["metadata"];
			$product_category->permalink 		   = $_POST["permalink"];
			$product_category->parent_id 		   = $_POST["parent_id"];
			$product_category->update();
			
			header("Location: /".routes::getInstance()->getCurrentTopLevelURLName()."/category/edit/".$_POST["product_category_id"]);
		}
	}
	
	//-----------------------------------------------------------------------------------------------
	
	public function addAction()
	{
		if(isset($_POST["category_name"]))
		{
			$product_category = new product_category();
			$product_category->parent_id     = 0;
			$product_category->category_type = routes::getInstance()->getCurrentTopLevelURLName();;
			$product_category->category_name = $_POST["category_name"];
			$product_category->description   = "";
			$product_category->metadata		 = "";
			$product_category->permalink 	 = $this->createPermalink($_POST["category_name"]);
			$product_category->insert();

			header("Location: /".routes::getInstance()->getCurrentTopLevelURLName()."/category/edit/".$product_category->product_category_id);
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