<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'article_category.php';

class article_categories
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
						(SELECT count(*) FROM articles WHERE article_category_id = ac.article_category_id) as article_count,
						(SELECT category_name FROM article_categories WHERE article_category_id = ac.parent_id) as parent_name
					FROM
						article_categories ac
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
                $article_category = new article_category();
                $article_category->article_category_id = $result["article_category_id"];
                $article_category->parent_id 		   = $result["parent_id"];
                $article_category->category_type 	   = $result["category_type"];
                $article_category->category_name  	   = $result["category_name"];
                $article_category->description 		   = $result["description"];
                $article_category->metadata 		   = $result["metadata"];
                $article_category->permalink 		   = $result["permalink"];
                $article_category->parent_name 		   = $result["parent_name"];
                $article_category->article_count 	   = $result["article_count"];
                $this->categories[] = $article_category;
            }
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
}

?>
