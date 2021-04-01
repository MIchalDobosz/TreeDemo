<?php
include "Functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['editInput']) && isset($_POST['id']) && $_POST['editInput'] != "") {
        $editInput = $_POST['editInput'];
        $id = $_POST['id'];
        renameItem($editInput, $id);
    }

    if (isset($_POST['editSelect']) && isset($_POST['id']) && $_POST['editSelect'] != "") {
        $selectInput = $_POST['editSelect'];
        $id = $_POST['id'];
        moveTo($selectInput, $id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TreeDemo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php
    echo displayStruct();
    ?>

    <script src="script.js"></script>
</body>
</html>