<?php
include "Functions.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['editInput']) && isset($_POST['id']) && $_POST['editInput'] != "") {
        $editInput = $_POST['editInput'];
        $id = $_POST['id'];
        if (preg_match("/^[a-zA-Z0-9]{1,32}$/", $editInput)) {
            renameItem($editInput, $id);
        }
    }

    if (isset($_POST['editSelect']) && isset($_POST['id']) && $_POST['editSelect'] != "") {
        $selectInput = $_POST['editSelect'];
        $id = $_POST['id'];
        moveTo($selectInput, $id);
    }

    if (isset($_POST['idDelete'])) {
        $id = $_POST['idDelete'];
        delete($id);
    }

    if (isset($_POST['newInput']) && isset($_POST['idNew']) && $_POST['newInput'] != "") {
        $newInput = $_POST['newInput'];
        $id = $_POST['idNew'];
        if (preg_match("/^[a-zA-Z0-9]{1,32}$/", $newInput)) {
            add($newInput, $id);
        }
    }

}

    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TreeDemo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

     <a href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"id", "orderDir"=>"ASC"))).'>Id ASC</a>
     <a href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"id", "orderDir"=>"DESC"))).'>Id DESC</a>
     <a href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"name", "orderDir"=>"ASC"))).'>Name ASC</a>
     <a href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"name", "orderDir"=>"DESC"))).'>Name DESC</a><br>
     '.displayStruct().'
    
    <script src="script.js"></script>
</body>
</html>';


