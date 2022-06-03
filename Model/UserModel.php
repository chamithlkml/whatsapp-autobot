<?php
require_once PROJECT_ROOT_PATH . '/Model/Database.php';

class UserModel extends Database
{
    /**
     * Returns the list of whatsapp users registered
     * @param $limit
     * @return bool|mixed
     * @throws Exception
     */
    public function getUsers($limit)
    {
        return $this->select("SELECT * FROM whatsapp_users ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }

}