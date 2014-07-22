<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'imagesView.php';
require_once 'imagesModel.php';
require_once 'Project/Model/Photo_Library/album/album.php';
require_once 'Project/Model/Photo_Library/album/albums.php';
require_once 'Project/Model/Photo_Library/album_size/album_image_size.php';
require_once 'Project/Model/Photo_Library/album_size/album_image_sizes.php';
require_once 'Project/Model/Photo_Library/image/images.php';
require_once 'Project/Model/Photo_Library/image/image.php';
require_once 'Project/2_Enterprise/Code/Applications/Photo_Library/Modules/fileUpload/fileUpload.php';
require_once 'Project/2_Enterprise/Code/Applications/Photo_Library/Modules/simpleImage/SimpleImage.php';

class imagesController extends applicationsSuperController
{
	public function viewAction()
	{
		if(isset($_GET["album_id"]))
		{
			$album_id = $_GET["album_id"];
			
			//get images group by sizes
			$model = new imagesModel();
			$array_of_images = $model->getImages($album_id);
			
			//count the number of images for displaying purposes
			$image_count = 0;
			foreach($array_of_images as $images)			
				$image_count += count($images);
			
			//select album details
			$album = new album();
			$album->setAlbumId($album_id);
			$album->select();

			$view = new imagesView();
			$view->setAlbum($album);
			$view->setImageCount($image_count);
			$view->setArrayOfImages($array_of_images);
			$view->displayMainTemplate();
		}
	}
	
	//---------------------------------------------------------------------------------------------------------------------------
	//upload images using dropzone
	
	public function uploadImageAction()
	{
		if(isset($_POST["fallback_submit"]))
		{
			$this->uploadImageOldWayAction();
		}
		else
		{
			$album_id = $_POST["album_id"];
		
			//get album details
			$album = new album();
			$album->setAlbumId($album_id);
			$album->select();
		
			$album_folder = $album->getAlbumFolder();
		
			//set the accepeted type of image
			$accepted_types = array(
					'image/gif',
					'image/jpeg',
					'image/jpg',
					'image/png',
					'image/bmp',
					'image/tiff'
			);
		
			//set the size id and dimensions
			$size_id 			 = $_POST["size_id"];
			$dimension 		 	 = $_POST["dimension"];
			$exploded_dimensions = explode("x", $dimension);
			$group_size_width 	 = $exploded_dimensions[0];
			$group_size_height	 = $exploded_dimensions[1];
		
			//set the path of the original image
			$path = STAR_SITE_ROOT."/Data/Images/$album_folder/original";
		
			//image details
			$filename  		 = $_FILES['file']['name'];
			$exploded_ext 	 = explode(".", $_FILES['file']['name']);
			$ext 			 = $exploded_ext[count($exploded_ext) - 1];
			$hash_file_name	 = uniqid().rand(2, 1000).".".$ext;
			$image_size		 = getimagesize($_FILES["file"]["tmp_name"]);
			$original_width	 = $image_size[0];
			$original_height = $image_size[1];
		
			$image_details  = $_FILES;
		
			//move the original image in path specified above
			$success = fileUpload::upload($image_details, $path, $hash_file_name, 'file', $accepted_types);
		
			if($success === TRUE)
			{
				//note that we keep aspect ratio of an image
				//save the resize image
				$size_path = STAR_SITE_ROOT."/Data/Images/$album_folder/{$dimension}";
				$this->saveResizeImage( $path."/".$hash_file_name,
				$size_path."/".$hash_file_name,
				$original_width,
				$original_height,
				$group_size_width,
				$group_size_height,
				TRUE );
					
				//save the thumbnail of the image
				$thumb_path = STAR_SITE_ROOT."/Data/Images/$album_folder/{$dimension}_thumb/$hash_file_name";
					
				//save the image thumbnail
				$this->saveResizeImage($path."/".$hash_file_name,
				$thumb_path,
				$original_width,
				$original_height,
				280,
				280,
				TRUE
				);
					
				//insert the image details to database
				$image = new image();
				$image->setAlbumId($album_id);
				$image->setSizeId($size_id);
				$image->setImageTitle("Default Title.");
				$image->setImageCaption("Default image caption.");
				$image->setFilename($filename);
				$image->setFileNameExt($ext);
				$image->setPath($hash_file_name);
				$image->insert();
				
				$response = array("success"    => true,
								  "thumb_path" => "/Data/Images/$album_folder/{$dimension}_thumb/$hash_file_name",
								  "image_id"   => $image->getImageId(),
								  "size_id"	   => $size_id,
								  "dimension"  => $dimension,
								  "filename"   => $filename
				);
				
				echo json_encode($response);
			}
			else
				echo json_encode(array("success" => false));
				
			die;
		}
		
		die("Oops.");
	}
	
