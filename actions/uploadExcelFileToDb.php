<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

require '../vendor/autoload.php';
include_once("../includes/functions.php");

$dbConnection = connectToDatabase();
$validationErrors = [];
$validationErrors["file_error"] = "";

// BEGIN excel file validation
if (empty($_FILES["file"]["name"])) {
    //response("error", "File was not posted.");
    $validationErrors["file_error"] = "Please select a file.";
} else {
    $fileName = mysqli_real_escape_string($dbConnection, pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME));
    $fileExt = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    $filePath = $_FILES["file"]["tmp_name"];

    if (strtolower($fileExt) != "xlsx" && strtolower($fileExt) != "xls") {
        $validationErrors["file_error"] = "Please select an excel file.";
    } else {
        $sql = "SELECT id 
                FROM excel_files
                WHERE name='$fileName'
                LIMIT 1";
        $result = mysqli_query($dbConnection, $sql);

        if ($result == false) {
            $mysqliError = mysqli_error($dbConnection);
            response("error", "An error occured. Could not check if excel_file already exists in db: $mysqliError");
        }

        if (mysqli_num_rows($result) > 0) {
            $validationErrors["file_error"] = "This excel file was already uploaded.";
        }
    }
}
// END excel file validation

// echo "<br>fileName: $fileName<br>";
// echo "<br>fileExt: $fileExt<br>";
// echo "<br>filePath: $filePath<br>";

foreach ($validationErrors as $validationError) {
    if ($validationError != "") {
        response("validation_error", $validationErrors);
    }
}

// Create excel file in db.
$sql = "INSERT INTO excel_files (name)
		VALUES ('$fileName')";
$result = mysqli_query($dbConnection, $sql);

if ($result == false) {
    $mysqliError = mysqli_error($dbConnection);
    response("error", "An error occured. Could not create excel_file row in db: $mysqliError");
}

$excelFileId = mysqli_insert_id($dbConnection);

// Add rows to excel_rows table.
$excelObj = IOFactory::load($filePath);
$workSheet = $excelObj->getSheet('0'); // get first sheet
$lastRow = $workSheet->getHighestDataRow();
$lastColumn = $workSheet->getHighestDataColumn();

for ($row = 2; $row <= $lastRow; $row++) {
    $valuesArray = [];

    foreach (range("A", $lastColumn) as $column) {
        $cellValue = $workSheet->getCell($column . $row)->getValue();
        $valuesArray[$column] = mysqli_real_escape_string($dbConnection, $cellValue);
    }
    
    $sql = "INSERT INTO excel_rows (firstname, lastname, age, city, state, excel_file_id)
		    VALUES ('".implode("', '", $valuesArray)."', '$excelFileId')";

    $result = mysqli_query($dbConnection, $sql);

    if ($result == false) { // If any rows failed to insert, delete all rows that were inserted along with the excel_file row that was created.
        deleteExcelFileFromDb($excelFileId, $dbConnection);
        $mysqliError = mysqli_error($dbConnection);
        response("error", "An error occured. Could not create excel_row in db for excel_file with id=$excelFileId: $mysqliError");
    }
}

response("success", $excelFileId);


// FUNCTIONS
function deleteExcelFileFromDb($excelFileId, $dbConnection) // Test function after creating first file in db: deleteExcelFileFromDb(1, $dbConnection);
{
    $sql = "DELETE FROM excel_files WHERE id=$excelFileId";
    $result = mysqli_query($dbConnection, $sql);

    if ($result == false) {
        $mysqliError = mysqli_error($dbConnection);
        response("error", "An error occured. Could not delete excel_file with id=$excelFileId from db: $mysqliError");
    }

    $sql = "DELETE FROM excel_rows WHERE excel_file_id=$excelFileId";
    $result = mysqli_query($dbConnection, $sql);

    if ($result == false) {
        $mysqliError = mysqli_error($dbConnection);
        response("error", "An error occured. Could not delete excel_rows for excel_file with id=$excelFileId from db: $mysqliError");
    }
}
