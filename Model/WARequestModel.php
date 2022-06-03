<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';

/**
 * WhatsApp Model
 * Class WARequestModel
 */
class WARequestModel extends Database
{
    /**
     * Store WhatsApp request data
     * @param $sender
     * @param $rule_id
     * @param $message
     * @return int
     * @throws Exception
     */
    public function store_request($sender, $rule_id, $message)
    {
        $stmt = $this->connection->prepare("INSERT INTO whatsapp_requests (sender, rule_id, message, created_on) VALUES (?, ?, ?, ?)");
        $req_sender  = $sender;
        $req_rule_id = $rule_id;
        $req_message = $message;
        $req_created_on = date('Y-m-d H:i:s');

        $stmt->bind_param("sdss", $req_sender,$req_rule_id, $req_message, $req_created_on);


        $result = $stmt->execute();

        if($result){
            $insert_id = $stmt->insert_id;
            $this->connection->close();

            return $insert_id;
        }else{
            throw new Exception("Failed to log message");
        }
    }

}