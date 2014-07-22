<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class products_query_helper
{
	
	public $limit;
	public $category_type;
	public $is_published;
	public $category;
	public $order_by;
	public $product_type;

	/*
	 * @property is_publish(boolean) tells whether if the article to be select is publish or not
	 * @property limit(bollean/int) if its false there is no limit, if its an integer we already knew this
	 * @property category(array) array consisting of category details, it could be only array("permalink" =>"sample-permalink")
	 * or array("article_category_id" => 1)
	 * @property category_type , type of category e.g. news, events, lates update
	 *  
	 */
	
	public function selectAllProducts()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						a.*,
						r.*,
						ac.product_category_id,
						ac.parent_id,
						ac.category_type,
						ac.category_name,
						ac.metadata,
						ac.description,
						ac.permalink as category_permalink,
						i.image_title,
						i.image_caption,
						CONCAT('/Data/Images/', ab.album_folder, '/', ais.dimensions, '/', i.path) as image_path
					FROM
						products a
					INNER JOIN
						route r
					ON
						a.route_id = r.route_id
					LEFT JOIN
						product_categories ac
					ON
						ac.product_category_id = a.product_category_id
					LEFT JOIN
						images i
					ON
						a.image_id = i.image_id
					LEFT JOIN
						albums ab
					ON
						i.album_id = ab.album_id
					LEFT JOIN
						album_image_sizes ais
					ON
						i.size_id = ais.size_id 
					WHERE
						1=1 ";
			
			
			//select the publish/not-publish article
			if($this->is_published)
				$sql .= "AND is_publish = '1' ";
			
			
			//select the carticle within this category
			if($this->category != NULL)
			{
				if(isset($this->category["permalink"]))				
					$sql .= " AND ac.permalink = :permalink ";
				else
					$sql .= " AND ac.product_category_id = :product_category_id";
			}
			
			//select article by type
			if($this->category_type)
				$sql .= " AND category_type = '$this->category_type' ";
			
			//select article by article_type
			if($this->product_type)
				$sql .= " AND product_type = '$this->product_type' ";
			
			//select articles order by
			if($this->order_by)
				$sql .= " ORDER BY $this->order_by ";
			else
				$sql .= " ORDER BY date_created";
				
			//set the limit of selected articles
			if($this->limit)
				$sql .= " LIMIT $this->limit ";

			
			$pdo_statement = $pdo_connection->prepare($sql);
			
			//bind parameters of selected category
			if($this->category != NULL)
			{
				if(isset($this->category["permalink"]))
					$pdo_statement->bindParam(":permalink", $this->category["permalink"], PDO::PARAM_STR);
				else
					$pdo_statement->bindParam(":product_category_id", $this->category["product_category_id"], PDO::PARAM_STR);
			}
		
			$pdo_statement->execute();
			
			return $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}

	//--------------------------------------------------------------------------------------------------
	
	public static function selectByColumn($column, $value)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						a.*,
						a.metadata as product_metadata,
						r.route_id,
						r.page_id,
						r.permalink as product_permalink,
						ac.*,
						i.image_title,
						i.image_caption,
						CONCAT('/Data/Images/', ab.album_folder, '/', ais.dimensions, '/', i.path) as image_path,
						CONCAT('/Data/Images/', ab.album_folder, '/', ais.dimensions, '_thumb/', i.path) as image_thumb
					FROM
						products a
					INNER JOIN
						route r
					ON
						a.route_id = r.route_id
					LEFT JOIN
						product_categories ac
					ON
						ac.product_category_id = a.product_category_id
					LEFT JOIN
						images i
					ON
						a.image_id = i.image_id
					LEFT JOIN
						albums ab
					ON
						i.album_id = ab.album_id
					LEFT JOIN
						album_image_sizes ais
					ON
						i.size_id = ais.size_id 
					WHERE
						$column = :".str_replace(".", "", $column)."
			";
			
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(":".str_replace(".", "", $column), $value, PDO::PARAM_STR);
			$pdo_statement->execute();
		
			return $pdo_statement->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
}