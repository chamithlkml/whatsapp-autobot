<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';


class CategoryModel extends Database{

    public function insert_category($category_name, $description, $type){
        $stmt = $this->connection->prepare("INSERT INTO whatsapp_item_categories (category_name, description,type) VALUES (?, ?, ?)");
            
            // print_r( $row);
            $stmt->bind_param("sss",$category_name,$description,$type);

            $result = $stmt->execute();
    }

    public function getAllCategory(){
        $categories= $this->select("SELECT id,category_name FROM whatsapp_item_categories");
        
        if(count($categories) == 0){
            throw new Exception('Existing order not found');
        }

        return $categories;
        
    }

    public function getCategoryId($category_name){
        $stmt= $this->select("SELECT category_id FROM whatsapp_item_categories");
        
        $result = $stmt->execute();
        print_r($result);

        return $result;
        
    }


    // public function insert_categories($sheetData){
    //     // print_r($sheetData); 
    //    if ($sheetData===null) {
    //     return;
    //    }
    //     foreach ($sheetData as $row) {
        
    //         $stmt = $this->connection->prepare("INSERT INTO whatsapp_item_categories (category_name, description,type) VALUES (?, ?, ?)");
    //         $category_name=$row[0];
    //         $description=$row[1]?$row[1]:"";
    //         $type=(int)$row[2];
    //         // print_r( $row);
    //         $stmt->bind_param("sss",$category_name,$description,$type);

                
    //         $result = $stmt->execute();
    //     }

    // }

    // public function truncate_categories(){
    //     $stmt = $this->connection->prepare("TRUNCATE TABLE whatsapp_item_categories");
    //     $result = $stmt->execute();
    // }
}