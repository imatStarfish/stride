<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';


class albumsModel extends applicationsSuperController
{
	public function getAlbumsWithImagePreview()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "SELECT
							a.album_id,
							a.album_title,
							a.album_folder,
							s.size_id,
							s.dimensions,
							i.image_id,
							i.image_title,
							i.image_caption,
							i.filename,
							i.filename_ext,
							i.date_created,
							i.path			
						FROM
							albums a
						LEFT JOIN
							album_image_sizes s
						ON 
							a.album_id = s.album_id
						LEFT JOIN
							images i
						ON	
							i.size_id = s.size_id
				";
				
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
			$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
			
			$new_albums_array = array();
			
			//remove the duplicate albums and gets the preview
			foreach($results as $result)
			{
				if(!array_key_exists($result["album_id"], $new_albums_array))
					$new_albums_array[$result["album_id"]] = $result;
				else
				{
					if($result["image_id"] != NULL)
						$new_albums_array[$result["album_id"]] = $result;
				}
			}
			
			return $new_albums_array;
		}
		catch(PDOException $e)
		{
				
		}
	}
	
	
}