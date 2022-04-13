<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';

class RoomModel extends Database
{
    public function getOrderID($sender){
        $existing_order = $this->select("select id from whatsapp_orders where sender = ? and completed_on is NULL order by id desc limit 1", ["s", $sender]);

        if(count($existing_order) == 0){
            throw new Exception('Existing order not found');
        }

        return $existing_order[0]['id'];
    }

    public function setEmailToOrder($sender, $email){
        $order_id = $this->getOrderID($sender);

        $stmt = $this->connection->prepare("update whatsapp_orders set recipient_email = ? where id = ?");
        $stmt->bind_param("sd", $r_email, $id);

        $r_email = $email;
        $id = $order_id;

        $result = $stmt->execute();

        if(!$result){
            throw new Exception('Failed to update the order address');
        }else{
            Logger::info('Order address updated');
        }

    }
}