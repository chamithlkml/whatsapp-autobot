<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';

/**
 * Rooms model class
 * Class RoomModel
 */
class RoomModel extends Database
{
    /**
     * Returns order id by sender
     * @param $sender
     * @return mixed
     * @throws Exception
     */
    public function getOrderID($sender){
        $existing_order = $this->select("select id from whatsapp_orders where sender = ? and completed_on is NULL order by id desc limit 1", ["s", $sender]);

        if(count($existing_order) == 0){
            throw new Exception('Existing order not found');
        }

        return $existing_order[0]['id'];
    }

    /**
     * Set email to order
     * @param $sender
     * @param $email
     * @throws Exception
     */
    public function setEmailToOrder($sender, $email){
        $order_id = $this->getOrderID($sender);

        $stmt = $this->connection->prepare("update whatsapp_orders set recipient_email = ? where id = ?");

        $r_email = $email;
        $id = $order_id;

        $stmt->bind_param("sd", $r_email, $id);
        $result = $stmt->execute();

        if(!$result){
            throw new Exception('Failed to update the order address');
        }else{
            Logger::info('Order address updated');
        }

    }
}