	//---------------------------------------------------------------------------------------------------------------------------
	//this is the fallback of the dropzone
	//this is use if the browser of the user is not supported by dropzone. e.g. IE 9
	
	public function uploadImageOldWayAction()
	{
		$album_id = $_POST["album_id"];
		
		//get album details
		$album = new album();
		$album->setAlbumId($album_id);
		$album->select();
		
		$album_folder = $album->getAlbumFolder();
		
		//set the accepeted type of image
		$accepted_types = array(
				'image/gif',
				'image/jpeg',
				'image/jpg',
				'image/png',
				'image/bmp',
				'image/tiff'
		);	
		
		//set the size id and dimensions
		$size_id 			 = $_POST["size_id"];
		$dimensions 		 	 = $_POST["dimension"];
		$exploded_dimensions = explode("x", $dimensions);
		$group_size_width 	 = $exploded_dimensions[0];
		$group_size_height	 = $exploded_dimensions[1];
		
		
		//count first the names in the files array to identify how many image will be uploaded
		for($i = 0;  $i<count($_FILES['photo']['name']);  $i++)
		{
			//set the path of the original image
			$path = STAR_SITE_ROOT."/Data/Images/$album_folder/original";
							
			//image details
			$filename  		 = $_FILES['photo']['name'][$i];
			$exploded_ext 	 = explode(".", $_FILES['photo']['name'][$i]);
			$ext 			 = $exploded_ext[count($exploded_ext) - 1];
			$path_name		 = sha1(md5(time().$_FILES['photo']['name'][$i].rand(1, 1000))).".".$ext;
			$image_size		 = getimagesize($_FILES["photo"]["tmp_name"][$i]);
			$original_width	 = $image_size[0];
			$original_height = $image_size[1];
		
		
			//image details must be in normal form of $_FILES array
			//since the $_FILES is modified to handle multiple uploading of images
			//we should back it to normal form so it can used by fileUpload module
			$image_details  = array(
			"photo" => array(
			"name" 	=> $_FILES['photo']['name'][$i],
							"type" 		=> $_FILES['photo']['type'][$i],
							"tmp_name"  => $_FILES['photo']['tmp_name'][$i],
							"error"		=> $_FILES['photo']['error'][$i],
							"size"		=> $_FILES['photo']['size'][$i]
				)
			);
			
			//move the original image in path specified above
			$success = fileUpload::upload($image_details, $path, $path_name, 'photo', $accepted_types);
			
			if($success === TRUE)
						{
			//note that we keep aspect ratio of an image
			//save the resize image
			$size_path = STAR_SITE_ROOT."/Data/Images/$album_folder/{$dimensions}";
			$this->saveResizeImage($path."/".$path_name, $size_path."/".$path_name, $original_width, $original_height, $group_size_width, $group_size_height, TRUE);
			
			//save the thumbnail of the image
			$thumb_path = STAR_SITE_ROOT."/Data/Images/$album_folder/{$dimensions}_thumb/$path_name";
			
			//save the image thumbnail
			$this->saveResizeImage($size_path."/".$path_name,
									$thumb_path,
									$original_width,
									$original_height,
									280,
									280,
									TRUE
			);
		
			//insert the image details to database
			$image = new image();
			$image->setAlbumId($album_id);
			$image->setSizeId($size_id);
			$image->setImageTitle("Default Title.");
			$image->setImageCaption("Default image caption.");
			$image->setFilename($filename);
			$image->setFileNameExt($ext);
			$image->setPath($path_name);
			$image->insert();
		
			header("Location: /photo_library/images/view?album_id={$album_id}");
		}
		else
			die("original image is not uploaded");
		}
	}	

	//---------------------------------------------------------------------------------------------------------------------------

