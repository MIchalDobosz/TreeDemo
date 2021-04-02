<?php

function getDbConnection()
{
    $connection_string = "host=localhost port=5432 dbname=TreeDemo user=postgres password=root";
    return pg_connect($connection_string);
}


function getStruct($connection, $orderBy, $orderDir)
{
    $query = pg_query($connection, "SELECT * FROM struct ORDER BY ".$orderBy." ".$orderDir);

    $struct = array();
    while ($row = pg_fetch_object($query)) {
        $struct[]=$row;
    }

    return $struct;
}


function displayStruct() {

    $connection = getDbConnection();

    if (isset($_GET['orderBy']) && isset($_GET['orderDir'])) {
        $struct = getStruct($connection, $_GET['orderBy'], $_GET['orderDir']);
    } else {
        $struct = getStruct($connection, "id", "ASC");
    }

    $root = "";
    foreach ($struct as $item) {
        if ($item->parent_id == "") {
            $root = $item;
        }
    }

    if (isset($_GET['id'])) {
        foreach ($struct as $item) {
            if ($item->id == $_GET['id']) {
                if ($item->id == $root->id) {
                    return '<ul>' . structToHtmlList($struct, $item, $root) . '</ul>';
                } else {
                    return '<a href="index.php?id=' . $root->id . '">&larr;Home</a> <a href="index.php?id=' . $item->parent_id . '">&larr;Parent</a> <ul>' . structToHtmlList($struct, $item, $root) . '</ul>';
                }
            }
        }
    } else {
        header("Location: index.php?id=".$root->id);
    }
}


function structToHtmlList($struct, $currentItem, $root) {

    $placeholder = "Rename";

    if ($currentItem->id == $root->id) {
        $return = '<li class="liItem"><a href="index.php?id=' . $currentItem->id . '">' . $currentItem->name . '</a>
                <input id="optionsToggle' . $currentItem->id . '" type="button" class="optionsToggle btn btn-secondary btn_change" onclick="displayOptions(' . $currentItem->id . ')" value="Options">
                <form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post" style="display: inline">
                <input id="id' . $currentItem->id . '" class="id" type="text" name="id" value="' . $currentItem->id . '" style="display: none"> 
                <input id="editInput' . $currentItem->id . '" class="editInput" type="text" name="editInput" placeholder="'.$placeholder.'" style="display: none"> 
                <input id="editSubmit' . $currentItem->id . '" class="editSubmit btn btn-primary btn_change" type="submit" value="Update" style="display: none"></form>
                <form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post" style="display: inline">
                <form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post" style="display: inline">
                <input id="idNew' . $currentItem->id . '" class="idNew" type="text" name="idNew" value="' . $currentItem->id . '" style="display: none"> 
                <input id="newInput' . $currentItem->id . '" class="newInput" type="text" name="newInput" placeholder="Enter name" style="display: none"> 
                <input id="newSubmit' . $currentItem->id . '" class="newSubmit" type="submit" value="Add" style="display: none"></form>
                <form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post" style="display: inline">
                <input id="idNew' . $currentItem->id . '" class="idNew" type="text" name="idNew" value="' . $currentItem->id . '" style="display: none"> 
                <input id="newInput' . $currentItem->id . '" class="newInput" type="text" name="newInput" placeholder="Enter name" style="display: none"> 
                <input id="newSubmit' . $currentItem->id . '" class="newSubmit btn btn-success btn_change" type="submit" value="Add" style="display: none"></form></li>';
    } else {
        $options = '';
        $nodeIds = explode(",", nodeToString($struct, $currentItem));

        foreach ($struct as $item) {
            if (!in_array($item->id, $nodeIds) && $item->id != $currentItem->parent_id) {
                $options .= '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        }

        $return = '<li class="liItem"><a href="index.php?id=' . $currentItem->id . '">' . $currentItem->name . '</a>
                <input id="optionsToggle' . $currentItem->id . '" type="button" class="optionsToggle btn btn-secondary btn_change" onclick="displayOptions(' . $currentItem->id . ')" value="Options">
                <form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post" style="display: inline">
                <input id="id' . $currentItem->id . '" class="id" type="text" name="id" value="' . $currentItem->id . '" style="display: none"> 
                <input id="editInput' . $currentItem->id . '" class="editInput" type="text" name="editInput" placeholder="'.$placeholder.'" style="display: none"> 
                <select id ="editSelect' . $currentItem->id . '" class="editSelect" name="editSelect" style="display: none"><option value="" selected disabled hidden>Change parent</option>' . $options . '</select>
                <input id="editSubmit' . $currentItem->id . '" class="editSubmit  btn btn-primary btn_change" type="submit" value="Update" style="display: none"></form>
                <form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post" style="display: inline">
                <input id="idDelete' . $currentItem->id . '" class="idDelete" type="text" name="idDelete" value="' . $currentItem->id . '" style="display: none"> 
                <input id="delete' . $currentItem->id . '" type="submit" style="display: none" value="Delete" class=" btn btn-danger btn_change"></form>
                <form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post" style="display: inline">
                <input id="idNew' . $currentItem->id . '" class="idNew" type="text" name="idNew" value="' . $currentItem->id . '" style="display: none"> 
                <input id="newInput' . $currentItem->id . '" class="newInput" type="text" name="newInput" placeholder="Enter name" style="display: none"> 
                <input id="newSubmit' . $currentItem->id . '" class="newSubmit  btn btn-success btn_change" type="submit" value="Add" style="display: none"></form></li>';
    }

    $childCheck = 0;
    foreach ($struct as $item) {
        if ($item->parent_id == $currentItem->id) {
            if ($childCheck == 0) {
                $return .= "<ul>";
                $childCheck = 1;
            }
            $return .= structToHtmlList($struct, $item, $root);
        }
    }
    if ($childCheck == 1) {
        $return .= "</ul>";
    }

    return $return;
}

function delete($itemId) {

    $connection = getDbConnection();
    $struct = getStruct($connection, "id", "ASC");
    $currentItem = "";
    foreach ($struct as $item) {
        if ($item->id == $itemId) {
            $currentItem = $item;
        }
    }

    $deletionString = nodeToString($struct, $currentItem);
    pg_query($connection, 'DELETE FROM struct WHERE id IN('.$deletionString.')');
    pg_close($connection);
}

function nodeToString($struct, $currentItem) {

    $return = "";
    foreach ($struct as $item) {
        if ($item->parent_id == $currentItem->id) {
            $return .= nodeToString($struct, $item).",";
        }
    }
    $return .= $currentItem->id;
    return $return;

}

function renameItem($editInput, $id) {

    $connection = getDbConnection();
    pg_query($connection, "UPDATE struct SET name = '".$editInput."' WHERE id = ".$id);
    pg_close($connection);
    header("Location: index.php");
}

function moveTo($selectInput, $id) {

    $connection = getDbConnection();
    pg_query($connection, "UPDATE struct SET parent_id = '".$selectInput."' WHERE id = ".$id);
    pg_close($connection);
    header("Location: index.php");
}

function add($nameInput, $id) {

    $connection= getDbConnection();
    pg_query($connection, "INSERT INTO struct (name, parent_id) VALUES ('".$nameInput."', ".$id.")");
    pg_close($connection);
    header("Location: index.php");
}
