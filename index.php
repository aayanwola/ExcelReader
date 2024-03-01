<?php
$pageTitle = "Excel Uploader";
require_once("includes/header.php");
?>

<h3>Excel Uploader</h3>

<form role="form" id="upload-excel-form" style="margin-bottom: 20px;">
    <div class="form-group">
        <input type="file" name="file" accept=".xlsx" onchange="setUploadExcelFormNameField(this)">
        <p class="file-error error"></p>
    </div>
    <!-- <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Name">
                <p class="name-error error"></p>
            </div> -->
    <button type="button" class="btn btn-default upload-btn" onclick="uploadExcel()" style="margin-bottom: 10px;">Upload</button>
    <div class="loader"></div>
</form>

<?php
require_once("includes/footer.php");
?>