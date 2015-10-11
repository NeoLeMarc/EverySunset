<?php
    header("Content-Type: text/html; charset=utf-8");
    include "../db.php";
    $id = (int)$_GET["id"];
    $SQL = "SELECT * FROM webcams WHERE id = '$id'";
    $result = query($SQL);
    $webcam = $result->fetch_assoc();
?>
<?xml version="1.0" encoding="utf-8"?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
    <h1>Webcam bearbeiten</h1>
    <form method="post" action="update.php?id=<?php echo $webcam['id'] ?>">
    <table>
    <tr>
        <td>ID</td>
        <td><?php echo $webcam['id'] ?></td>
    </tr>
    <tr>
        <td>L&auml;ngengrad</td>
        <td><input name="lon" type="text" size="8" value="<?php echo $webcam['lon'] ?>"/></td>
    </tr>
    <tr>
        <td>Breitengrad</td>
        <td><input name="lat" type="text" size="8" value="<?php echo $webcam['lat'] ?>"/></td>
    </tr>
    <tr>
        <td>Titel</td>
        <td><input name="title" type="text" size="100" value="<?php echo $webcam['title'] ?>"/></td>
    </tr>
    <tr>
        <td>URL</td>
        <td><input name="url" type="text" size="100" value="<?php echo $webcam['url'] ?>"/></td>
    </tr>
    <tr>
        <td><input type="submit" value="Speichern"></td>
        <td><a href="./#<?php echo $webcam['id'] ?>">Abbrechen</a></td>
    </tr>
    </table>
    </form>
</html>
</body>
