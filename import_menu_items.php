<?php 

require_once 'inc/bootstrap.php';
require_once 'Model/MenuItemModel.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

try{
    $menu_item_model = new MenuItemModel();
    $category_model = new CategoryModel();

    $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    $spreadsheetItems = $reader->load("./xlsx/Items.xlsx");

    $sheetDataItems = $spreadsheetItems->getActiveSheet()->toArray();
    // print_r($sheetDataItems); 
    

    $categories =$category_model->getAllCategory();
    unset($sheetDataItems[0]);
    // print_r($sheetDataItems[0]);
    $keys = array_column($categories, 'category_name');

    foreach($sheetDataItems as $row){
        // print_r($row);
        $index = array_search($row[0], $keys);
        // print_r($categories[$index]["id"]);
        // $category_model->getCategoryId($row[0]);

        $category_id=(int)$categories[$index]["id"];
        $item_name=$row[1]?$row[1]:"";
        $description=$row[2]?$row[2]:"";
        $size=(int)$row[3];
        $currency=$row[4];
        $unit_price=(int)$row[5];
        $type=(int)$row[6];
        $menu_item_model->insert_menu_items($category_id, $item_name, $description, $size, $currency, $unit_price, $type);
    }

}catch(Exception $e){
    echo "Exception thrown: " . $e->getMessage();

}

?>