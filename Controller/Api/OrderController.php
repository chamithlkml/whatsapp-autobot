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

    public function nameAction()
    {
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->createOrder($data->query->sender, 1);
        $this->sendResponse("Please enter your name");

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

        $message = "Here are our Food and Drinks menu.\n";

        $message .= $this->getMenuItemsListingMessage(1);

        $this->sendResponse($message);
    }

    public function addressAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $order_model = new OrderModel();
        $order_model->setNameToOrder($data->query->sender, $data->query->message);

        $this->sendResponse("Please enter your delivery address");
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

        $message = $this->getMenuItemsListingMessage(2);

        $this->sendResponse($message);
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
        $description = $menu_item['description'] == "" ? "" : " - " . $menu_item['description'];
        $size = $menu_item['size'] == '-' ? "":" (" . $menu_item['size'] . ")";
        $message = "How many " . $menu_item['item_name'] . $description . $size . "(s) do you need.?";

        $this->sendResponse($message);
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
        $message .= "1. See the menu\n";
        $message .= "2. Complete reservation";

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
            $description = $order_item['description'] == '' ?  '' : "- " . $order_item['description'];
            $size = $order_item['size'] == '-' ? "" : " (" . $order_item['size'] . ")";
            $message .= "- " . $order_item['item_name'] . " " . $description . $size . " " . $order_item['quantity'] . " Nos: " . $order_item['sub_total'] . " ". $order_item['currency'] . "\n";
            $email_message .= '<li>' . $order_item['item_name'] . " " . $description . $size . " " . $order_item['quantity'] . " Nos: " . $order_item['sub_total'] . " ". $order_item['currency'] . '</li>';
            $total += $order_item['sub_total'];
        }

        $email_message .= '</ul>';
        $email_message .= '<p>Total = ' . $total . " " . $order_items[0]['currency'] . '</p></html>';

        $message .= "Total = " . $total . " " . $order_items[0]['currency'] . "\n";

        $sms_message .= $message . "\n";

        $message .= "\nPlease credit our bank account with the total amount so that we can book your rooms\n";
        $message .= "Bank: Bank of Ceylon\n";
        $message .= "Branch: Tangalle\n";
        $message .= "Account No: 85807772";

        $email_message .= '<p>Please credit our bank account with the total amount so that we can book your rooms</p>';
        $email_message .= '<p>Bank: Bank of Ceylon</p>';
        $email_message .= '<p>Branch: Tangalle</p>';
        $email_message .= '<p>Account No: 85807772</p>';

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

        $this->sendResponse($message);
    }

    public function rooms_menuAction(){
        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        Logger::info('post_data: ' . print_r($data, true));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $message = $this->getMenuItemsListingMessage(2);
        $this->sendResponse($message);
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
        $category = $order_model->getCategory($menu_item['category_id']);

        $reply = new stdClass();
        $reply->message = "How many " . $category['category_name'] . ": " . $menu_item['item_name'] . " [" . $menu_item['size'] . "](s) do you need.?";

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
        $message .= "1. See the menu\n";
        $message .= "2. Complete order";

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
        $message .= "1. Pay on delivery\n";
        $message .= "2. Bank transfer";

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