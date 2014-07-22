<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'album.php';

class albums
{
	private $array_of_albums = array();

	//--------------------------------------------------------------------------------------------------
	
	public function getArrayOfAlbums()	
	{
		return $this->array_of_albums;
	}
	
	//--------------------------------------------------------------------------------------------------
	
	public function select()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						*
					FROM
						albums
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				$pdo_statement->execute();
			
				$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($results as $result)
				{
					$album	= new album();
					
					$album->setAlbumId($result["album_id"]);
					$album->setAlbumFolder($result["album_folder"]);
					$album->setAlbumTitle($result["album_title"]);

					$this->array_of_albums[] = $album;
				}
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
}