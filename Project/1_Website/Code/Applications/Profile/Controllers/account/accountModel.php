<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class accountModel extends applicationsSuperController
{
	public $user_id;
	
	public function getAllApplications()
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
				ON
					a.application_forms_id = af.application_forms_id
				WHERE
					user_id = :user_id	
			";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(":user_id", $this->user_id, PDO::PARAM_STR);
			$pdo_statement->execute();
			
			return $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			die;
		}
	}
}