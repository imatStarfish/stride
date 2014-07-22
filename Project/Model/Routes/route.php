<?php 
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
class route{	private $route_id;	private $permalink;	private $page_id;
//-------------------------------------------------------------------------------------------------		public function __get($field)	{		if(property_exists($this, $field)) return $this->{$field};		else return NULL;	}
//-------------------------------------------------------------------------------------------------		public function getRouteId()	{		if(property_exists($this, 'route_id')) 			return $this->route_id;		else 			return FALSE;	}//-------------------------------------------------------------------------------------------------		public function getPermalink()	{		if(property_exists($this, 'permalink')) 			return $this->permalink;		else 			return FALSE;	}//-------------------------------------------------------------------------------------------------		public function getPageId()	{		if(property_exists($this, 'page_id')) 			return $this->page_id;		else			return NULL;	}//-------------------------------------------------------------------------------------------------	
	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
//-------------------------------------------------------------------------------------------------	public function setRouteId($route_id)	{		if (property_exists($this, 'route_id'))			$this->route_id	=	$route_id;		else			return FALSE;	}		public function setPermalink($permalink)	{		if (property_exists($this, 'permalink'))			$this->permalink	=	$permalink;		else			return FALSE;	}		public function setPageId($page_id)	{		if (property_exists($this, 'page_id'))			$this->page_id	=	$page_id;		else			return FALSE;	}
	public function select()	{		try		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "SELECT						*					FROM						route					WHERE						route_id = :route_id					";
			$pdo_statement = $pdo_connection->prepare($sql);			$pdo_statement->bindParam(':route_id', $this->route_id, PDO::PARAM_INT);			$pdo_statement->execute();
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);			$this->route_id		=	$result['route_id'];			$this->permalink	=	$result['permalink'];			$this->page_id		=	$result['page_id'];
		}		catch(PDOException $pdoe)		{			throw new Exception($pdoe);		}	}
//-------------------------------------------------------------------------------------------------
	public function ifPermalinkExists($permalink, $route_id = NULL)	{		try		{			$pdo_connection = starfishDatabase::getConnection();
			$sql = "SELECT						*					FROM						route r					WHERE						permalink = :permalink					";			if($route_id)				$sql .= " AND route_id != :route_id ";			$pdo_statement = $pdo_connection->prepare($sql);
			if($route_id)				$pdo_statement->bindParam(':route_id', $route_id, PDO::PARAM_INT);						$pdo_statement->bindParam(':permalink', $permalink, PDO::PARAM_INT);			$pdo_statement->execute();
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			$this->route_id		=	$result['route_id'];			$this->permalink	=	$result['permalink'];			$this->page_id		=	$result['page_id'];					}		catch(PDOException $pdoe)		{			throw new Exception($pdoe);		}
	}
//-------------------------------------------------------------------------------------------------
	public function insert()	{		try		{			$pdo_connection = starfishDatabase::getConnection();
			$sql = "INSERT INTO						route						(							permalink,							page_id						)					VALUES						(							:permalink,							:page_id						)					";
			$pdo_statement = $pdo_connection->prepare($sql);			$pdo_statement->bindParam(':permalink', $this->permalink, PDO::PARAM_STR);			$pdo_statement->bindParam(':page_id', $this->page_id, PDO::PARAM_STR);			$pdo_statement->execute();
			return $this->route_id = $pdo_connection->lastInsertId();		}		catch(PDOException $pdoe)		{			throw new Exception($pdoe);		}	}
//-------------------------------------------------------------------------------------------------
	public function update()	{		try 		{			$pdo_connection = starfishDatabase::getConnection();			$sql = "UPDATE						route					SET						permalink	= :permalink					WHERE						route_id = :route_id					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':permalink', $this->permalink, PDO::PARAM_STR);			$pdo_statement->bindParam(':route_id', $this->route_id, PDO::PARAM_STR);			$pdo_statement->execute();		}		catch(PDOException $pdoe)		{			throw new Exception($pdoe);		}	}		
//-------------------------------------------------------------------------------------------------
	public function delete()	{		try		{			$pdo_connection = starfishDatabase::getConnection();
			$sql = "DELETE FROM						route					WHERE						route_id = :route_id					";
			$pdo_statement = $pdo_connection->prepare($sql);			$pdo_statement->bindParam(':route_id', $this->route_id, PDO::PARAM_INT);			$pdo_statement->execute();		}		catch(PDOException $pdoe)		{			throw new Exception($pdoe);		}	}				public function updatePageId()	{		try		{			$pdo_connection = starfishDatabase::getConnection();			$sql = "	UPDATE							route						SET							page_id		= :page_id						WHERE							route_id = :route_id					";				$pdo_statement = $pdo_connection->prepare($sql);				//bindParam is used so that SQL inputs are escaped.			//This is to prevent SQL injections!				$pdo_statement->bindParam(':page_id', $this->page_id, PDO::PARAM_STR);			$pdo_statement->bindParam(':route_id', $this->route_id, PDO::PARAM_STR);			$pdo_statement->execute();		}		catch(PDOException $pdoe)		{			throw new Exception($pdoe);		}	}			}
?>