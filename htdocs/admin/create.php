<?php
    include "../db.php";
    $lat = $_POST["lat"];
    $lon = $_POST["lon"];
    $title = $_POST["title"];
    $url = $_POST["url"];

    $SQL = "INSERT INTO webcams SET lat = ?, lon = ?, title = ?, url = ?";
    $stmt = $db->prepare($SQL);
    $stmt->bind_param("ddss", $lat, $lon, $title, $url);
    $stmt->execute();
    $stmt->close();
    $id = $db->insert_id;

    $SQL = "INSERT INTO status SET webcam_id = ?, http_status = 0, comment = 'NOT PARSED YET'";
    $stmt = $db->prepare($SQL);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: .#$id");
?>
