<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';


class MenuItemModel extends Database{

    public function insert_menu_items($category_id, $item_name, $description, $size, $currency, $unit_price, $type){
        $stmt = $this->connection->prepare("INSERT INTO whatsapp_menu_items (category_id, item_name,description,size,currency,unit_price,type) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("sssssss",$category_id, $item_name, $description, $size, $currency, $unit_price, $type);

            $result = $stmt->execute();
    }
    
}