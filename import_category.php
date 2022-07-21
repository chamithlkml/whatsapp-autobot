<?php

require_once 'inc/bootstrap.php';
require_once 'Model/CategoryModel.php';
require_once 'Model/MenuItemModel.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

try{
    $menu_item_model = new MenuItemModel();

    $category_model = new CategoryModel();

    $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    $spreadsheet = $reader->load("./xlsx/Categories.xlsx");

    $sheetData = $spreadsheet->getActiveSheet()->toArray();

    unset($sheetData[0]);

    foreach($sheetData as $row){
        $category_name=$row[0];
        $description=$row[1]?$row[1]:"";
        $type=(int)$row[2];
        $category_model->insert_category($category_name, $description, $type);
    }
    
    
}catch(Exception $e){
    echo "Exception thrown: " . $e->getMessage();
    
}

?>