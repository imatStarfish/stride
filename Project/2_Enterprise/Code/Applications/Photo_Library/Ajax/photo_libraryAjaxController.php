<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/2_Enterprise/Code/Applications/Photo_Library/Modules/imageChecker/imageChecker.php';
require_once 'Project/2_Enterprise/Code/Applications/Photo_Library/Blocks/photo_libraryBlockController.php';

require_once 'Project/Model/Photo_Library/image/image.php';
require_once 'Project/Model/Photo_Library/image/images.php';
require_once 'Project/Model/Photo_Library/album_size/album_image_sizes.php';
require_once 'Project/Model/Photo_Library/album/album.php';

	 	
class photo_libraryAjaxController extends applicationsSuperController
{
	private	$characters = array(".","png","jpg","gif");

	//-------------------------------------------------------------------------------------------------------------------------------------
	
	public function loadImageEditorAction()
	{
		$image_id = $_REQUEST["image_id"];

		//get the image editor template with image details on it
		$block_controller = new photo_librayBlockController();
		$block_controller->setImageId($image_id);
		
		$content  = $block_controller->getImageEditorTemplate();

		jQuery('#photo_details_holder_container')->html($content);
		jQuery::getResponse();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------
	
	public function getImageGroupSizesOnAlbumAction()
	{
		$album_id = $_REQUEST["album_id"];
		
		$album_image_sizes = new album_image_sizes();
		$album_image_sizes->select($album_id);
		$array_of_sizes = $album_image_sizes->getArrayOfAlbumImageSizes();
		
		$album = new album();
		$album->setAlbumId($album_id);
		$album->select();
	
		// change the value of the image sizes dropdown
		$content = "";
		foreach($array_of_sizes as $size)
			$content .= '<option value=\'{"size_id":"'. $size->getSizeId() .'", "dimensions":"'. $size->getDimensions() .'", "album_folder":"'.$album->getAlbumFolder().'" }\' >'.$size->getDimensions().'</option>';
		
		
		jQuery("#photo_details_holder select[name=image_sizes]")->html($content);
		jQuery::getResponse();
	}
	
	//-------------------------------------------------------------------------------------------------------------------------------------
	
	public function checkIfImageIsUsedAction()
	{
		$image_id = $_REQUEST["image_id"];
		
		//check image id is use in other modules
		$is_used  = imageChecker::checkIfImageIsUsed($image_id);
		
		if($is_used)
		{
			jQuery("#delete-image-error #page")->html($is_used);	
			jQuery::evalScript("$('#delete-image-error').modal('toggle');");
		}
		else
		{
			jQuery("#delete-image-dialog input[name=image_id]")->val($image_id);
			jQuery::evalScript("$('#delete-image-dialog').modal('toggle');");
		}
		
		jQuery::getResponse();
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------------
	
	public function checkIfFolderExistAction()
	{
		$album_title  = $_REQUEST["album_title"];
		$album_folder = str_replace(' ', '-', $album_title).'_album';
		
		//check if the directory exist
		if(!is_dir(STAR_SITE_ROOT."/Data/Images/{$album_folder}"))
			jQuery("#add_album_form")->submit();
		else
		{
			$content = "Album folder already exist.";
			jQuery("#error_container")->html($content);				
		}
		
	
		jQuery::getResponse();
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------------
	
	public function checkIfAlbumSizeExistAction()
	{
		$album = new album();
		$album->setAlbumId($_REQUEST["album_id"]);
		$album->select();
		
		$album_size_path = STAR_SITE_ROOT."/Data/Images/{$album->getAlbumFolder()}/{$_REQUEST["dimensions"]}";
		
		//check if the directory exist
		if(!is_dir($album_size_path))
			jQuery("#add_album_size_form")->submit();
		else
		{
			$content = "Album size already exist.";
			jQuery("#error_container")->html($content);
		}
		
		jQuery::getResponse();
	}
	
	
	//------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------------
	//Photo Chooser
	
	public function loadPhotoChooserAction()
	{
		require_once "Project/2_Enterprise/Code/Applications/Photo_Library/Controllers/albums/albumsModel.php";
		
		//get all albums
		$model 			 = new albumsModel();
		$array_of_albums = $model->getAlbumsWithImagePreview();
		
		//load photo chooser template by album
		$block_controller = new photo_librayBlockController();
		$content = $block_controller->getPhotoChooserAlbumTemplate($array_of_albums);
		
		jQuery("#photo_chooser")->html($content);
		
		jQuery::getResponse();
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------------
	public function loadPhotoChooserImagesAction()
	{
		require_once "Project/2_Enterprise/Code/Applications/Photo_Library/Controllers/images/imagesModel.php";
		
		$album_id = $_REQUEST["album_id"];
		
		//get images inside the album
		$model 			 = new imagesModel();
		$array_of_images = $model->getImages($album_id);
		
		//select album details
		$album = new album();
		$album->setAlbumId($album_id);
		$album->select();
		
		//load photo chooser template with images
		$block_controller = new photo_librayBlockController();
		$block_controller->setAlbum($album);
		$content = $block_controller->getPhotoChooserImagesTemplate($array_of_images);
		
		jQuery("#photo_chooser")->html($content);
		jQuery::getResponse();
	}
	

	
}