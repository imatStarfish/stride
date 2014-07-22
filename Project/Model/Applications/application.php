<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class application
{
    public $application_id;
    public $application_forms_id;
    public $user_id;
    public $scholar_data;
    public $grants_data;
    public $last_modified;

    //--------------------------------------------------------------------------------------------------------------------

    public function insert()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "INSERT INTO
                        applications
                        (
                            application_forms_id,
                            user_id,
                            scholar_data,
                            grants_data,
                            last_modified
                        )
                    VALUES
                        (
                            :application_forms_id,
                            :user_id,
                            :scholar_data,
                            :grants_data,
                            :last_modified
                        )";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":application_forms_id", $this->application_forms_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":scholar_data", $this->scholar_data, PDO::PARAM_STR);
            $pdo_statement->bindParam(":grants_data", $this->grants_data, PDO::PARAM_STR);
            $pdo_statement->bindParam(":last_modified", $this->last_modified, PDO::PARAM_STR);
            $pdo_statement->execute();

            $this->application_id = $pdo_connection->lastInsertId();

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
                            applications
                        WHERE
                            application_id = :application_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":application_id", $this->application_id, PDO::PARAM_INT);
            $pdo_statement->execute();
            $result = $pdo_statement->fetch(PDO::FETCH_ASSOC);

            $this->application_id = $result["application_id"];
            $this->application_forms_id = $result["application_forms_id"];
            $this->user_id = $result["user_id"];
            $this->scholar_data = $result["scholar_data"];
            $this->grants_data = $result["grants_data"];
            $this->last_modified = $result["last_modified"];
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
                        applications
                    SET
                        application_forms_id = :application_forms_id,
                        user_id = :user_id,
                        scholar_data = :scholar_data,
                        grants_data = :grants_data,
                        last_modified = :last_modified 
                    WHERE
                        application_id = :application_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":application_id", $this->application_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":application_forms_id", $this->application_forms_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":scholar_data", $this->scholar_data, PDO::PARAM_STR);
            $pdo_statement->bindParam(":grants_data", $this->grants_data, PDO::PARAM_STR);
            $pdo_statement->bindParam(":last_modified", $this->last_modified, PDO::PARAM_STR);
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
                            applications
                        WHERE
                            application_id = :application_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":application_id", $this->application_id, PDO::PARAM_INT);
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
                            applications
                        SET
                            $column = :$column
                        WHERE
                            application_id = :application_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":application_id", $this->application_id, PDO::PARAM_INT);
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
