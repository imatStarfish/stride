<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class attachment
{
    public $attachment_id;
    public $application_id;
    public $path;
    public $description;

    //--------------------------------------------------------------------------------------------------------------------

    public function insert()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "INSERT INTO
                        attachments
                        (
                            application_id,
                            path,
                            description
                        )
                    VALUES
                        (
                            :application_id,
                            :path,
                            :description
                        )";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":application_id", $this->application_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":path", $this->path, PDO::PARAM_STR);
            $pdo_statement->bindParam(":description", $this->description, PDO::PARAM_STR);
            $pdo_statement->execute();

            $this->attachment_id = $pdo_connection->lastInsertId();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }

    //--------------------------------------------------------------------------------------------------------------------

    public function select()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT
                            *
                        FROM
                            attachments
                        WHERE
                            attachment_id = :attachment_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":attachment_id", $this->attachment_id, PDO::PARAM_INT);
            $pdo_statement->execute();
            $result = $pdo_statement->fetch(PDO::FETCH_ASSOC);

            $this->attachment_id = $result["attachment_id"];
            $this->application_id = $result["application_id"];
            $this->path = $result["path"];
            $this->description = $result["description"];
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }

    //--------------------------------------------------------------------------------------------------------------------

    public function update()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "UPDATE
                        attachments
                    SET
                        application_id = :application_id,
                        path = :path,
                        description = :description 
                    WHERE
                        attachment_id = :attachment_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":attachment_id", $this->attachment_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":application_id", $this->application_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":path", $this->path, PDO::PARAM_STR);
            $pdo_statement->bindParam(":description", $this->description, PDO::PARAM_STR);
            $pdo_statement->execute();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }

    //--------------------------------------------------------------------------------------------------------------------

    public function delete()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "DELETE FROM
                            attachments
                        WHERE
                            attachment_id = :attachment_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":attachment_id", $this->attachment_id, PDO::PARAM_INT);
            $pdo_statement->execute();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }

    //--------------------------------------------------------------------------------------------------------------------

    public function updateOneColumn($column, $value)
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "UPDATE
                            attachments
                        SET
                            $column = :$column
                        WHERE
                            attachment_id = :attachment_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":attachment_id", $this->attachment_id, PDO::PARAM_INT);
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
