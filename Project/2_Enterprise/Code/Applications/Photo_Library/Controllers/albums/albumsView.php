<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Model/Photo_Library/image/images.php';

class albumsView  extends applicationsSuperView
{
	private $template_location;
	private $array_of_albums;
	private $album;
	
	
	//setter
	//----------------------------------------------------------------------------------------
	public function getArrayOfAlbums()
	{
		return $this->array_of_albums;
	}
	
	public function setArrayOfAlbums($array_of_albums)
	{
		$this->array_of_albums = $array_of_albums;
	}
	
	
	public function setArrayOfImageSizes($array_of_image_sizes)
	{
		$this->array_of_image_sizes = $array_of_image_sizes;
	}
	
	//getter
	//----------------------------------------------------------------------------------------

	public function getArrayOfImageSizes()
	{
		return $this->array_of_image_sizes;
	}
	
	public function setArrayOfImages($array_of_images)
	{
		$this->array_of_images = $array_of_images;
	}

	public function getArrayOfImages()
	{
		return $this->array_of_images;
	}

	
//-------------------------------------------------------------------------------------------------	
	 

	public function __construct()
	{
		//I added this because I find the file locations hard to read. :D
		$this->template_location = 'Project/'.DOMAIN.'/Design/Applications/Photo_Library/Controllers/templates/albums/';
	}
	
	
//-------------------------------------------------------------------------------------------------	

	public function displayMainTemplate()
	{
		$content = $this->renderTemplate($this->template_location.'popup_dialogs.phtml');
		response::getInstance()->addContentToTree(array('POPUP_DIALOGS'=>$content));
		
		$content = $this->renderTemplate($this->template_location.'album_header.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));
		
		$content = $this->renderTemplate($this->template_location.'albums_listing.phtml');
		response::getInstance()->addContentToTree(array('CONTENTS'=>$content));
		
		$content = $this->renderTemplate($this->template_location.'main_template.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	 
	
//-------------------------------------------------------------------------------------------------	
	
	
	
	
}