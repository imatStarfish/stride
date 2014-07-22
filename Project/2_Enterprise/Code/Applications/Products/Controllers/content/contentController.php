<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'contentView.php';
require_once 'contentModel.php';
require_once 'Project/Model/Products/products/products_query_helper.php';
require_once 'Project/Model/Products/product_categories/product_categories.php';
require_once 'Project/Model/Products/products/product.php';
require_once 'Project/Model/Routes/route.php';

class contentController extends applicationsSuperController
{
	public function indexAction()
	{
		$query_helper 				= new products_query_helper();
		$query_helper->product_type = routes::getInstance()->getCurrentTopLevelURLName();
		
		$view = new contentView();
		
		$view->products = $query_helper->selectAllProducts();
		
		$view->displayProductListingTemplate();
	}

	//------------------------------------------------------------------------------------------------------------

	public function viewAction()
	{
		$product_id = $this->getValueOfURLParameterPair("view");

		//select article details
		$product 	= products_query_helper::selectByColumn("product_id", $product_id);

		//check if article is realy existing
		if(isset($product["product_id"]))
		{
			$view = new contentView();
				
			//select article categories for displaying in dropdowns
			$product_categories = new product_categories();
			
			$product_categories->select(routes::getInstance()->getCurrentTopLevelURLName());
			
			$view->product_categories = $product_categories->categories;
			$view->product 			  = $product;
			
			$view->displayProductDetailsTemplate();
		}
		else
			header("Location: /404");
	}

	//------------------------------------------------------------------------------------------------------------

	public function updateAction()
	{
		if(isset($_POST["product_id"]))
		{
			$product = new product();
			
			$product->product_id = $_POST["product_id"];
			$product->select();
				
			//udpate article
			$product->product_category_id = $_POST["product_category_id"];
			$product->product_title 	  = $_POST["product_title"];
			$product->product_price 	  = $_POST["product_price"];
			$product->is_publish    	  = $_POST["is_publish"];
			$product->product_description = $_POST["product_description"];
			$product->date_created  	  = strtotime($_POST["date_created"]);
			$product->metadata			  = $_POST["metadata"];
			$product->image_id			  = $_POST["image_id"];
			$product->update();
				
			//update permalink
			if($_POST["current_permalink"] != $_POST["permalink"])
			{
				$route 	   = new route();
				$permalink = $this->createPermalink($_POST["permalink"]);
				$route->ifPermalinkExists($permalink, $_POST["route_id"]);

				if(!$route->getPermalink())
				{
					$route->setPermalink($permalink);
					$route->setRouteId($_POST["route_id"]);
					$route->update();
				}
			}
				
			$url = routes::getInstance()->getCurrentTopLevelURLName()."/content/view/".$product->product_id;
			header("Location: /$url");
		}
	}

	//------------------------------------------------------------------------------------------------------------

	public function deleteAction()
	{
		if(isset($_POST["product_id"]))
		{
			$product = new product();
			
			$product->product_id = $_POST["product_id"];
			$product->delete();
			
			$route 	 = new route();
			$route->setRouteId($_POST["route_id"]);
			$route->delete();
			
			
			header("Location: /".routes::getInstance()->getCurrentTopLevelURLName());
		}
	}
	
	//------------------------------------------------------------------------------------------------------------

	public function addAction()
	{
		if(isset($_POST["product_title"]))
		{
			$product_type = routes::getInstance()->getCurrentTopLevelURLName();
			
			$route = new route();
			
			$route->setPermalink($this->createPermalink($_POST["product_title"])); 
			$route->setPageId("product"); 
			$route->insert();
			
			if($route->getRouteId())
			{
				$product = new product();
				
				$product->product_type 	 	  = $product_type;
				$product->product_title 	  = $_POST["product_title"];
				$product->product_price 	  = 0.00;
				$product->route_id 			  = $route->getRouteId();
				$product->product_category_id = 0;
				$product->is_publish		  = 0;
				$product->date_created 		  = strtotime(date("Y-m-d H:i"));
				$product->insert();
				
				header("Location: /".routes::getInstance()->getCurrentTopLevelURLName()."/content/view/".$product->product_id);
			}
			else
				header("Location: /".routes::getInstance()->getCurrentTopLevelURLName());
		}
	}

	//------------------------------------------------------------------------------------------------------------

	
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
	
	//------------------------------------------------------------------------------------------------------------
}