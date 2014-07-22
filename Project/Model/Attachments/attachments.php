<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'attachment.php';

class attachments
{
    public $attachments;

    //--------------------------------------------------------------------------------------------------------------------

    public function select()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT
                            *
                        FROM
                            attachments";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->execute();
            $results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

            foreach($results as $result)
            {
                $attachment = new attachment();

                $attachment->attachment_id = $result["attachment_id"];
                $attachment->application_id = $result["application_id"];
                $attachment->path = $result["path"];
                $attachment->description = $result["description"];
                $this->attachments[] = $attachment;

            }
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
}

?>
