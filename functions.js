const loader = new Image(25, 25);
loader.src = "images/loader.gif";

function setUploadExcelFormNameField(el) {
    var file = $(el)[0].files[0];

    if (file != null) {
        var fileName = file.name.split('.')[0];
        $("#upload-excel-form input[name='name']").val(fileName);
    }
}

function uploadExcel() {
    var formId = "upload-excel-form";
    var loaderSelector = "#upload-excel-form .loader";
    var uploadButtonSelector = "#upload-excel-form .upload-btn";

    $(`#${formId} .error`).html("");
    showLoader(loaderSelector);
    $(uploadButtonSelector).addClass("disabled");

    $.ajax({
        type: "POST",
        data: new FormData($(`#${formId}`)[0]),
        url: "actions/upload_excel_file_to_db.php",
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            if (response.type == "success") {
                alert("File successfuly uploaded!");
                location.href = `excel_file.php?id=${response.data}`;
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
            hideLoader(loaderSelector);
            $(uploadButtonSelector).removeClass("disabled");
        }
    });
}

function showLoader(selection) {
    $(selection).html(loader);
}

function hideLoader(selection) {
    $(selection).html("");
}