<?php


class OrderController extends BaseController
{

    public function select_optionAction()
    {
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $customer_choice = intval($data->query->message);
        $order_options = [1,2,3];

        if(in_array($customer_choice, $order_options))
        {
            $order_model = new OrderModel();
            $order_model->createOrder($data->query->sender, $customer_choice);

            $this->sendResponse("Please enter your name.");
        }else if($customer_choice == 4)
            {
                $message = "Call 0472240299 or send an email to info@resthousetngalle.com. We will reach out to you as soon as possible. Thank you!";
                $this->sendResponse($message);
            }else
                {
                    $this->sendError(['Wrong input', 'Try again']);
                }
    }

    public function set_nameAction()
    {
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->setNameToOrder($data->query->sender, $data->query->message);

        $order = $order_model->getOrder($data->query->sender);

        $message = "";

        if($order['type'] == 1){
            $message .= "Please enter your delivery address";

            $this->sendResponse($message);
        }else if($order['type'] == 2 || $order['type'] == 3) {
            $message .= "Please enter your email address";

            $this->sendResponse($message);
        }else{
            $this->sendError(['Wrong input', 'Try again']);
        }

    }

    public function choiceAction()
    {

        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->setAddressToOrder($data->query->sender, $data->query->message);

        $category_menu_items = $order_model->getCategoryMenuItems(1);

        $message = "Here are our Food and Drinks menu.\n";

        $message .= $this->getMenuItemsListingMessage(1);

        $this->sendResponse($message);
    }

    public function set_addressAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order = $order_model->getOrder($data->query->sender);


        if($order['type'] == 1)
        {
            $order_model->setAddressToOrder($data->query->sender, $data->query->message);
        }
        else if($order['type'] == 2 || $order['type'] == 3)
        {
            $this->validate_email($data->query->message);
            $order_model->setEmailToOrder($data->query->sender, $data->query->message);
        }

        $message = "";

