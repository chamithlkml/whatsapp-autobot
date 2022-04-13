<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';

class OrderModel extends Database
{
    public function getMenuItems($type)
    {
        return $this->select("select * from whatsapp_menu_items where type=? order by code asc", ["i", $type]);
    }

    public function createOrder($sender, $type)
    {
        $stmt = $this->connection->prepare("INSERT INTO whatsapp_orders (sender, type, created_on) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $req_sender,$req_type, $req_created_on);

        $req_sender = $sender;
        $req_type = $type;
        $req_created_on = date('Y-m-d H:i:s');
        $result = $stmt->execute();

        if($result){
            $insert_id = $stmt->insert_id;
            $this->connection->close();
            return $insert_id;
        }else{
            throw new Exception("Failed to create order");
        }
    }

    public function getOrderID($sender){
        $existing_order = $this->select("select id from whatsapp_orders where sender = ? and completed_on is NULL order by id desc limit 1", ["s", $sender]);

        if(count($existing_order) == 0){
            throw new Exception('Existing order not found');
        }

        return $existing_order[0]['id'];
    }

    public function setNameToOrder($sender, $name){
        $order_id = $this->getOrderID($sender);

        $stmt = $this->connection->prepare("update whatsapp_orders set recipient_name = ? where id = ?");
        $stmt->bind_param("si", $r_name, $id);

        $r_name = $name;
        $id = $order_id;

        $result = $stmt->execute();

        if(!$result){
            throw new Exception('Failed to update the order name');
        }else{
            Logger::info('Order address updated');
        }
    }

    public function setEmailToOrder($sender, $email){
        $order_id = $this->getOrderID($sender);

        $stmt = $this->connection->prepare("update whatsapp_orders set recipient_email = ? where id = ?");
        $stmt->bind_param("si", $r_email, $id);

        $r_email = $email;
        $id = $order_id;

        $result = $stmt->execute();

        if(!$result){
            throw new Exception('Failed to update the email address');
        }else{
            Logger::info('Order address updated');
        }
    }

    public function setAddressToOrder($sender, $address){
        $order_id = $this->getOrderID($sender);

        $stmt = $this->connection->prepare("update whatsapp_orders set address = ? where id = ?");
        $stmt->bind_param("si", $r_address, $id);

        $r_address = $address;
        $id = $order_id;

        $result = $stmt->execute();

        if(!$result){
            throw new Exception('Failed to update the order address');
        }else{
            Logger::info('Order address updated');
        }

    }

    public function getMenuItemID($code)
    {
        $menu_items = $this->select("select id from whatsapp_menu_items where code = ? limit 1", ["d", intval($code)]);

        if(count($menu_items) == 0){
            throw new Exception('Menu item not found');
        }

        return $menu_items[0]['id'];
    }

    public function getMenuItemByCode($code)
    {
        $menu_items = $this->select("select * from whatsapp_menu_items where code = ?", ["d", intval($code)]);

        if(count($menu_items) == 0){
            throw new Exception('Menu item not found');
        }

        return $menu_items[0];
    }

    public function addItemToOrder($sender, $code)
    {
        $order_id = $this->getOrderID($sender);
        $menu_item_id = $this->getMenuItemID($code);

        $stmt = $this->connection->prepare("INSERT INTO whatsapp_order_items (order_id, menu_item_id, quantity, sub_total, created_on) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("dddds", $q_order_id,$q_menu_item_id, $q_quantity, $q_sub_total, $q_created_on);

        $q_order_id = $order_id;
        $q_menu_item_id = $menu_item_id;
        $q_quantity = 0;
        $q_sub_total = 0;
        $q_created_on = date('Y-m-d H:i:s');
        $result = $stmt->execute();

        if($result){
            return $stmt->insert_id;
        }else{
            throw new Exception("Failed to create order");
        }

    }

    public function getMenuItem($menu_item_id)
    {
        $menu_items = $this->select("select * from whatsapp_menu_items where id = ?", ["d", intval($menu_item_id)]);

        if(count($menu_items) == 0){
            throw new Exception('Menu item not found');
        }

        return $menu_items[0];
    }

    public function getLastOrderItem($order_id)
    {
        $order_items = $this->select("select * from whatsapp_order_items where order_id = ? order by id desc limit 1", ["d", intval($order_id)]);

        if(count($order_items) == 0){
            throw new Exception('Order item not found');
        }

        return $order_items[0];
    }

    public function setOrderItemQuantity($sender, $quantity)
    {
        $order_id = $this->getOrderID($sender);
        Logger::info("order id: " . $order_id);
        $last_order_item = $this->getLastOrderItem($order_id);
        Logger::info('last order item: ' . print_r($last_order_item, true));

        $menu_item = $this->getMenuItem($last_order_item['menu_item_id']);
        Logger::info(print_r($menu_item, true));

        $stmt = $this->connection->prepare("UPDATE whatsapp_order_items SET quantity = ?, sub_total = ? where id = ?");
        $stmt->bind_param("ddd", $q_quantity,$q_sub_total, $q_id);

        $q_quantity = intval($quantity);
        $q_sub_total = intval($menu_item['unit_price']) * intval($quantity);
        $q_id = $last_order_item['id'];

        $result = $stmt->execute();

        if($result){
            return $result;
        }else{
            throw new Exception("Failed to create order");
        }

    }

    public function getOrderSummary($sender)
    {
        $order_id = $this->getOrderID($sender);

        return $this->select("select wmi.item_name, wmi.size, wmi.currency, wmi.unit_price, wmi.description, woi.quantity, woi.sub_total 
                        from whatsapp_order_items AS woi INNER JOIN whatsapp_menu_items AS wmi ON woi.menu_item_id=wmi.id 
                        where woi.order_id = ? order by woi.id asc", ["d", intval($order_id)]);

    }

    public function completeOrder($sender)
    {
        $order_id = $this->getOrderID($sender);

        $stmt = $this->connection->prepare("UPDATE whatsapp_orders SET completed_on = ? where id = ?");
        $stmt->bind_param("sd", $q_completed_on,$q_id);

        $q_completed_on = date('Y-m-d H:i:s');
        $q_id = $order_id;

        $result = $stmt->execute();

        if($result){
            return $result;
        }else{
            throw new Exception("Failed to complete order");
        }

    }

    public function getOrder($sender){
        $existing_order = $this->select("select * from whatsapp_orders where sender = ? and completed_on is NULL order by id desc limit 1", ["s", $sender]);

        if(count($existing_order) == 0){
            throw new Exception('Existing order not found');
        }

        return $existing_order[0];
    }

    public function addCategory($category_name, $description)
    {
        if($description == "-")
        {
            $description = "";
        }

        $stmt = $this->connection->prepare("INSERT INTO whatsapp_item_categories (category_name, description, created_on) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $c_category_name, $c_description, $c_created_on);

        $c_category_name = $category_name;
        $c_description = $description;
        $c_created_on = date('Y-m-d H:i:s');

        $result = $stmt->execute();

        if($result){
            return $stmt->insert_id;
        }else{
            throw new Exception("Failed to create order");
        }

    }

    public function getCategories($type=1)
    {
        return $this->select("select * from whatsapp_item_categories where type=? order by id asc", ["i", $type]);
    }

    public function getMaxMenuItemCode(){
        $max_codes = $this->select("select max(code) as max_code from whatsapp_menu_items", []);

        return count($max_codes) == 0 ? 0 : $max_codes[0]['max_code'];
    }

    public function addMenuItem($category_id, $item_name, $description, $size, $currency, $price, $type)
    {
        $max_code = $this->getMaxMenuItemCode();
        Logger::info('max code: ' . $max_code);
        $new_code = intval($max_code) + 1;

        if($description == "-")
        {
            $description = "";
        }

        $stmt = $this->connection->prepare("INSERT INTO whatsapp_menu_items (type, code, item_name, size, currency, unit_price, category_id, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ddsssdds", $i_type, $i_code, $i_item_name, $i_size, $i_currency, $i_unit_price, $i_category_id, $i_description);

        $i_type = intval($type);
        $i_code = $new_code;
        $i_item_name = $item_name;
        $i_size = $size;
        $i_currency = $currency;
        $i_unit_price = $price;
        $i_category_id = $category_id;
        $i_description = $description;

        $result = $stmt->execute();

        if($result){
            return $stmt->insert_id;
        }else{
            throw new Exception("Failed to create order");
        }
    }

    public function getMenuItemsByCategory($type, $category_id)
    {
        return $this->select("select * from whatsapp_menu_items where type={$type} and category_id = ? order by code asc", ["i", intval($category_id) ]);
    }

    public function getCategoryMenuItems($type)
    {
        $result = [];

        $categories = $this->getCategories($type);

        foreach($categories as $category)
        {
            $temp = new stdClass();
            $temp->category = $category;
            $temp->menu_items = $this->getMenuItemsByCategory($type, $category['id']);

            $result[] = $temp;
        }

        return $result;
    }

    public function setItemPrice($code, $price)
    {
        $stmt = $this->connection->prepare("UPDATE whatsapp_menu_items SET unit_price = ? where code = ?");
        $stmt->bind_param("dd", $q_price,$q_code);

        $q_price = $price;
        $q_code = $code;

        $result = $stmt->execute();

        if($result){
            return $result;
        }else{
            throw new Exception("Failed to set price");
        }
    }

}