<?php

/**
 * 1. Truncate the 2 tables before start
 * 2. Rename inc/.config.php to inc/config.php ************
 * 3. Replace database credentials **********
 * 4. require_once /path/to/inc/bootstrap.php *******
 * 5. Use Model/OrderModel.php file's existing methods
 * 6. AddCategory($name, $description)
 * 7. addMenuItem($category_id, $item_name, $description, $size, $currency, $price, $type)
 * 8. print_r($var); die;
 * 9. echo 'test...';
 * 10. repair Lib/composer.json and `run php composer.phar`
 */

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

    // print_r($sheetData); 
    foreach($sheetData as $row){
        $category_name=$row[0];
        $description=$row[1]?$row[1]:"";
        $type=(int)$row[2];
        $category_model->insert_category($category_name, $description, $type);
    }
    
    
}catch(Exception $e){
    echo "Exception thrown: " . $e->getMessage();
    // echo "Trace: " . $e->
}

?>