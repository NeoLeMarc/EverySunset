<?php
$db = new mysqli('localhost', 'everysunset', 'Abendrot23', 'everysunset');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

function query($sql){
    global $db;
    if(!$result = $db->query($sql)){
        die('There was an error running the query [' . $db->error . ']');
    } else
        return $result;
}
?>
