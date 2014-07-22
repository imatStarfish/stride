<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'albumsView.php';
require_once 'albumsModel.php';
require_once 'Project/Model/Photo_Library/album/album.php';
require_once 'Project/Model/Photo_Library/album_size/album_image_size.php';
require_once 'Project/Model/Photo_Library/image/images.php';

class albumsController extends applicationsSuperController
{
	public function indexAction()
	{
		$model 			 = new albumsModel();
		$array_of_albums = $model->getAlbumsWithImagePreview();

		$view = new albumsView();
		$view->setArrayOfAlbums($array_of_albums);
		$view->displayMainTemplate();
	}
	
	//-------------------------------------------------------------------------------------------------------------------
	
	public function addAlbumAction()
	{
		$album_title		= $_POST['album_title'];
		$height				= $_POST['height'];
		$width				= $_POST['width'];
		$album_folder		= str_replace(' ', '-', $album_title).'_album';
		$album_size_folder	= $width.'x'.$height;

		if(!file_exists(STAR_SITE_ROOT.'/Data/Images/'.$album_folder))
		{
			//add album to database
			$album = new album();
			$album->setAlbumFolder($album_folder);
			$album->setAlbumTitle($album_title);
			$album->insert();
				
			//add album size to database
			$album_image_size = new album_image_size();
			$album_image_size->setAlbumId($album->getAlbumId());
			$album_image_size->setDimensions($album_size_folder);
			$album_image_size->insert();

			//add album in Data/Images folder
			$data_handler = new dataHandler();
			
			//replace spaces in folder names with underscores so there won't be a problem with the URLs
			$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder);
			$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder.'/'.$album_size_folder);
			$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder.'/'.$album_size_folder.'_thumb');
			$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder.'/original');
			
			header("Location: /photo_library/images/view?album_id={$album->getAlbumId()}");
		}
		else
			die("There is a same folder name.");
	}
	
	//-------------------------------------------------------------------------------------------------------------------
	
	public function deleteAlbumAction()
	{
		if(isset($_POST['delete_album']))
		{
			$album_id = $_POST['album_id'];
			 
			//select album from database
			$album = new album();
			$album->setAlbumId($album_id);
			$album->select();
			 
			//delete from database
			$album->delete();
			
			//delete all images from that album
			$images = new images();
			$images->deleteByColumn("album_id", $album_id);
			
			//delete all image sizes
			$album_image_size = new album_image_size();
			$album_image_size->deleteByColumn("album_id", $album_id);
				
			//delete album folder
			$data_handler = new dataHandler();
			
			//do this or else album folder will be deleted
			if($album->getAlbumFolder() != NULL || $album->getAlbumFolder() != "")
				$data_handler->delete_directory_recurse('Data/Images/'.$album->getAlbumFolder());
			
			header('Location: /photo_library');
		}
		else 
			header('Location: /photo_library');
	}	
	
	//-------------------------------------------------------------------------------------------------------------------
	
	public function editAlbumAction()
	{
		if(isset($_POST["edit_album"]))
		{
			$album_id    = $_POST['album_id'];
			$album_title = $_POST["album_title"];
			
			$album = new album();
			$album->setAlbumId($album_id);
			$album->setAlbumTitle($album_title);
			$album->update();
			
			header('Location: /photo_library');
		}
		else
			header('Location: /photo_library');
	}
	
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
		{
			die();
			header('Location: /photo_library');
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------------
	
	
}