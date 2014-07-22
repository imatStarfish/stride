<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'application.php';

class applications
{
    public $applications;

    //--------------------------------------------------------------------------------------------------------------------

    public function select()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT
                            *
                        FROM
                            applications";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->execute();
            $results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

            foreach($results as $result)
            {
                $application = new application();

                $application->application_id = $result["application_id"];
                $application->application_forms_id = $result["application_forms_id"];
                $application->user_id = $result["user_id"];
                $application->scholar_data = $result["scholar_data"];
                $application->grants_data = $result["grants_data"];
                $application->last_modified = $result["last_modified"];
                $this->applications[] = $application;

            }
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
}

?>
