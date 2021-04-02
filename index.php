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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mb-5 mt-4">
        <h1 class="display-1 text-center">Tree Demo</h1>
    </div>

    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-10">
             <h2>Sort by:</h2>
             <a class="btn btn-secondary" href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"id", "orderDir"=>"ASC"))).'>ID &uarr;</a>
             <a class="btn btn-secondary" href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"id", "orderDir"=>"DESC"))).'>ID &darr;</a>
             <a class="btn btn-secondary" href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"name", "orderDir"=>"ASC"))).'>Name &uarr;</a>
             <a class="btn btn-secondary" href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"name", "orderDir"=>"DESC"))).'>Name &darr;</a><br>
        </div>
     </div>
     <div class="row justify-content-center">
        <div class="col-10">
        '.displayStruct().'
        </div>
     </div>
    <script src="script.js"></script>
</body>
</html>';


