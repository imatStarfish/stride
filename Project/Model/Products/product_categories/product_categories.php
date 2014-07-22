<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'product_category.php';

class product_categories
{
    public $categories;
    
    //---------------------------------------------------------------------------------------------------------------------

    public function select($category_type = NULL)
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT	
						ac.*,
						(SELECT count(*) FROM products WHERE product_category_id = ac.product_category_id) as product_count,
						(SELECT category_name FROM product_categories WHERE product_category_id = ac.parent_id) as parent_name
					FROM
						product_categories ac
            ";
            
            if($category_type)
            {
            	$sql .= " WHERE category_type = :category_type ";
            }
            
            $pdo_statement = $pdo_connection->prepare($sql);
            
            if($category_type)
            {
            	$pdo_statement->bindParam(":category_type", $category_type, PDO::PARAM_STR);
            }
            
            $pdo_statement->execute();
            
            $results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

            foreach($results as $result)
            {
                $product_category = new product_category();
                
                $product_category->product_category_id = $result["product_category_id"];
                $product_category->parent_id 		   = $result["parent_id"];
                $product_category->category_type 	   = $result["category_type"];
                $product_category->category_name  	   = $result["category_name"];
                $product_category->description 		   = $result["description"];
                $product_category->metadata 		   = $result["metadata"];
                $product_category->permalink 		   = $result["permalink"];
                $product_category->parent_name 		   = $result["parent_name"];
                $product_category->product_count 	   = $result["product_count"];
                $this->categories[] = $product_category;
            }
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
}

?>