        if($order['type'] == 1 || $order['type'] == 2)
        {
            $message .=  "Here are our food and drinks categories.\n";
            $message .= $order['type'] == 2 ? "Note: Subjected to 10% service charge.\n" : "";


        }
    }

    public function emailAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->createOrder($data->query->sender, $data->query->message);

        $reply = new stdClass();
        $reply->message = "Please enter your email address.";

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));
        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    public function roomsAction(){
        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $room_model = new RoomModel();
        $room_model->setEmailToOrder($data->query->sender, $data->query->message);

        $order_model = new OrderModel();
        # Get rooms
        $menu_items = $order_model->getMenuItems(2);

        $menu = "Here is the list of rooms we have for you. Please enter the code of your room choice. \n";

        foreach ($menu_items as $menu_item)
        {
            $menu .= "[" . $menu_item['code'] . "] " . $menu_item['item_name'] . " (" . $menu_item['size'] . ") - " . $menu_item['currency'] . " " . $menu_item['unit_price'] . "\n";
        }

        $reply = new stdClass();
        $reply->message = $menu;

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    public function room_quantityAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->addItemToOrder($data->query->sender, $data->query->message);

        $code = $data->query->message;
        $menu_item = $order_model->getMenuItemByCode($code);

        $reply = new stdClass();
        $reply->message = "How many " . $menu_item['item_name'] . " rooms do you need.?";

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));
        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    public function add_roomAction(){
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->setOrderItemQuantity($data->query->sender, $data->query->message);
        Logger::info('Item quantity is set');

        $reply = new stdClass();
        $message = "Room(s) reserved. Select your choice. \n";
        $message .= "[1] See the menu\n";
        $message .= "[2] Complete reservation";

        $reply->message = $message;

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

    }

    public function complete_room_reservationAction()
    {
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_items = $order_model->getOrderSummary($data->query->sender);
        $order = $order_model->getOrder($data->query->sender);

        $sms_message = "Received rooms reservation: #" . $order['id'] . "\n";
        $sms_message .= "Name: " . $order['recipient_name'] . "\n";
        $sms_message .= "Contact No: " . $order['sender'] . "\n";
        $sms_message .= "Email: " . $order['recipient_email'] . "\n";

        $email_message = '<html><h3>Room reservation request: #' . $order['id'] . '</h3>';
        $email_message .= '<p>Name: ' . $order['recipient_name'] . '</p>';
        $email_message .= '<p>Contact No: ' . $order['sender'] . '</p>';
        $email_message .= '<p>Email: ' . $order['recipient_email'] . '</p>';
        $email_message .= '</br>';
        $email_message .= '<h3>Order Summary</h3>';
        $email_message .= '<ul>';

        $message = "Order Summary\n";
        $total = 0;

        foreach($order_items as $order_item){
            $message .= "- " . $order_item['item_name'] . " " . $order_item['quantity'] . " Nos: " . $order_item['sub_total'] . " ". $order_item['currency'] . "\n";
            $email_message .= '<li>' . $order_item['item_name'] . " " . $order_item['quantity'] . " Nos: " . $order_item['sub_total'] . " ". $order_item['currency'] . '</li>';
            $total += $order_item['sub_total'];
        }

        $email_message .= '</ul>';
        $email_message .= '<p>Total = ' . $total . " " . $order_items[0]['currency'] . '</p></html>';

        $message .= "Total = " . $total . " " . $order_items[0]['currency'] . "\n";

        $sms_message .= $message . "\n";

        try{
            $smsGateway = new SmsGateway();
            $smsGateway->sendMessage($sms_message, ROOM_RESERVATION_OFFICER_NO);

            $mailer = new Mailer();
            $mailer->sendMail(MAIL_FROM, $order['recipient_email'], MAIL_CC, "Room reservation", $email_message);

        }catch (\Exception $e){
            Logger::error("error occurred when sending email, sms: " . $e->getMessage());
            Logger::error($e->getTraceAsString());
        }

        # Complete the order only when all getOrderID is done
        $order_model->completeOrder($data->query->sender);

        $reply = new stdClass();

        $reply->message = $message;

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    public function rooms_menuAction(){
        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        # Get rooms
        $menu_items = $order_model->getMenuItems(2);

        $menu = "Here is the list of rooms we have for you. Please enter the code of your room choice. \n";

        foreach ($menu_items as $menu_item)
        {
            $menu .= "[" . $menu_item['code'] . "] " . $menu_item['item_name'] . " (" . $menu_item['size'] . ") - " . $menu_item['currency'] . " " . $menu_item['unit_price'] . "\n";
        }

        $reply = new stdClass();
        $reply->message = $menu;

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

    }

    public function quantityAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->addItemToOrder($data->query->sender, $data->query->message);

        $code = $data->query->message;
        $menu_item = $order_model->getMenuItemByCode($code);

        $reply = new stdClass();
        $reply->message = "How many " . $menu_item['item_name'] . " [" . $menu_item['size'] . "](s) do you need.?";

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));
        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    public function itemAction(){
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->setOrderItemQuantity($data->query->sender, $data->query->message);
        Logger::info('Item quantity is set');

        $reply = new stdClass();
        $message = "Item added to the order. Select your choice. \n";
        $message .= "[1] See the menu\n";
        $message .= "[2] Complete order";

        $reply->message = $message;

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

    }

    public function menuAction()
    {
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $message = $this->getMenuItemsListingMessage(1);
        $this->sendResponse($message);

    }

    public function confirmAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_items = $order_model->getOrderSummary($data->query->sender);
        $order = $order_model->getOrder($data->query->sender);

        $sms_message = "Received order: " . $order['id'] . "\n";
        $sms_message .= "Recipient Name: " . $order['recipient_name'] . "\n";
        $sms_message .= "Recipient Contact No: " . $order['sender'] . "\n";
        $sms_message .= "Recipient Address: " . $order['address'] . "\n";

        $message = "Order Summary\n";
        $total = 0;

        foreach ($order_items as $order_item)
        {
            $message .= "- " . $order_item['item_name'] . " [" . $order_item['size'] . "] " . $order_item['quantity'] . " Nos: " . $order_item['sub_total'] . " ". $order_item['currency'] . "\n";
            $total += $order_item['sub_total'];
        }

        $message .= "Total = " . $total . " " . $order_items[0]['currency'] . "\n";
        $sms_message .= $message;
        $message .= "Please enter your preferred payment method ?\n";
        $message .= "[1] Pay on delivery\n";
        $message .= "[2] Bank transfer";

        $reply = new stdClass();
        $reply->message = $message;

        $responseData = json_encode(array(
            'replies' => array(
                $reply
            )
        ));

        $smsGateway = new SmsGateway();
        $smsGateway->sendMessage($sms_message, $order['sender']);
        $smsGateway->sendMessage($sms_message, KITCHEN_DEPT_NO);

        # Complete the order only when all getOrderID is done
        $order_model->completeOrder($data->query->sender);


        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

    }

    private function getOrderItemField($item_label, $value)
    {
        $field = $value;

        for($i=0;$i<strlen($item_label);$i++)
        {
            $field .= " ";
        }

        $field .= "|";

        return $field;
    }
}