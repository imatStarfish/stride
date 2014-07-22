<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class article_category
{
    public $article_category_id;
    public $parent_id;
    public $category_type;
    public $category_name;
    public $description;
    public $metadata;
    public $permalink;
    
    public $parent_name;
    public $article_count;

    //---------------------------------------------------------------------------------------------------------------------

    public function insert()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "INSERT INTO
                        article_categories
                        (
                            parent_id,
                            category_type,
                            category_name,
                            description,
                            metadata,
                            permalink
                        )
                    VALUES
                        (
                            :parent_id,
                            :category_type,
                            :category_name,
                            :description,
                            :metadata,
                            :permalink
                        )";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":parent_id", $this->parent_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":category_type", $this->category_type, PDO::PARAM_STR);
            $pdo_statement->bindParam(":category_name", $this->category_name, PDO::PARAM_STR);
            $pdo_statement->bindParam(":description", $this->description, PDO::PARAM_STR);
            $pdo_statement->bindParam(":metadata", $this->metadata, PDO::PARAM_STR);
            $pdo_statement->bindParam(":permalink", $this->permalink, PDO::PARAM_STR);
            $pdo_statement->execute();

            $this->article_category_id = $pdo_connection->lastInsertId();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }

    //---------------------------------------------------------------------------------------------------------------------

    public function select($permalink = FALSE)
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT
                            *
                        FROM
                            article_categories
                        WHERE
                            article_category_id = :article_category_id";
            
            
            if($permalink != FALSE)
            {
            	$sql .= " OR permalink=:permalink ";
            }

            $pdo_statement = $pdo_connection->prepare($sql);
            
            
            if($permalink != FALSE)
            {
            	$pdo_statement->bindParam(":permalink", $permalink, PDO::PARAM_STR);
            }
            
            $pdo_statement->bindParam(":article_category_id", $this->article_category_id, PDO::PARAM_INT);
            $pdo_statement->execute();
            $result = $pdo_statement->fetch(PDO::FETCH_ASSOC);

            $this->article_category_id = $result["article_category_id"];
            $this->parent_id = $result["parent_id"];
            $this->category_type = $result["category_type"];
            $this->category_name = $result["category_name"];
            $this->description = $result["description"];
            $this->metadata = $result["metadata"];
            $this->permalink = $result["permalink"];
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }

    //---------------------------------------------------------------------------------------------------------------------

    public function update()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "UPDATE
                        article_categories
                    SET
                        parent_id = :parent_id,
                        category_name = :category_name,
                        category_type = :category_type,
                        description = :description,
                        metadata = :metadata,
                        permalink = :permalink 
                    WHERE
                        article_category_id = :article_category_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":article_category_id", $this->article_category_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":parent_id", $this->parent_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":category_name", $this->category_name, PDO::PARAM_STR);
            $pdo_statement->bindParam(":category_type", $this->category_type, PDO::PARAM_STR);
            $pdo_statement->bindParam(":description", $this->description, PDO::PARAM_STR);
            $pdo_statement->bindParam(":metadata", $this->metadata, PDO::PARAM_STR);
            $pdo_statement->bindParam(":permalink", $this->permalink, PDO::PARAM_STR);
            $pdo_statement->execute();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }

    //---------------------------------------------------------------------------------------------------------------------

    public function delete()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "DELETE FROM
                            article_categories
                        WHERE
                            article_category_id = :article_category_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":article_category_id", $this->article_category_id, PDO::PARAM_INT);
            $pdo_statement->execute();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
    
    //---------------------------------------------------------------------------------------------------------------------

    public function updateOneColumn($column, $value)
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "UPDATE
                            article_categories
                        SET
                            $column = :$column
                        WHERE
                            article_category_id = :article_category_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":article_category_id", $this->article_category_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":$column", $value, PDO::PARAM_INT);
            $pdo_statement->execute();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
}

?>
