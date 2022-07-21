<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';


class MenuItemModel extends Database{

    public function insert_menu_items($category_id, $item_name, $description, $size, $currency, $unit_price, $type){
        $stmt = $this->connection->prepare("INSERT INTO whatsapp_menu_items (category_id, item_name,description,size,currency,unit_price,type) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            // print_r( $row);
            $stmt->bind_param("sssssss",$category_id, $item_name, $description, $size, $currency, $unit_price, $type);

            $result = $stmt->execute();
    }
    



    // public function insert_menu_items($sheetDataItems){
    // print_r($sheetDataItems); 
    //    if ($sheetDataItems===null) {
    //     return;
    //    }
    //     foreach ($sheetDataItems as $row) {
        
    //         $stmt = $this->connection->prepare("INSERT INTO whatsapp_menu_items (type,code,item_name,size,currency,unit_price,category_id,description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    //         $type=(int)$row[0];
    //         $category_name=$row[0];
    //         $description=$row[1]?$row[1]:"";
    //         $type=(int)$row[2];
    //         // print_r( $row);
    //         $stmt->bind_param("sss",$category_name,$description,$type);

                
    //         $result = $stmt->execute();
    //     }

    // }
}