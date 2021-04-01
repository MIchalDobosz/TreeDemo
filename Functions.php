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

    $return = '<a href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"id", "orderDir"=>"ASC"))).'>Id ASC</a>
               <a href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"id", "orderDir"=>"DESC"))).'>Id DESC</a>
               <a href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"name", "orderDir"=>"ASC"))).'>Name ASC</a>
               <a href=index.php?'.http_build_query(array_merge($_GET, array("orderBy"=>"name", "orderDir"=>"DESC"))).'>Name DESC</a><br>';
    $connection = getDbConnection();

    if (isset($_GET['orderBy']) && isset($_GET['orderDir'])) {
        $struct = getStruct($connection, $_GET['orderBy'], $_GET['orderDir']);
    } else {
        $struct = getStruct($connection, "id", "ASC");
    }

    if (isset($_GET['id']) && !isset($_GET['d'])) {
        foreach ($struct as $item) {
            if ($item->id == $_GET['id']) {
                $return .= "<ul>".structToHtmlList($struct, $item)."</ul>";
                return $return;
            }
        }
    } elseif (isset($_GET['id']) && isset($_GET['d']) && $_GET['d'] == 1) {
        delete($connection, $struct, $_GET['id']);
    } else {
        $root = "";
        foreach ($struct as $item) {
            if ($item->parent_id == "") {
                $root = $item;
            }
        }
        header("Location: index.php?id=".$root->id);
    }
}


function structToHtmlList($struct, $currentItem) {

    $options = '';
    foreach ($struct as $item) {
        if ($item->id != $currentItem->id && $item->id != $currentItem->parent_id && $item->parent_id != $currentItem->id) {
            $options .= '<option value="'.$item->id.'">'.$item->name.'</option>';
        }
    }

    $return = '<form action='.htmlspecialchars($_SERVER["PHP_SELF"]).' method="post"><li class="liItem"><a href="index.php?id='.$currentItem->id.'">'.$currentItem->name.'</a>
                <input id="optionsToggle'.$currentItem->id.'" type="button" class="optionsToggle" onclick="displayOptions('.$currentItem->id.')" value="Options">
                <a id="delete'.$currentItem->id.'" href="index.php?id='.$currentItem->id.'&d=1" style="display: none"> Delete</a>
                <input id="id'.$currentItem->id.'" class="id" type="text" name="id" value="'.$currentItem->id.'" style="display: none"> 
                <input id="editInput'.$currentItem->id.'" class="editInput" type="text" name="editInput" style="display: none"> 
                <select id ="editSelect'.$currentItem->id.'" class="editSelect" name="editSelect" style="display: none"><option value="" selected disabled hidden>Change parent</option>'.$options.'</select>
                <input id="editSubmit'.$currentItem->id.'" class="editSubmit" type="submit" value="Update" style="display: none">
                </li></form>';

    $childCheck = 0;
    foreach ($struct as $item) {
        if ($item->parent_id == $currentItem->id) {
            if ($childCheck == 0) {
                $return .= "<ul>";
                $childCheck = 1;
            }
            $return .= structToHtmlList($struct, $item);
        }
    }
    if ($childCheck == 1) {
        $return .= "</ul>";
    }

    return $return;
}

function delete($connection, $struct, $itemId) {

    $currentItem = "";
    foreach ($struct as $item) {
        if ($item->id == $itemId) {
            $currentItem = $item;
        }
    }

    $deletionString = nodeToString($struct, $currentItem);
    pg_query($connection, 'DELETE FROM struct WHERE id IN('.$deletionString.')');
    header("Location: index.php");
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
