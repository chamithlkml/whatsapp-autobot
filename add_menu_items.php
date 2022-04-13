<?php
require __DIR__ . "/inc/bootstrap.php";

$food_model = new OrderModel();

//$menu_items = [
//    '15|Name|Description|Size|Currency|Price'
//];

$menu_items = [
    '44|Gold Leaf|-|12 pack|LKR|960',
    '44|Dunhill|-|20 pack|LKR|1700',
];

foreach($menu_items as $menu_item){
    $chunks = explode("|", $menu_item);

    $food_model->addMenuItem(
        intval($chunks[0]), #Category ID
        $chunks[1],#Item Name
        $chunks[2],#Description
        $chunks[3],#Size
        $chunks[4],#Currency
        $chunks[5]#Price
    );
}