<?php
require_once 'photo_libraryBlockView.php';
require_once 'photo_libraryBlockModel.php';

require_once 'Project/Model/Photo_Library/album/album.php';
require_once 'Project/Model/Photo_Library/album/albums.php';
require_once 'Project/Model/Photo_Library/album_size/album_image_size.php';
require_once 'Project/Model/Photo_Library/album_size/album_image_sizes.php';
require_once 'Project/Model/Photo_Library/image/images.php';

class photo_librayBlockController
{
	private $image_id;
	private $album;
	
	//-------------------------------------------------------------------------------------------------------
	
	public function getImageId()
	{
		return $this->image_id;
	}
	
	//-------------------------------------------------------------------------------------------------------
	
	public function getAlbum()
	{
		return $this->album;
	}

	//-------------------------------------------------------------------------------------------------------
	
	public function setImageId($image_id)
	{
		$this->image_id = $image_id;
	}
	
	public function setAlbum($album)
	{
		$this->album = $album;
	}
	
	//-------------------------------------------------------------------------------------------------------

	public function getImageEditorTemplate()
	{
		//get image details
		//we don't use generic select on this because of the path in getting of image
		$model = new photo_libray2BlockModel();
		$image = $model->getImageDetails($this->image_id);

		//get all alubms for dropdown
		$albums = new albums();
		$albums->select();
		$array_of_albums = $albums->getArrayOfAlbums();
		
		//get all image sizes in this album
		$album_image_sizes = new album_image_sizes();
		$album_image_sizes->select($image["album_id"]);
		$array_of_sizes = $album_image_sizes->getArrayOfAlbumImageSizes();
		
		
		$view = new photo_librayBlockView();
		$view->setImage($image);
		$view->setArrayOfAlbums($array_of_albums);
		$view->setArrayOfImageSizes($array_of_sizes);
		
		return $view->renderImageEditorTemplate();
	}
	
	
	//-------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------
	//Photo Chooser
	
	public function getPhotoChooserAlbumTemplate($array_of_albums)
	{
		$view = new photo_librayBlockView();
		$view->setArrayOfAlbums($array_of_albums);
		return $view->renderPhotoChooserAlbumTemplate();
	}
	
	public function getPhotoChooserImagesTemplate($array_of_images)
	{
		$view = new photo_librayBlockView();
		$view->setAlbum($this->album);
		$view->setArrayOfImages($array_of_images);
		return $view->renderPhotoChooserImagesTemplate();
	}
	
	
}