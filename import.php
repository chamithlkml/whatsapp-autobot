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


include 'vendor/autoload.php';

require_once PROJECT_ROOT_PATH . '/inc/bootstrap.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;


$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$spreadsheet = $reader->load("Categories.xlsx");

// $data=$spreadsheet->getSheet(0)->toArray();

// echo count($d);

$sheetData = $spreadsheet->getActiveSheet()->toArray();

$i=1;

foreach ($sheetData as $row) {
    $insert_data = array(
        ':category_name' =>$row[0],
        ':description' =>$row[1],
        ':created_on' =>$row[2],
        ':type' =>$row[3]
    );
 
	echo $i."---".$row[0].",".$row[1]." <br>";
	$i++;

    $statement->execute($insert_data);
}





// if($_FILES["import_excel"]["name"]!='')
// {
//     $allowed_extension = array('xls','csv','xlsx');
//     $file_array = explode(".",$_FILES["import_excel"]["name"]);
//     $file_extension = end($file_array);

//     if(in_array($file_extension, $allowed_extension))
//     {
//         $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify
//         ($_FILES["import_excel"]["name"]);
//         $reader = \PhpOffice\PhpSpreadsheet\IOFactory::
//         createReader($file_type);

//         $spreadsheet = $reader->load($_FILES["import_excel"]["tmp_name"]);

//         $data = $spreadsheet->getActiveSheet()->toArray();

//         foreach($data as $row)
//         {
//             $insert_data = array(
//                 ':category_name' =>$row[0],
//                 ':description' =>$row[1],
//                 ':created_on' =>$row[2],
//                 ':type' =>$row[3]
//             );

//             $query = 'INSERT INTO whatsapp_item_categories (category_name,description,created_on,type)
//             VALUES (:category_name,:description,:created_on,:type)';

//             $statement = $connect->prepare($query);
//             $statement->execute($insert_data);
//             }

//             $message = '<div class = "alert alert-success"> Data Imported Successfully </div>';



//     }
//     else
//     {
//         $message = '<div class = "alert alert-danger"> Only .xls .csv or .xlsx file allowed </div>';

//     }
// }
// else
// {
//     $message = '<div class="alert alert-danger"> Please Select File</div>';

// }

// echo $message;

?>