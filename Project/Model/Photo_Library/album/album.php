<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class album
{
	private $album_id;
	private $album_title;
	private $album_folder;

	
	//setter
	//---------------------------------------------------------------------------------------------------------------------------
	
	public function setAlbumId($album_id)
	{
		$this->album_id = $album_id;
	}
	
	public function setAlbumTitle($album_title)
	{
		$this->album_title = $album_title;
	}
	
	public function setAlbumFolder($album_folder)
	{
		$this->album_folder = $album_folder;
	}
	
	//getter
	//---------------------------------------------------------------------------------------------------------------------------

	public function getAlbumId()
	{
		return $this->album_id;
	}
	
	public function getAlbumTitle()
	{
		return $this->album_title;
	}
	
	public function getAlbumFolder()
	{
		return $this->album_folder;
	}
	
	
	//---------------------------------------------------------------------------------------------------------------------------

	public function select()
	{
		try
		{

			$pdo_connection = starfishDatabase::getConnection();

			$sql = "SELECT
						*
					FROM
						albums
					WHERE
						album_id = :album_id
					";

			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
			$pdo_statement->execute();

			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);

			$this->album_id		=	$result['album_id'];
			$this->album_title	=	$result['album_title'];
			$this->album_folder	=	$result['album_folder'];

		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	//---------------------------------------------------------------------------------------------------------------------------
	
	public function insert()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "INSERT INTO
						albums
						(
							`album_title`,
							`album_folder`
						)
						VALUES
						(
							:album_title,
							:album_folder
						)
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':album_title', $this->album_title, PDO::PARAM_STR);
			$pdo_statement->bindParam(':album_folder', $this->album_folder, PDO::PARAM_STR);
			$pdo_statement->execute();
			
			$this->album_id = $pdo_connection->lastInsertId();
				
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}

	//---------------------------------------------------------------------------------------------------------------------------

	public function update()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "UPDATE
						albums
					SET
						album_title		= :album_title
					WHERE
						album_id	= :album_id
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':album_title', $this->album_title, PDO::PARAM_STR);
			$pdo_statement->execute();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}

	//---------------------------------------------------------------------------------------------------------------------------

	public function delete()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "DELETE FROM
						albums
					WHERE
						album_id = :album_id
					";
				
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
			$pdo_statement->execute();
	
			return TRUE;
		}
		catch(PDOException $pdoe)
		{
			return FALSE;
		}
	}

}