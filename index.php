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

        function uploadExcel() {
            var formId = "upload-excel-form";
            $(`#${formId} .error`).html("");

            $.ajax({
                type: "POST",
                data: new FormData($(`#${formId}`)[0]),
                url: "actions/uploadExcelFileToDb.php",
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(response) {
                    if (response.type == "success") {
                        alert("File successfuly uploaded!");
                    } else if (response.type == "error") {
                        alert(response.message);
                    } else if (response.type == "validation_error") {
                        $(`#${formId} .file-error`).html(response.errors.file_error);
                    } else {
                        alert("An unknown error occured.");
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occured: " + xhr.status + " " + xhr.statusText);
                },
                complete: function() {

                }
            });
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

        <form role="form" id="upload-excel-form" style="margin-bottom: 20px;">
            <div class="form-group">
                <input type="file" name="file" accept=".xlsx" onchange="setUploadExcelFormNameField(this)">
                <p class="file-error error"></p>
            </div>
            <!-- <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Name">
                <p class="name-error error"></p>
            </div> -->
            <button type="button" class="btn btn-default" onclick="uploadExcel()">Upload</button>
        </form>
    </div>

</body>

</html>