<?php
function response($type, $data="") {
	if ($type == "success") {
		$response['type'] = $type;
		$response['data'] = $data;
	} elseif ($type == "error") {
		$response['type'] = $type;
		$response['message'] = $data;
	} elseif ($type == "validation_error") {
		$response['type'] = $type;
		$response['errors'] = $data;
	} else {
		$response['type'] = "error";
		$response['message'] = "Invalid response type";
	}
	
	echo json_encode($response);
	exit();
}

function connectToDatabase() {
    $db_conx = @mysqli_connect("localhost", "ajibola_ayanwola", "ajballa1", "ajibola_excel_reader");

    if (!$db_conx) {
        response("error", "Could not connect to database: ".mysqli_connect_error());
    }
    else {
        return $db_conx;
    }
}
?>