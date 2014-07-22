<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'productsBlockView.php';
require_once 'productsBlockModel.php';

class productsBlockController extends applicationsSuperController
{
	public function renderProductListingTemplate($products)
	{
		$view 			= new productsBlockView();
		$view->products = $products;
		
		return $view->renderProductListingTemplate();
	}
	
	//------------------------------------------------------------------------------------------------------------
	
	public function renderSideCategoryTemplate()
	{
		$model 		= new productsBlockModel();
		
		$categories = $model->selectproductCategories();
		
		$view 		= new productsBlockView();
		
		$view->categories = $categories;
		
		return $view->renderProductCategoryTemplate();
	}
	
	//------------------------------------------------------------------------------------------------------------

	public function renderProductListingByCategory($products)
	{
		$view 			= new productsBlockView();
		$view->products = $products;
		
		return $view->renderProductListingTemplate();
	}
}