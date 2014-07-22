<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Model/Products/products/product.php';
require_once 'Project/Model/Routes/route.php';
require_once 'Project/Model/Products/product_categories/product_category.php';
	 	
class productsAjaxController extends applicationsSuperController
{
	
	public function checkPermalinkAction()
	{
		$route 	   = new route();
		$permalink = $this->createPermalink($_POST["product_title"]);
		$route->ifPermalinkExists($permalink);
		
		if(!$route->getPermalink())
		{
			jQuery("#addProductForm")->submit();
		}
		else
		{
			jQuery("#addProductPopup #addError")->html("Permalink already exists");
		}
	
		jQuery::getResponse();
	}
	
	//----------------------------------------------------------------------------------------------------
	
	public function checkCategoryPermalinkAction()
	{

		$product_category = new product_category();
		$product_category->select($this->createPermalink($_POST["category_name"]));
		
		if(!$product_category->product_category_id)
		{
			jQuery("#addCategoryForm")->submit();
		}
		else
		{
			jQuery("#addproductPopup #addError")->html("Permalink already exists");
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