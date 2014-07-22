<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'article.php';

class articles
{
    public $array_of_articles;

    //---------------------------------------------------------------------------------------------------------------------

    public function select()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT
                            *
                        FROM
                            articles";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->execute();
            $results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

            foreach($results as $result)
            {
                $article = new article();

                $article->article_id 		  = $result["article_id"];
                $article->route_id 			  = $result["route_id"];
                $article->article_category_id = $result["article_category_id"];
                $article->article_title 	  = $result["article_title"];
                $article->content 			  = $result["content"];
                $article->author 			  = $result["author"];
                $article->is_publish 		  = $result["is_publish"];
                $article->date_created 		  = $result["date_created"];
                $article->date_modified 	  = $result["date_modified"];
                $article->metadata 			  = $result["metadata"];
                $article->downloadables_path  = $result["downloadables_path"];
                $article->image_id 			  = $result["image_id"];
                $this->array_of_articles[] 	  =	$article;

            }
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
}

?>
