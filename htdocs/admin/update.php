<?php
    include "../db.php";
    $id = (int)$_GET["id"];
    $lat = $_POST["lat"];
    $lon = $_POST["lon"];
    $title = $_POST["title"];
    $url = $_POST["url"];

    $SQL = "UPDATE webcams SET lat = ?, lon = ?, title = ?, url = ? WHERE id = ?";
    $stmt = $db->prepare($SQL);
    $stmt->bind_param("ddssi", $lat, $lon, $title, $url, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: .#$id");
?>
