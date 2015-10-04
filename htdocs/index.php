<html>
<body>
<?php
    include "db.php";
    query("SET time_zone = '+00:00'");

    $SQL = " 
        SELECT *, if((sunset > time(now())), 
                     (timediff(sunset, time(now()))),
                     (addtime(timediff(sunset, time(now())), '24:00:00'))) as tts, time(now()) as curtime FROM status
        JOIN webcams ON webcams.id = status.webcam_id 
        WHERE http_status = 200
        ORDER BY tts 
    ";

    $result = query($SQL);

?>
<h1>Every Sunset - Webcam status</h1>
    <table border=1>
        <tr>
            <td>Title</td>
            <td>Curtime (UTC)</td>
            <td>Sunrise (UTC)</td>
            <td>Sunset (UTC)</td>
            <td>Time to sunset</td>
            <td>URL</td>
            <td>Preview</td>
        </tr>
        <?php
        while($row = $result->fetch_assoc()){?>
            <tr>
                <td><?php echo $row['title'] ?></td>
                <td><?php echo $row['curtime'] ?></td>
                <td><?php echo $row['sunrise'] ?></td>
                <td><?php echo $row['sunset'] ?></td>
                <td><?php echo $row['tts'] ?></td>
                <td><?php echo $row['url'] ?></td>
                <td><a target="_blank" href="<?php echo $row['url']?>"><img src="<?php echo $row['url'] ?>" height=100/></a></td>
            </tr>
        <?php } ?>        
    </table>
</body>
</html>
