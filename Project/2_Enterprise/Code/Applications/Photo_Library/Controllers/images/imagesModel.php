<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';


class imagesModel extends applicationsSuperController
{
	public function getImages($album_id)
	{
		try 
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "SELECT
						CONCAT(s.dimensions,'_',s.size_id),
						i.*
					FROM
						album_image_sizes s
					LEFT JOIN	
						albums a
					ON
						s.album_id = a.album_id
					LEFT JOIN
						images i
					ON	
						s.size_id = i.size_id
					WHERE
						a.album_id = :album_id
			";
		
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(":album_id", $album_id, PDO::PARAM_INT);
			$pdo_statement->execute();
			
			$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
			return $results;
		}
		catch(PDOException $e)
		{
			echo "<pre>".$e->getMessage();
		}
	}
	
	//-----------------------------------------------------------------------------------------------------------
	
// 	public function buildImagesArray($array_of_images)
// 	{
// 		$new_images_array = array();
// 		foreach($array_of_images as $images)
// 		{
// 			if(!array_key_exists('{"dimensions":"'.$images["dimensions"].'","size_id":"'.$images["size_id"].'"}', $new_images_array))
// 				$new_images_array['{"dimensions":"'.$images["dimensions"].'","size_id":"'.$images["size_id"].'"}'] = array($images["image_id"] => $images);
// 			else
// 				$new_images_array['{"dimensions":"'.$images["dimensions"].'","size_id":"'.$images["size_id"].'"}'][$images["image_id"]] = $images;
// 		}

// 		return $new_images_array;
// 	}
	
	//-----------------------------------------------------------------------------------------------------------
	
	public function getImageDetails($image_id)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "SELECT
							 i. * , 
							 s. * ,
							 a. *
						FROM
						   images i
						LEFT JOIN 
					   		album_image_sizes s 
					   	ON
					   		 i.size_id = s.size_id
					   	LEFT JOIN
					   		albums a
					   	ON
					   		a.album_id = i.album_id
						WHERE
							 i.image_id = :image_id
						";
	
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(":image_id", $image_id, PDO::PARAM_INT);
			$pdo_statement->execute();
				
			$results = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			return $results;
		}
		catch(PDOException $e)
		{
			echo "<pre>".$e->getMessage();
		}
	}
	
	//-----------------------------------------------------------------------------------------------------------

	public function selectImageSizeByAlbumIdAndDimensions($album_id, $dimensions)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "SELECT
						*
					FROM	
						album_image_sizes
					WHERE
						album_id = :album_id
					AND 
						dimensions = :dimensions
								";
		
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(":album_id", $album_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(":dimensions", $dimensions, PDO::PARAM_INT);
			$pdo_statement->execute();
		
			$results = $pdo_statement->fetch(PDO::FETCH_ASSOC);

			if($results)
				return $results;
			else
				return FALSE;			
		}
		catch(PDOException $e)
		{
			echo "<pre>".$e->getMessage();
		}
	}
	
	
}