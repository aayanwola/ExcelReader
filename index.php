<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        function setUploadExcelFormNameField(el) {
            var file = $(el)[0].files[0];

            if (file != null) {
                var fileName = file.name.split('.')[0];
                $("#upload-excel-form input[name='name']").val(fileName);
            }
        }
    </script>
</head>

<body>

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">WebSiteName</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Page 1-1</a></li>
                            <li><a href="#">Page 1-2</a></li>
                            <li><a href="#">Page 1-3</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Page 2</a></li>
                    <li><a href="#">Page 3</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3>Excel Reader</h3>

        <form role="form" id="upload-excel-form" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
            <div class="form-group">
                <input type="file" name="file" accept=".xlsx" onchange="setUploadExcelFormNameField(this)">
                <p class="file-error error"></p>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Name">
                <p class="name-error error"></p>
            </div>
            <button type="submit" class="btn btn-default" onclick="uploadExcel()">Upload</button>
        </form>

        <?php

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        use \PhpOffice\PhpSpreadsheet\IOFactory;

        if (!empty($_POST)) {
            require 'vendor/autoload.php';
            
            // print_r($_POST);
            // print_r($_FILES);

            //$file_ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            //$file_temp_location = $_FILES["file"]["tmp_name"];
            //$file_upload_error_code = $_FILES["file"]["error"];

            $filePath = $_FILES["file"]["tmp_name"];
            /*$reader = IOFactory::createReaderForFile($filePath);
            $excelObj = $reader->load($filePath);*/
            $excelObj = IOFactory::load($filePath);

            //$workSheet = $excelObj->getActiveSheet();
            $workSheet = $excelObj->getSheet('0'); // get first sheet

            //echo "<br>A1: {$workSheet->getCell("A1")->getValue()}<br>";

            $lastRow = $workSheet->getHighestDataRow();
            $lastColumn = $workSheet->getHighestDataColumn();

            //echo "<br>Row ends at: $lastRow<br>";
            //echo "<br>Column ends at: $lastColumn<br>";

            echo '<table class="table table-striped table-hover">';
            for ($row = 1; $row <= $lastRow; $row++) {
                echo "<tr>";
                foreach (range("A", $lastColumn) as $column) {
                    echo "<td>";
                    echo $workSheet->getCell($column . $row)->getValue();
                    echo "</td>";
                }
                echo "</tr>";
            }
            echo '</table>';
        }
        ?>
    </div>

</body>

</html>