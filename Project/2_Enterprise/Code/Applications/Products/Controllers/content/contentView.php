<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class contentView  extends applicationsSuperView
{
	public $product;
	public $products;
	public $product_categories;
	
	public $template_location;
	public $block_location;
	
	public function __construct()
	{
		$current_application_id  = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$this->template_location = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Controllers/content";
		$this->block_location    = "Project/".DOMAIN."/Design/Applications/".$current_application_id."/Blocks";
	}
	
	//-------------------------------------------------------------------------------------------------	--
	
	private function displayMainTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'application_header.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));

	}
	
	//-------------------------------------------------------------------------------------------------	

	public function displayProductListingTemplate()
	{
		require_once "Project/".DOMAIN."/Code/Applications/".applicationsRoutes::getInstance()->getCurrentApplicationID()."/Blocks/productsBlockController.php";
		$productsBlockController = new productsBlockController();
		
		$content = $productsBlockController->renderProductListingTemplate($this->products);
		response::getInstance()->addContentToTree(array('APPLICATION_CONTENT'=>$content));
	}
	
	//-------------------------------------------------------------------------------------------------	
	
	public function displayProductDetailsTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'/content_details.phtml');
		response::getInstance()->addContentToTree(array("APPLICATION_CONTENT"=>$content));
	}
}