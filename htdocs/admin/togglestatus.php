<?php
include "../db.php";
$id = (int)$_GET["id"];
$SQL = "UPDATE webcams SET active = !active WHERE id = ?";
$stmt = $db->prepare($SQL);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
header("Location: .#$id");
?>
