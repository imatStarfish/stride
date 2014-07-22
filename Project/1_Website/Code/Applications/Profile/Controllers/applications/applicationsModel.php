<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class applicationsModel extends applicationsSuperController
{
	public $application_id;
	
	public function getApplicationData()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$sql = "
				SELECT
					a.*,
					af.*
				FROM	
					applications a
				INNER JOIN
					application_forms af
				WHERE
					application_id = :application_id
			";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(":application_id", $this->application_id, PDO::PARAM_STR);
			$pdo_statement->execute();
			
			return $pdo_statement->fetch(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			die;
		}
	}
}