	public function updateImageAction()
	{
		if(isset($_POST["update_image"]))
		{
			//select the image details
			//this will help also in updating (if you need to update one column only)
			//the property of image class will be filled so you don't have to set it again
			$image = new image();
			$image->setImageId($_POST["image_id"]);
			$image->select();

			$current_album_id 	= $image->getAlbumId();
			$current_size_id  	= $image->getSizeId();

			//the image_sizes is in json format
			//decode it to transform the json in array format
			$decoded_image_size  = json_decode($_POST["image_sizes"], true);
			$size_id 			 = $decoded_image_size["size_id"];
			$dimensions 		 = $decoded_image_size["dimensions"];
			$album_folder		 = $decoded_image_size["album_folder"];
			$album_id 		     = $_POST["album_id"];

			//do the generic updating if the album_id is equal to current album_id
			if($current_album_id == $album_id)
			{
				if($current_size_id == $size_id)
				{
					//just update the caption and title if the user don't want to chnage the file
					$image->setImageCaption($_POST["image_caption"]);
					$image->setImageTitle($_POST["image_title"]);
					$image->update();

					header("Location: /photo_library/images/view?album_id={$image->getAlbumId()}");
				}
				else
				{
					try
					{
						//do this if the user want move the the image in other sizes
						//select the current album image size
						$album_image_size = new album_image_size();
						$album_image_size->setSizeId($current_size_id);
						$album_image_size->select();
						
						//set the new paths of thew image and thumbnals
						$new_image_path      = STAR_SITE_ROOT."/Data/Images/$album_folder/$dimensions/{$image->getPath()}";
						$new_thumb_path      = STAR_SITE_ROOT."/Data/Images/$album_folder/{$dimensions}_thumb/{$image->getPath()}";
						
						//set the current path of the image and thumbnails (it will be use in deleting)
						$original_size_path   = STAR_SITE_ROOT."/Data/Images/$album_folder/{$album_image_size->getDimensions()}/{$image->getPath()}";
						$original_thumb_path  = STAR_SITE_ROOT."/Data/Images/$album_folder/{$album_image_size->getDimensions()}_thumb/{$image->getPath()}";
						
						//set the path original image
						$original_image_path = STAR_SITE_ROOT."/Data/Images/$album_folder/original/{$image->getPath()}";
						
						//check if the user want to keep the aspect ratio
						if(isset($_POST["keep_aspect_ratio"]))
						$keep_aspect_ratio = TRUE;
						else
						$keep_aspect_ratio = FALSE;
						
						//get the the original size of the image
						$image_size		 = getimagesize($original_image_path);
						$original_width	 = $image_size[0];
						$original_height = $image_size[1];
						
						//get the new width and height of the image
						list($new_width, $new_height) = explode("x", $dimensions);
						
						//create the new size of the image and thumbnail
						$this->saveResizeImage($original_image_path, $new_image_path, $original_width, $original_height, $new_width, $new_height, $keep_aspect_ratio);
						
						//save the thumbnail of the image
						$this->saveResizeImage($original_image_path,
						$new_thumb_path,
						$original_width,
						$original_height,
						280,
						280,
						TRUE
						);
						
						//delete the old image
						$data_handler = new dataHandler();
						unlink($original_size_path);
						unlink($original_thumb_path);
							
						//update the table
						$image->setImageCaption($_POST["image_caption"]);
						$image->setImageTitle($_POST["image_title"]);
						$image->setSizeId($size_id);
						$image->update();
						
						header("Location: /photo_library/images/view?album_id={$image->getAlbumId()}");
					}
					catch(Exception $e)
					{
						echo $e->getMessage();	
					}
				}
			}
			//do this if you want to move the image in other albums
			else
			{
				//update the table
				$image->setImageCaption($_POST["image_caption"]);
				$image->setImageTitle($_POST["image_title"]);
				$image->setSizeId($size_id);
				$image->setAlbumId($album_id);
				$image->update();

				//set the new paths of the image and thumbnails
				$new_image_path      = STAR_SITE_ROOT."/Data/Images/$album_folder/$dimensions/{$image->getPath()}";
				$new_thumb_path      = STAR_SITE_ROOT."/Data/Images/$album_folder/{$dimensions}_thumb/{$image->getPath()}";
				$new_original_path   = STAR_SITE_ROOT."/Data/Images/$album_folder/original/{$image->getPath()}";


				//get the current album details to get the source path
				$album = new album();
				$album->setAlbumId($current_album_id);
				$album->select();

				//select the current album image size to get the current album size
				$album_image_size = new album_image_size();
				$album_image_size->setSizeId($current_size_id);
				$album_image_size->select();

				//set the current folders (it will be the source file) and will use in deletion
				$current_original_image_path   =  STAR_SITE_ROOT."/Data/Images/{$album->getAlbumFolder()}/original/{$image->getPath()}";
				$current_size_image_path       =  STAR_SITE_ROOT."/Data/Images/{$album->getAlbumFolder()}/{$album_image_size->getDimensions()}/{$image->getPath()}";
				$current_thumbnail_path 	   =  STAR_SITE_ROOT."/Data/Images/{$album->getAlbumFolder()}/{$album_image_size->getDimensions()}_thumb/{$image->getPath()}";

				//check if the user want to keep the aspect ratio
				if(isset($_POST["keep_aspect_ratio"]))
				$keep_aspect_ratio = TRUE;
				else
				$keep_aspect_ratio = FALSE;

				//get the the current original size of the image
				$image_size		 = getimagesize($current_original_image_path);
				$original_width	 = $image_size[0];
				$original_height = $image_size[1];

				//get the new width and height of the image
				list($new_width, $new_height) = explode("x", $dimensions);

				//create the new size of the image and thumbnail
				//the first params in saveResizeImage must be the original image to avoid distortion
				copy($current_size_image_path, $new_original_path);
				$this->saveResizeImage($current_size_image_path, $new_image_path, $original_width, $original_height, $new_width, $new_height, $keep_aspect_ratio);

				//save the thumbnail of the image
				$this->saveResizeImage($current_size_image_path,
				$new_thumb_path,
				$original_width,
				$original_height,
				280,
				280,
				TRUE
				);

				//delete the left image in the current album
				unlink($current_original_image_path);
				unlink($current_size_image_path);
				unlink($current_thumbnail_path);

				header("Location: /photo_library/images/view?album_id={$image->getAlbumId()}");
			}
		}
		else
		header("Location: /");
	}


	
	
