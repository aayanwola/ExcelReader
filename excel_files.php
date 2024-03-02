<?php
include_once("includes/functions.php");
$dbConnection = connectToDatabase();

$pageTitle = "Excel Files";
require_once("includes/header.php");
?>

<h3>Excel Files</h3>

<ol>
    <?php
    $sql = "SELECT *
            FROM excel_files";
    $result = mysqli_query($dbConnection, $sql);

    if ($result == true) {
        while ($excelFile = mysqli_fetch_assoc($result)) { ?>
            <li style="font-size: 17px;"><a href="excel_file.php?id=<?php echo $excelFile['id']; ?>"><?php echo $excelFile['name']; ?></a></li>
    <?php }
    } ?>
</ol>

<?php
require_once("includes/footer.php");
?>