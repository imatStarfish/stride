<?php

require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class album_image_size
{
	public $size_id;
	public $dimensions;
	public $album_id;
	
	public function setSizeId($size_id)
	{
		$this->size_id = $size_id;
	}
	
	public function setDimensions($dimensions)
	{
		$this->dimensions = $dimensions;
	}
	
	public function setAlbumId($album_id)
	{
		$this->album_id = $album_id;
	}
	
	//-------------------get function--------------------
	
	public function getSizeId()
	{
		return $this->size_id;
	}
	
	public function getDimensions()
	{
		return $this->dimensions;
	}
	
	public function getAlbumId()
	{
		return $this->album_id;
	}
	
	//-------------------------------------------------------
	
	public function select()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						*
					FROM
						album_image_sizes
					WHERE
						size_id = :size_id
					";
					
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':size_id',$this->size_id,	PDO::PARAM_INT);
			$pdo_statement->execute();
			
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			
			$this->size_id		=	$result['size_id'];
			$this->dimensions	=	$result['dimensions'];
			$this->album_id		=	$result['album_id'];
		}
		catch(PDOException $pdoe)
		{
			throw new PDOException($pdoe);
		}
	}
	
	public function insert()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
			
			$sql = "INSERT INTO
						album_image_sizes
						(
							size_id,
							dimensions,
							album_id
						)
						VALUES
						(
							:size_id,
							:dimensions,
							:album_id
						)
					";
					
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':size_id',$this->size_id,			PDO::PARAM_INT);
			$pdo_statement->bindParam(':dimensions',$this->dimensions,		PDO::PARAM_INT);
			$pdo_statement->bindParam(':album_id',$this->album_id,			PDO::PARAM_INT);
			$pdo_statement->execute();
			
			$this->size_id  = $pdo_connection->lastInsertId();
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new PDOException($pdoe);
		}
	}
	
	public function update()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "UPDATE
						album_image_sizes
					SET
						dimensions = :dimensions,
						album_id   = :album_id
					WHERE
						size_id    = :size_id
					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':size_id',$this->size_id,			PDO::PARAM_INT);
			$pdo_statement->bindParam(':dimensions',$this->dimensions,		PDO::PARAM_INT);
			$pdo_statement->bindParam(':album_id',$this->album_id,			PDO::PARAM_INT);
			$pdo_statement->execute();
		}
		catch(PDOException $pdoe)
		{
			throw new PDOException($pdoe);
		}
	}
	
	public function delete()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
			
			$sql = "DELETE FROM
							album_image_sizes
					WHERE
							size_id = :size_id
					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':size_id',$this->size_id,		PDO::PARAM_INT);
			$pdo_statement->execute();
			
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new PDOException($pdoe);
		}
	}
	
	public function deleteByColumn($column, $value)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "DELETE FROM
							album_image_sizes
						WHERE
			$column = :$column
						";
				
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(":$column", $value, PDO::PARAM_STR);
			$pdo_statement->execute();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
}

?>