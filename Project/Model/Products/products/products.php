<?php
require_once FILE_ACCESS_CORE_CODE.'/Modules/Database/starfishDatabase.php';
require_once 'product.php';

class products
{
    public $array_of_products;

    //---------------------------------------------------------------------------------------------------------------------

    public function select()
    {
        try
        {
            $pdo_connection = starfishDatabase::getConnection();
            $sql = "SELECT
                            *
                        FROM
                            products";

            $pdo_statement = $pdo_connection->prepare($sql);
            $pdo_statement->execute();
            $results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

            foreach($results as $result)
            {
                $product = new product();

                $product->product_id = $result['product_id'];
                $product->route_id = $result['route_id'];
                $product->product_category_id = $result['product_category_id'];
                $product->product_type = $result['product_type'];
                $product->product_title = $result['product_title'];
                $product->product_price = $result['product_price'];
                $product->description = $result['description'];
                $product->is_published = $result['is_published'];
                $product->date_created = $result['date_created'];
                $product->date_modified = $result['date_modified'];
                $product->metadata = $result['metadata'];
                $product->image_id = $result['image_id'];
                
                $this->array_of_products[] = $product;

            }
            
            return $this->array_of_products;
            
        }
        catch(PDOException $pdoe)
        {
            throw new Exception($pdoe);
        }
    }
}

?>
