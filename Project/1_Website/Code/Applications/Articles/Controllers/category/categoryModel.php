<?php
require_once 'Project/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';

class categoryModel extends applicationsSuperController
{
	
	public function selectArticlesByPermalink($permalink)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "SELECT
							a.*,
							r.permalink,
							i.image_title,
							i.image_caption,
							CONCAT('/Data/Images/', ab.album_folder, '/', ais.dimensions, '/', i.path) as image_path
						FROM
							articles a
						INNER JOIN
							route r
						ON
							a.route_id = r.route_id
						LEFT JOIN
							article_categories ac
						ON
							ac.article_category_id = a.article_category_id
						LEFT JOIN
							images i
						ON
							a.image_id = i.image_id
						LEFT JOIN
							albums ab
						ON
							i.album_id = ab.album_id
						LEFT JOIN
							album_image_sizes ais
						ON
							i.size_id = ais.size_id 
						WHERE
							is_publish = '1'
						AND 
							ac.permalink = :permalink
				";
				
				
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(":permalink", $permalink, PDO::PARAM_STR);
			$pdo_statement->execute();
	
			return $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}	
}