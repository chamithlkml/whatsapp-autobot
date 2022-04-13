<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';

class LogModel extends Database
{
    public function insert_log($type, $message)
    {
        $stmt = $this->connection->prepare("INSERT INTO whatsapp_logs (type, created_on, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $log_type,$log_created_on, $log_message);

        $log_type  = $type;
        $log_created_on = date('Y-m-d H:i:s');
        $log_message = str_replace("'", "", $message);
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