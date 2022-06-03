<?php
require __DIR__ . "/inc/bootstrap.php";

$food_model = new OrderModel();

//$menu_items = [
//    '15|Name|Description|Size|Currency|Price'
//];

$menu_items = [
    '48|Deluxe Family|4 persons max|room only|LKR|9900|2',
    '48|Deluxe Family|4 persons max|bread & breakfast|LKR|12025|2',
    '48|Deluxe Family|4 persons max|half board|LKR|18295|2',
    '48|Deluxe Family|4 persons max|full board|LKR|21430|2',
];

foreach($menu_items as $menu_item){
    $chunks = explode("|", $menu_item);

    $food_model->addMenuItem(
        intval($chunks[0]), #Category ID
        $chunks[1],#Item Name
        $chunks[2],#Description
        $chunks[3],#Size
        $chunks[4],#Currency
        $chunks[5],#Price
        $chunks[6]#Type
    );
}