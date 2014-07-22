<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Model/Photo_Library/image/images.php';

class imagesView  extends applicationsSuperView
{
	private $array_of_images;
// 	private $array_of_image_sizes;
	private $album;
	private $image_count;
	//-------------------------------------------------------------------------------------------------
	
	public function getAlbum()
	{
		return $this->album;
	}
	
	public function getArrayOfImages()
	{
		return $this->array_of_images;
	}
	
// 	public function getArrayOfImageSizes()
// 	{
// 		return $this->array_of_image_sizes;
// 	}
	
	public function getImageCount()
	{
		return $this->image_count;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function setArrayOfImages($array_of_images)
	{
		$this->array_of_images = $array_of_images;
	}
	
// 	public function setArrayOfImageSizes($array_of_image_sizes)
// 	{
// 		$this->array_of_image_sizes = $array_of_image_sizes;
// 	}
	
	public function setAlbum($album)
	{
		$this->album = $album;
	}
	
	public function setImageCount($image_count)
	{
		$this->image_count = $image_count;
	}
	
	//-------------------------------------------------------------------------------------------------

	
	public function __construct()
	{
		$this->template_location = 'Project/'.DOMAIN.'/Design/Applications/Photo_Library/Controllers/templates/images/';
	}
	
	
	//-------------------------------------------------------------------------------------------------
	
	public function displayMainTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'popup_dialogs.phtml');
		$content .= $this->renderTemplate("Project/2_Enterprise/Design/Applications/Photo_Library/Controllers/templates/album_sizes/popup_dialogs.phtml");
		response::getInstance()->addContentToTree(array('POPUP_DIALOGS'=>$content));
		
		$content = $this->renderTemplate($this->template_location.'image_listing.phtml');
		response::getInstance()->addContentToTree(array('CONTENTS'=>$content));
		
		$content = $this->renderTemplate($this->template_location.'image_header.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));
		
		$content = $this->renderTemplate($this->template_location.'main_template.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	
}