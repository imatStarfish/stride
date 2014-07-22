<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class photo_libray2BlockModel
{
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
}