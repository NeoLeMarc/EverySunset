<html>
<body>
<?php
    include "../db.php";
    $id = (int)$_GET['id'];
    $confirm = $_GET['confirm'];

    if($confirm == "true"){
        $sSQL = "DELETE FROM status WHERE webcam_id = ?";
        $wSQL = "DELETE FROM webcams WHERE id = ?";

        $stmt = $db->prepare($sSQL);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $db->prepare($wSQL);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: .");
    } else { 
        $SQL = "SELECT * FROM webcams WHERE id = '$id'";
        $result = query($SQL);
        $webcam = $result->fetch_assoc();
    ?>
        Webcam mit der ID <?php echo $id ?> (Titel: <?php echo $webcam['title'] ?>) wirklich l&ouml;schen?
        <br/>
        <a href="delete.php?id=<?php echo $id ?>&confirm=true">L&ouml;schen</a>
        <br/>
        <a href=".#<?php echo $id ?>">Abbrechen</a>
    <?php }
?>
</body>
</html>
