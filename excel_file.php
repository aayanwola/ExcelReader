<?php
include_once("includes/functions.php");
$dbConnection = connectToDatabase();

$excelFile = null;

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $excelFileId = $_GET['id'];

    $sql = "SELECT * 
            FROM excel_files
            WHERE id=$excelFileId
            LIMIT 1";
    $result = mysqli_query($dbConnection, $sql);

    if ($result == true) {
        $excelFile = mysqli_fetch_assoc($result);
    }
}

if ($excelFile == null) {
    $pageTitle = "Excel File Not Found";
} else {
    $pageTitle = $excelFile['name'];
}

require_once("includes/header.php");
?>

<?php if ($excelFile == null) { ?>
    <h3>Excel File Not Found</h3>
<?php } else { ?>
    <h3>Excel File: <?php echo $excelFile['name']; ?></h3>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Age</th>
                <th>City</th>
                <th>State</th>
                <th>Transferred?</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * 
                    FROM excel_rows
                    WHERE excel_file_id=$excelFileId";
            $result = mysqli_query($dbConnection, $sql);

            if ($result == true) {
                while ($excelRow = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $excelRow['firstname']; ?></td>
                        <td><?php echo $excelRow['lastname']; ?></td>
                        <td><?php echo $excelRow['age']; ?></td>
                        <td><?php echo $excelRow['city']; ?></td>
                        <td><?php echo $excelRow['state']; ?></td>
                        <td></td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
<?php } ?>

<?php
require_once("includes/footer.php");
?>