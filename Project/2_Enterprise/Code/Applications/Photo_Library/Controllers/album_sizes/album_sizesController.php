<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'album_sizesView.php';
require_once 'album_sizesModel.php';
require_once 'Project/Model/Photo_Library/album/album.php';
require_once 'Project/Model/Photo_Library/album_size/album_image_size.php';
require_once 'Project/Model/Photo_Library/image/images.php';

class album_sizesController extends applicationsSuperController
{
	//--------------------------------------------------------------------------------------------------------------------

	public function addAlbumSizeAction()
	{
		$dimensions = $_POST["width"]."x".$_POST["height"];
		$album_id   = $_POST["album_id"];
		
		//select album details
		$album = new album();
		$album->setAlbumId($album_id);
		$album->select();
		
		//set the path of the folders to be created
		$album_size_path 	   = STAR_SITE_ROOT."/Data/Images/{$album->getAlbumFolder()}/{$dimensions}";
		$album_size_thumb_path = STAR_SITE_ROOT."/Data/Images/{$album->getAlbumFolder()}/{$dimensions}_thumb";
		
		//check if directory is exists (this is also checked in ajax)
		if(!is_dir($album_size_path))
		{
			//create the directories
			$data_handler = new dataHandler();
			$data_handler->create_directory($album_size_path);
			$data_handler->create_directory($album_size_thumb_path);
			
			//add album size to database
			$album_image_size = new album_image_size();
			$album_image_size->setAlbumId($album->getAlbumId());
			$album_image_size->setDimensions($dimensions);
			$album_image_size->insert();
			
			header("Location: /photo_library/images/view?album_id={$album->getAlbumId()}");
		}
		else
			header('Location: /photo_library');
	}
	
	//--------------------------------------------------------------------------------------------------------------------

	public function deleteAlbumSizeAction()
	{
		if(isset($_POST["delete_album_size"]))
		{
			$size_id  = $_POST["size_id"];
			
			//delete the image size in the database
			$album_image_size = new album_image_size();
			$album_image_size->setSizeId($size_id);
			$album_image_size->select();
			
			//select album details for album folder name to complete the path to be deleted
			$album = new album();
			$album->setAlbumId($album_image_size->getAlbumId());
			$album->select();

			//delete all images in the database
			$images = new images();
			$images->deleteByColumn("size_id", $size_id);
			
			//delete the image size_folder
			$data_handler = new dataHandler();
			
			//do this or else album folder will be deleted
			if($album->getAlbumFolder() != NULL || $album->getAlbumFolder() != "")
			{
				$data_handler->delete_directory_recurse('Data/Images/'.$album->getAlbumFolder().'/'.$album_image_size->getDimensions());
				$data_handler->delete_directory_recurse('Data/Images/'.$album->getAlbumFolder().'/'.$album_image_size->getDimensions()."_thumb"); 
			}
			
			$album_image_size->delete();
			
			header("Location: /photo_library/images/view?album_id={$album->getAlbumId()}");
		}
	}
	
	
}