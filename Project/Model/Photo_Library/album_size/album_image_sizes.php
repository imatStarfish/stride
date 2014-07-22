<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'album_image_size.php';

class album_image_sizes
{
	private $array_of_album_image_sizes;
	
	//----------------------------------------------------------------------

	public function setArrayOfAlbumImageSizes($array_of_album_image_sizes)
	{
		$this->array_of_album_image_sizes = $array_of_album_image_sizes;
	}
	
	public function getArrayOfAlbumImageSizes()
	{
		return $this->array_of_album_image_sizes;
	}
	
	
	//----------------------------------------------------------------------
	
	
	public function select($album_id = FALSE)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						*
					FROM
						album_image_sizes
					";
			
			if($album_id)
				$sql .= " WHERE album_id = $album_id ";
				
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
			
			$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($results as $result)
			{
				$album_image_size = new album_image_size();
				
				$album_image_size->setSizeId($result['size_id']);
				$album_image_size->setDimensions($result['dimensions']);
				$album_image_size->setAlbumId($result['album_id']);
				
				$this->array_of_album_image_sizes[] = $album_image_size;
			}
		}
		catch(PDOException $pdoe)
		{
			throw new PDOException($pdoe);
		}
	}
}

?>