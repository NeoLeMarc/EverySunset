<?php
header("Access-Control-Allow-Origin: *");
    include "db.php";
    query("SET time_zone = '+00:00'");

    $SQL = " 
        SELECT *, if((sunset > time(now())), 
                     (timediff(sunset, time(now()))),
                     (addtime(timediff(sunset, time(now())), '24:00:00'))) as tts, time(now()) as curtime FROM status
        JOIN webcams ON webcams.id = status.webcam_id 
        WHERE http_status = 200
        AND   active = 1
        ORDER BY tts 
        LIMIT 3 
    ";

    $result = query($SQL);
    $count = $result->num_rows;
?>
{"webcams": [
        <?php
        while($row = $result->fetch_assoc()){?>
                {"title": "<?php echo $row['title'] ?>", "sunset": "<?php echo $row['sunset'] ?>", "tts": "<?php echo $row['tts'] ?>", "url": "<?php echo $row['url'] ?>"}

        <?php 
            if(--$count > 0) echo ",";
        }
        ?>        
]}