	//---------------------------------------------------------------------------------------------------------------------------

	public function updateImageThumbnailAction()
	{
		//this method is not actually updating the image thumbnail
		//it will crop the part of the image and replace the old one
		$image_id = $_POST["image_id"];

		//get the image details
		$model    	   = new imagesModel();
		$image_details = $model->getImageDetails($image_id);

		//set the original image path
		$original_image_path = STAR_SITE_ROOT."/Data/Images/{$image_details["album_folder"]}/{$image_details["dimensions"]}/{$image_details["path"]}";
		//set the path of the thumbnail
		$thumnail_image_path = STAR_SITE_ROOT."/Data/Images/{$image_details["album_folder"]}/{$image_details["dimensions"]}_thumb/{$image_details["path"]}";

		$width  = $_POST["width"];
		$height = $_POST["height"];

		//override the existing thumbnail image
		//create the image size of the image
		$simpleImage = new SimpleImage();
		$simpleImage->load($original_image_path);
		$simpleImage->saveCropImage($thumnail_image_path,
		$_POST['x-coordinate1'],
		$_POST['y-coordinate1'],
		$width,
		$height,
		$width,
		$height
		);
		
		//resize the image to became a thumbnail
		$this->saveResizeImage($thumnail_image_path,
		$thumnail_image_path,
		$width,
		$height,
		280,
		280,
		TRUE
		);

		header("Location: /photo_library/images/view?album_id={$image_details["album_id"]}");
	}

	//---------------------------------------------------------------------------------------------------------------------------

