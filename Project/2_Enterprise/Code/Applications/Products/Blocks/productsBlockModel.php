<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class productsBlockModel
{
	public function selectproductCategories()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "SELECT
						parent_id,
						A.*
					FROM
						product_categories	A
					";
		
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
		
			$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
			
			$parent_ids 	= $results[0];
			
			
			$grouped_categories = array();
			
			
			foreach($parent_ids as $main_category)
			{
				$grouped_categories[$main_category["product_category_id"]]	 = array(
					"category_information" => $main_category
				);
			
				if(array_key_exists($main_category["product_category_id"], $parent_ids))
				{
					foreach($results[$main_category["product_category_id"]] as $subcategory)
					{
						$grouped_categories[$main_category["product_category_id"]]["subcategory_information"][$subcategory["product_category_id"]] = $subcategory;
					}
				}
			}
			
			
			return $grouped_categories;
			
			
			/* 
			 * NOTE : uncomment this to view the grouped category
			 * 
			 * $all_categories = $this->selectAllCategories();
			 * $list = "";
			foreach($parent_ids as $parent_id)
			{
				$list .= "<ul>";
				$list .= "<li><a href='#'>".$all_categories[$parent_id["product_category_id"]][0]["category_name"]."</a></li>";
				
				if(array_key_exists($parent_id["product_category_id"], $parent_ids))
				{
					$list .= "<li>";
					$list .= "<ul>";
					foreach($results[$parent_id["product_category_id"]] as $subcategory)
					{
						$list .= "<li><a href='#'>".$all_categories[$subcategory["product_category_id"]][0]["category_name"]."</a></li>";
					}
					$list .= "</ul>";
					$list .= "</li>";
				} 
				
				$list .= "</ul>";
			} 
			
			echo $list;
			*/
			
		}
		catch(PDOException $e)
		{
			echo "<pre>".$e->getMessage();
		}
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	public function groupCategory($original_source, $current_source)
	{
		foreach($current_source as $id)
		{
					
		}
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	//this method is use for debugging
	private function selectAllCategories()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "SELECT
						product_category_id,
						A.*
					FROM
						product_categories	A	
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
			
			$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
			return $results;
		}
		catch(PDOException $e)
		{
			echo "<pre>".$e->getMessage();
		}
	}

}