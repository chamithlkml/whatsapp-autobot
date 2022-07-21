<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';


class CategoryModel extends Database{

    public function insert_category($category_name, $description, $type){
        $stmt = $this->connection->prepare("INSERT INTO whatsapp_item_categories (category_name, description,type) VALUES (?, ?, ?)");
            
            $stmt->bind_param("sss",$category_name,$description,$type);

            $result = $stmt->execute();
    }

    public function getAllCategory(){
        $categories= $this->select("SELECT id,category_name FROM whatsapp_item_categories");
        
        if(count($categories) == 0){
            throw new Exception('Category not found');
        }

        return $categories;
        
    }

    public function getCategoryId($category_name){
        $stmt= $this->select("SELECT category_id FROM whatsapp_item_categories");
        
        $result = $stmt->execute();
        print_r($result);

        return $result;
        
    }

}