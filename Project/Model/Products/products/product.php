<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class product
{
    public $product_id;
    public $route_id;
    public $product_category_id;
    public $product_type;
    public $product_title;
    public $product_price;
    public $product_description;
    public $is_publish;
    public $date_created;
    public $date_modified;
    public $metadata;
    public $image_id;
    
    //---------------------------------------------------------------------------------------------------------------------

    public function insert()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "INSERT INTO
                        products
                        (
                            route_id,
                            product_category_id,
                            product_type,
                            product_title,
                            product_price,
                            is_publish,
                            date_created
                        )
                    VALUES
                        (
                            :route_id,
                            :product_category_id,
                            :product_type,
                            :product_title,
                            :product_price,
                            :is_publish,
                            :date_created
                        )";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":route_id", $this->route_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":product_category_id", $this->product_category_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":product_type", $this->product_type, PDO::PARAM_STR);
            $pdo_statement->bindParam(":product_title", $this->product_title, PDO::PARAM_STR);
            $pdo_statement->bindParam(":product_price", $this->product_price, PDO::PARAM_STR);
            $pdo_statement->bindParam(":is_publish", $this->is_publish, PDO::PARAM_BOOL);
            $pdo_statement->bindParam(":date_created", $this->date_created, PDO::PARAM_INT);
            $pdo_statement->execute();

            $this->product_id = $pdo_connection->lastInsertId();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }

    //---------------------------------------------------------------------------------------------------------------------

    public function select()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT
                            *
                        FROM
                            products
                        WHERE
                            product_id = :product_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":product_id", $this->product_id, PDO::PARAM_INT);
            $pdo_statement->execute();
            $result = $pdo_statement->fetch(PDO::FETCH_ASSOC);

            $this->product_id 		   = $result["product_id"];
            $this->route_id 		   = $result["route_id"];
            $this->product_category_id = $result["product_category_id"];
            $this->product_type 	   = $result["product_type"];
            $this->product_title 	   = $result["product_title"];
            $this->product_price 	   = $result["product_price"];
            $this->product_description 		   = $result["product_description"];
            $this->is_publish 		   = $result["is_publish"];
            $this->date_created = $result["date_created"];
            $this->date_modified = $result["date_modified"];
            $this->metadata = $result["metadata"];
            $this->image_id = $result["image_id"];
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
                        products
                    SET
                        route_id = :route_id,
                        product_category_id = :product_category_id,
                        product_type = :product_type,
                        product_title = :product_title,
                        product_price = :product_price,
                        product_description = :product_description,
                        is_publish = :is_publish,
                        date_created = :date_created,
                        date_modified = :date_modified,
                        metadata = :metadata,
                        image_id = :image_id 
                    WHERE
                        product_id = :product_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":product_id", $this->product_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":route_id", $this->route_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":product_category_id", $this->product_category_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":product_type", $this->product_type, PDO::PARAM_STR);
            $pdo_statement->bindParam(":product_title", $this->product_title, PDO::PARAM_STR);
            $pdo_statement->bindParam(":product_price", $this->product_price, PDO::PARAM_STR);
            $pdo_statement->bindParam(":product_description", $this->product_description, PDO::PARAM_STR);
            $pdo_statement->bindParam(":is_publish", $this->is_publish, PDO::PARAM_BOOL);
            $pdo_statement->bindParam(":date_created", $this->date_created, PDO::PARAM_INT);
            $pdo_statement->bindParam(":date_modified", $this->date_modified, PDO::PARAM_INT);
            $pdo_statement->bindParam(":metadata", $this->metadata, PDO::PARAM_STR);
            $pdo_statement->bindParam(":image_id", $this->image_id, PDO::PARAM_INT);
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
                            products
                        WHERE
                            product_id = :product_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":product_id", $this->product_id, PDO::PARAM_INT);
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
                            products
                        SET
                            $column = :$column
                        WHERE
                            product_id = :product_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":product_id", $this->product_id, PDO::PARAM_INT);
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
