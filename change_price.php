<?php
require __DIR__ . "/inc/bootstrap.php";

$food_model = new OrderModel();

$price_changes = [
    '84|1350'
];

foreach($price_changes as $price_change){
    $chunks = explode("|", $price_change);

    $food_model->setItemPrice(intval($chunks[0]), intval($chunks[1]));
}