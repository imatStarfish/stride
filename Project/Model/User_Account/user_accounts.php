<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'user_account.php';

class user_accounts
{
    public $user_accounts;

    //--------------------------------------------------------------------------------------------------------------------

    public function select()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT
                            *
                        FROM
                            user_accounts";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->execute();
            $results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

            foreach($results as $result)
            {
                $user_account = new user_account();

                $user_account->user_id = $result["user_id"];
                $user_account->email = $result["email"];
                $user_account->first_name = $result["first_name"];
                $user_account->last_name = $result["last_name"];
                $user_account->middle_name = $result["middle_name"];
                $user_account->date_of_birth = $result["date_of_birth"];
                $user_account->user_type = $result["user_type"];
                $this->user_accounts[] = $user_account;

            }
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
}

?>
