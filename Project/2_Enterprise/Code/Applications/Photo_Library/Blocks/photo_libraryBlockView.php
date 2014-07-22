<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Model/Photo_Library/image/images.php';

class photo_librayBlockView  extends applicationsSuperView
{
	private $image;
	private $array_of_images;
	private $array_of_image_sizes;
	private $array_of_albums;
	private $album;
	//-------------------------------------------------------------------------------------------------
	
	public function getArrayOfImages()
	{
		return $this->array_of_images;
	}
	
	public function getImageDetails()
	{
		return $this->image;
	}
	
	public function getArrayOfImageSizes()
	{
		return $this->array_of_image_sizes;
	}
	
	public function getArrayOfAlbums()
	{
		return $this->array_of_albums;
	}
	
	public function getAlbum()
	{
		return $this->album;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function setArrayOfImages($array_of_images)
	{
		$this->array_of_images = $array_of_images;
	}
	
	public function setImage($image)
	{
		$this->image = $image;
	}
	
	public function setArrayOfImageSizes($array_of_image_sizes)
	{
		$this->array_of_image_sizes = $array_of_image_sizes;
	}
	
	public function setArrayOfAlbums($array_of_albums)
	{
		$this->array_of_albums = $array_of_albums;
	}
	
	
	public function setAlbum($album)
	{
		$this->album = $album;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function __construct()
	{
		$this->template_location = 'Project/'.DOMAIN.'/Design/Applications/Photo_Library/Blocks/templates/';
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function renderImageEditorTemplate()
	{
		$content = $this->renderTemplate($this->template_location."image_editor/image_editor.phtml");
		return $content;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function renderPhotoChooserAlbumTemplate()
	{
		$content = $this->renderTemplate($this->template_location."photo_chooser/photo_chooser_albums.phtml");
		return $content;
	}

	//-------------------------------------------------------------------------------------------------
	
	public function renderPhotoChooserImagesTemplate()
	{
		$content = $this->renderTemplate($this->template_location."photo_chooser/photo_chooser_images.phtml");
		return $content;
	}


}
?>