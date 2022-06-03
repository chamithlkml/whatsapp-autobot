<?php

/*
 * Administrator related APIs
 */
class AdminController extends BaseController
{

    /**
     * Creates a food/rooms category
     * @throws Exception
     */
    public function create_categoryAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $validated_resp = $this->validate_input($data->query->message, 'add_category', 6);

        if(!$validated_resp->valid)
        {
            $this->sendError($validated_resp->errors);
        }
        else
            {
                $category_chunks = explode("|", $data->query->message);

                $message = "";

                $order_model = new OrderModel();
                $order_model->addCategory($category_chunks[4], $category_chunks[5]);

                $message .= "Category added. Here is the existing list.\n";

                $categories = $order_model->getCategories();

                foreach($categories as $category)
                {
                    $message .= $category['id'] . ". " . $category['category_name'] . "\n" . $category['description'] . "\n";
                }

                $this->sendResponse($message);
            }

    }

    public function add_menu_itemAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $validated_resp = $this->validate_input($data->query->message, 'add_menu_item', 11);

        if(!$validated_resp->valid)
        {
            $this->sendError($validated_resp->errors);
        }
        else
            {
                $item_chunks = explode("|", $data->query->message);

                $order_model = new OrderModel();
                $order_model->addMenuItem($item_chunks[4], $item_chunks[5], $item_chunks[6], $item_chunks[7], $item_chunks[8], $item_chunks[9], $item_chunks[10]);

                $this->sendResponse(
                    $this->getMenuItemsListingMessage(1)
                );
            }

    }

    public function list_menu_itemsAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $validated_resp = $this->validate_input($data->query->message, 'list_menu_items', 4);

        if(!$validated_resp->valid)
        {
            $this->sendError($validated_resp->errors);
        }
        else
            {
                $this->sendResponse(
                        $this->getMenuItemsListingMessage(1)
                    );
            }

    }

    public function change_priceAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $validated_resp = $this->validate_input($data->query->message, 'change_price', 6);

        if(!$validated_resp->valid)
        {
            $this->sendError($validated_resp->errors);
        }
        else
        {
            $message_chunks = explode("|", $data->query->message);
            $order_model = new OrderModel();

            $order_model->setItemPrice($message_chunks[4], $message_chunks[5]);
            $menu_item = $order_model->getMenuItemByCode($message_chunks[4]);

            $message = "Updated the following menu item.\n";
            $message .= $menu_item['code'] . ". " . $menu_item['item_name'];

            if($menu_item['size'] != '' || $menu_item['size'] != '-'){
                $message .= "(" . $menu_item['size'] . ")";
            }

            $message .= " " . $menu_item['description'] . " " . $menu_item['currency'] . " " . $menu_item['unit_price'] . "\n";
            $message .= "The menu\n";
            $message .= $this->getMenuItemsListingMessage(1);

            $this->sendResponse($message);
        }
    }

    public function list_roomsAction()
    {
        $data = json_decode(file_get_contents("php://input"));

        $wa_request_model = new WARequestModel();
        $wa_request_model->store_request($data->query->sender, $data->query->ruleId, $data->query->message);

        $validated_resp = $this->validate_input($data->query->message, 'list_rooms', 4);

        if(!$validated_resp->valid)
        {
            $this->sendError($validated_resp->errors);
        }
        else
        {
            $message = $this->getMenuItemsListingMessage(2);

            $this->sendResponse(
                $message
            );
        }
    }

}