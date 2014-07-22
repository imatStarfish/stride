<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class user_account
{
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $middle_name;
    public $date_of_birth;
    public $user_type;

    //--------------------------------------------------------------------------------------------------------------------

    public function insert()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "INSERT INTO
                        user_accounts
                        (
                            email,
                            first_name,
                            last_name,
                            middle_name,
                            date_of_birth,
                            user_type
                        )
                    VALUES
                        (
                            :email,
                            :first_name,
                            :last_name,
                            :middle_name,
                            :date_of_birth,
                            :user_type
                        )";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":email", $this->email, PDO::PARAM_STR);
            $pdo_statement->bindParam(":first_name", $this->first_name, PDO::PARAM_STR);
            $pdo_statement->bindParam(":last_name", $this->last_name, PDO::PARAM_STR);
            $pdo_statement->bindParam(":middle_name", $this->middle_name, PDO::PARAM_STR);
            $pdo_statement->bindParam(":date_of_birth", $this->date_of_birth, PDO::PARAM_STR);
            $pdo_statement->bindParam(":user_type", $this->user_type, PDO::PARAM_STR);
            $pdo_statement->execute();

            $this->user_id = $pdo_connection->lastInsertId();

        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
    
    //--------------------------------------------------------------------------------------------------------------------
    
    public static function selectLoggedIn($email, $password)
    {
    	try
    	{
    		$pdo_connection = starfishDatabase::getConnection();
    
    		$sql = "SELECT
    									*
    								FROM
    									user_accounts
    								WHERE
    									email = :email
    								AND
    									password = :password
    								";
    
    		$pdo_statement = $pdo_connection->prepare($sql);
    
    		$pdo_statement->bindParam(':email', $email, PDO::PARAM_STR);
    		$pdo_statement->bindParam(':password', $password, PDO::PARAM_STR);
    		$pdo_statement->execute();
    
    		$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
    
    		return $result;
    
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
                            user_accounts
                        WHERE
                            user_id = :user_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
            $pdo_statement->execute();
            $result = $pdo_statement->fetch(PDO::FETCH_ASSOC);

            $this->user_id = $result["user_id"];
            $this->email = $result["email"];
            $this->first_name = $result["first_name"];
            $this->last_name = $result["last_name"];
            $this->middle_name = $result["middle_name"];
            $this->date_of_birth = $result["date_of_birth"];
            $this->user_type = $result["user_type"];
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
                        user_accounts
                    SET
                        email = :email,
                        first_name = :first_name,
                        last_name = :last_name,
                        middle_name = :middle_name,
                        date_of_birth = :date_of_birth,
                        user_type = :user_type 
                    WHERE
                        user_id = :user_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
            $pdo_statement->bindParam(":email", $this->email, PDO::PARAM_STR);
            $pdo_statement->bindParam(":first_name", $this->first_name, PDO::PARAM_STR);
            $pdo_statement->bindParam(":last_name", $this->last_name, PDO::PARAM_STR);
            $pdo_statement->bindParam(":middle_name", $this->middle_name, PDO::PARAM_STR);
            $pdo_statement->bindParam(":date_of_birth", $this->date_of_birth, PDO::PARAM_STR);
            $pdo_statement->bindParam(":user_type", $this->user_type, PDO::PARAM_STR);
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
                            user_accounts
                        WHERE
                            user_id = :user_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
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
                            user_accounts
                        SET
                            $column = :$column
                        WHERE
                            user_id = :user_id";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->bindParam(":user_id", $this->user_id, PDO::PARAM_INT);
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
