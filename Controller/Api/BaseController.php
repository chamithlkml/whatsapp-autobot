<?php

/**
 * Base controller having common used controller methods
 * Class BaseController
 */
class BaseController
{
    public function __construct()
    {
        $this->validate_request();
    }

    /**
    * __call magic method.
    */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Validating request by headers
     */
    public function validate_request()
    {
        if($_SERVER['PHP_AUTH_USER'] != BASIC_AUTH_USER || $_SERVER['PHP_AUTH_PW'] != BASIC_AUTH_PASSWORD || $_SERVER['HTTP_X_APP_KEY'] != APP_KEY){
            $this->sendOutput('Unauthorized request', array('HTTP/1.1 401 Unauthorized'));
        }
    }

    /**
     * Get URI elements.
     *
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );

        return $uri;
    }

    /**
     * Get querystring params.
     *
     * @return array
     */
    protected function getQueryStringParams()
    {
        return parse_str($_SERVER['QUERY_STRING'], $query);
    }

    /**
     * Send API output.
     *
     * @param mixed  $data
     * @param string $httpHeader
     */
    protected function sendOutput($data, $httpHeaders=array())
    {
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;
        exit;
    }

    /**
     * Standard response
     * @param $message
     */
    protected function sendResponse($message)
    {
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

    /**
     * Send error response
     * @param $errors
     */
    protected function sendError($errors)
    {
            http_response_code(400);
            $replies = [];

            foreach($errors as $error){
                $replies[] = array(
                    "message" => $error
                );
            }

            echo json_encode(array("replies" => $replies));
    }

    /**
     * Returns menu items listing message
     * @param $type
     * @return string
     * @throws Exception
     */
    protected function getMenuItemsListingMessage($type)
    {
        $food_model = new OrderModel();

        $category_menu_items = $food_model->getCategoryMenuItems($type);

        $message = "";

        foreach($category_menu_items as $item)
        {
            $message .= $item->category['category_name'] . "\n";

            if($item->category['description'] != "")
                $message .= $item->category['description'] . "\n";

            foreach($item->menu_items as $menu_item)
            {
                $description = $menu_item['description'] == '' ?  '' : "- " . $menu_item['description'];
                $size = $menu_item['size'] == '-' ? "" : " (" . $menu_item['size'] . ")";
                $message .= $menu_item['code'] . ". " . $menu_item['item_name'] . " "  . $description . " " . $size . " " . $menu_item['currency'] . " " . $menu_item['unit_price'] . "\n";
            }

            $message .= "\n";
        }

        return $message;
    }

    /**
     * Validate input data
     * @param $message
     * @param $action
     * @param $chunks_count
     * @return stdClass
     */
    protected function validate_input($message, $action, $chunks_count)
    {
        $response = new stdClass();
        $response->valid = false;
        $response->errors = [];

        $chunks = explode("|", $message);

        if(count($chunks) != $chunks_count){
            $response->valid = false;
            $response->errors = ['Wrong input', 'Try again'];

            return $response;
        }

        if( $chunks[0] == 'admin' && $chunks[1] == $action && $chunks[2] == BASIC_AUTH_USER && $chunks[3] == BASIC_AUTH_PASSWORD)
        {
            $response->valid = true;

            return $response;
        }
    }

    /**
     * Validate input for valid email
     * @param $input
     * @throws Exception
     */
    protected function validate_email($input)
    {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email');
        }
    }
}