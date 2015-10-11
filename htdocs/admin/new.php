<?xml version="1.0" encoding="utf-8"?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
    <h1>Webcam anlegen</h1>
    <form method="post" action="create.php">
    <table>
    <tr>
        <td>ID</td>
        <td>Neu</td>
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