	public function cropImageAction()
	{
		if(isset($_POST["crop_image"]))
		{
			$image_id   = $_POST["image_id"];
			$album_id   = $_POST["album_id"];
			$dimensions = $_POST["width"]."x".$_POST["height"];

			//get the image details
			$model    	   = new imagesModel();
			$image_details = $model->getImageDetails($image_id);

			$image_size_path  = STAR_SITE_ROOT.'/Data/Images/'.$image_details["album_folder"].'/'.$dimensions;
			$image_thumb_path = STAR_SITE_ROOT.'/Data/Images/'.$image_details["album_folder"].'/'.$dimensions.'_thumb';
				
			$original_image_path = STAR_SITE_ROOT.'/Data/Images/'.$image_details["album_folder"].'/'.$image_details["dimensions"].'/'.$image_details["path"];
				
			//check if there is existing album size on current album
			$image_size_details = $model->selectImageSizeByAlbumIdAndDimensions($album_id, $dimensions);
				
			//check if there is existing dimensions in that album
			if($image_size_details == FALSE)
			{
				//add album in Data/Images folder
				$data_handler = new dataHandler();

				//replace spaces in folder names with underscores so there won't be a problem with the URLs
				$data_handler->create_directory($image_size_path);
				$data_handler->create_directory($image_thumb_path);

				//save the size to database
				$album_image_size  = new album_image_size();
				$album_image_size->setAlbumId($image_details["album_id"]);
				$album_image_size->setDimensions($dimensions);
				$album_image_size->insert();
				$size_id = $album_image_size->getSizeId();
			}
			else
			$size_id = $image_size_details["size_id"];
				
				
			$targ_w = $_POST["width"];
			$targ_h = $_POST["height"];
				
			//set the file name of the image
			$filename = sha1(md5($image_details["path"].rand(1, 1000).time("Y-m-d H:i:s"))).".".$image_details["filename_ext"];
				
			//we treat a crop image as a uploaded folder
			//copy the original image of the source file
			//we know that one images has 3 image file in different folder
			//image_size folder image_size_thumb folder and the orignal folder
			//this is to avoid errors in moving a crop image to other folders
			$path = STAR_SITE_ROOT.'/Data/Images/'.$image_details["album_folder"].'/original/';
			copy($path.$image_details["path"], $path.$filename);

			//save the image to group size folder
			$simpleImage = new SimpleImage();
			$simpleImage->load($original_image_path);
			$simpleImage->saveCropImage($image_size_path."/".$filename,
			$_POST['x-coordinate1'],
			$_POST['y-coordinate1'],
			$targ_w,
			$targ_h,
			$_POST['width'],
			$_POST['height']
			);
				
			//save the image thumbnail
			$this->saveResizeImage($image_size_path."/".$filename,
			$image_thumb_path."/".$filename,
			$_POST['width'],
			$_POST['height'],
			280,
			280,
			TRUE
			);
				
			//insert the image details to database
			$image = new image();
			$image->setAlbumId($album_id);
			$image->setSizeId($size_id);
			$image->setImageTitle("Default Title.");
			$image->setImageCaption("Default image caption.");
			$image->setFilename($image_details["filename"]);
			$image->setFileNameExt($image_details["filename_ext"]);
			$image->setPath($filename);
			$image->insert();
				
			header("Location: /photo_library/images/view?album_id={$image_details["album_id"]}");
				
		}
	}

	//---------------------------------------------------------------------------------------------------------------------------

	public function deleteImageAction()
	{
		if(isset($_POST["delete_photo"]))
		{
			$image_id = $_POST["image_id"];
				
			$model = new imagesModel();
			$image_details = $model->getImageDetails($image_id);
				
			unlink(STAR_SITE_ROOT."/Data/Images/{$image_details['album_folder']}/original/{$image_details["path"]}");
			unlink(STAR_SITE_ROOT."/Data/Images/{$image_details['album_folder']}/{$image_details["dimensions"]}/{$image_details["path"]}");
			unlink(STAR_SITE_ROOT."/Data/Images/{$image_details['album_folder']}/{$image_details["dimensions"]}_thumb/{$image_details["path"]}");
		
			$image = new image();
			$image->setImageId($image_id);
			$image->delete();
	
			header("Location: /photo_library/images/view?album_id={$image_details["album_id"]}");
		}
	}

	//---------------------------------------------------------------------------------------------------------------------------

	private function saveResizeImage($original_path, $new_path, $original_width, $original_height, $new_width, $new_height, $keep_aspect_ratio)
	{
		$simpleImage = new SimpleImage();
		$simpleImage->load($original_path);

		//do this to if you want to keep the aspect ratio
		if($keep_aspect_ratio)
		{

			//just copy the file, if it didnt accept the condition to avoid distortion
			if($original_width > $new_width)
			{
				$simpleImage->resizeToWidth($new_width);
				$simpleImage->save($new_path);
			}
			else
			copy($original_path, $new_path);

			//just copy the file, if it didnt accept the condition to avoid distortion
			if($original_height > $new_height)
			{
				$simpleImage->resizeToWidth($new_height);
				$simpleImage->save($new_path);
			}
			else
			copy($original_path, $new_path);
		}
		else
		{
			//do the backdoor of resizing the image
			$simpleImage->resize($new_width, $new_height);
			$simpleImage->save($new_path);
		}
	}




}