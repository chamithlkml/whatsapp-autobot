<?php

require __DIR__ . "/inc/bootstrap.php";

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    $valid_actions = [
        'order' => ['select_option', 'set_name', 'choice', 'name', 'address', 'quantity', 'item', 'menu', 'confirm', 'email', 'rooms', 'room_quantity', 'add_room', 'rooms_menu', 'complete_room_reservation'],
        'admin' => ['validate_user', 'validate_password', 'create_category', 'list_categories', 'add_menu_item', 'list_menu_items', 'change_price', 'list_rooms']
    ];


    if ((isset($uri[2]) && !array_key_exists($uri[2], $valid_actions)) || !isset($uri[3])) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    if( $uri[2] == 'order' && in_array($uri[3], $valid_actions[$uri[2]])){

        try{
            require PROJECT_ROOT_PATH . "/Controller/Api/OrderController.php";

            $objFeedController = new OrderController();
            $strMethodName = $uri[3] . 'Action';
            $objFeedController->{$strMethodName}();
        }catch (Throwable $e){
            Logger::error($e->getMessage());
            Logger::error($e->getTraceAsString());

            $reply = new stdClass();

            $message = $e->getMessage() . "\n";
            $message .= $e->getTraceAsString();

            $reply->message = $message;

            $responseData = json_encode(array(
                'replies' => array(
                    $reply
                )
            ));

            header_remove('Set-Cookie');
            $httpHeaders = array('Content-Type: application/json', 'HTTP/1.1 200 OK');

            if (is_array($httpHeaders) && count($httpHeaders)) {
                foreach ($httpHeaders as $httpHeader) {
                    header($httpHeader);
                }
            }

            echo $responseData;
            exit;
        }
    }
    else if($uri[2] == 'admin' && in_array($uri[3], $valid_actions[$uri[2]]))
    {
        try{
            require PROJECT_ROOT_PATH . "/Controller/Api/AdminController.php";

            $objFeedController = new AdminController();
            $strMethodName = $uri[3] . 'Action';
            $objFeedController->{$strMethodName}();
        }catch (Throwable $e){
            Logger::error($e->getMessage());
            Logger::error($e->getTraceAsString());

            $reply = new stdClass();

            $message = $e->getMessage() . "\n";
            $message .= $e->getTraceAsString();

            $reply->message = $message;

            $responseData = json_encode(array(
                'replies' => array(
                    $reply
                )
            ));

            header_remove('Set-Cookie');
            $httpHeaders = array('Content-Type: application/json', 'HTTP/1.1 200 OK');

            if (is_array($httpHeaders) && count($httpHeaders)) {
                foreach ($httpHeaders as $httpHeader) {
                    header($httpHeader);
                }
            }

            echo $responseData;
            exit;

        }
    }

