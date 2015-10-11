<?php
    include "../db.php";
    query("SET time_zone = '+00:00'");

    function getWebcams($active = true){
        if($active)
            $SQLactive = 'WHERE active = 1 AND http_status = 200';
        else
            $SQLactive = ' WHERE active = 0 OR http_status != 200 OR http_status IS NULL';

        $SQL = " 
            SELECT *, if((sunset > time(now())), 
                         (timediff(sunset, time(now()))),
                         (addtime(timediff(sunset, time(now())), '24:00:00'))) as tts, time(now()) as curtime FROM status
            LEFT JOIN webcams ON webcams.id = status.webcam_id 
            $SQLactive
            ORDER BY tts 
        ";
        $result = query($SQL);
        $aresults = [];
        while($row = $result->fetch_assoc())
            $aresults[] = $row;
        return $aresults;
    }

    function getActiveWebcams(){
        return getWebcams(true);
    }

    function getInactiveWebcams(){
        return getWebcams(false);
    }

    function enableOrDisable($row){
        ?><a href="togglestatus.php?id=<?php echo $row['id']?>"><?php
        if($row['active'] == 1){
            ?>Disable<?php
        } else {
            ?>Enable<?php
        } 
        ?></a><?php
    }

    function renderResults($results, $bgcolor="lightgreen"){ ?>
        <table border=1 bgcolor=<?php echo $bgcolor; ?> width="1024">
            <tr>
                <td>Title</td>
                <td>ID</td>
                <td>Curtime (UTC)</td>
                <td>Sunrise (UTC)</td>
                <td>Sunset (UTC)</td>
                <td>Time to sunset</td>
                <td>URL</td>
                <td>Preview</td>
                <td>Options</td>
            </tr>
            <?php
                foreach($results as $row){?>
                <tr>
                    <td><?php echo $row['title'] ?></td>
                    <td><a name="<?php echo $row['id'] ?>"/><?php echo $row['id'] ?></td>
                    <td><?php echo $row['curtime'] ?></td>
                    <td><?php echo $row['sunrise'] ?></td>
                    <td><?php echo $row['sunset'] ?></td>
                    <td><?php echo $row['tts'] ?></td>
                    <td><?php echo substr($row['url'], 0, 100) ?></td>
                    <td><?php if($row['http_status'] == 200){?><a target="_blank" href="<?php echo $row['url']?>"><img src="<?php echo $row['url'] ?>" height=100/></a><?php } else echo "HTTP FETCH FAILED";?></td>
                    <td><a href="edit.php?id=<?php echo $row['id'] ?>">Edit</a><br/>
                        <?php enableOrDisable($row) ?></br>
                        <a href="delete.php?id=<?php echo $row['id'] ?>">Delete</a></br></td>
                </tr>
            <?php } ?>        
        </table>
    <?php } ?>

<html>
<body>
<input type="button" value="Sonnenunterg&auml;nge neu berechnen" onclick="javascript:window.location='regenerate.php'"/>
(Kann mehrere Minuten ben&ouml;tigen)<br/>
<input type="button" value="Neue Webcam anlegen" onclick="javascript:window.location='new.php'"/>

<h1>Every Sunset - Webcam status</h1>

<h2>Active cams</h2>
<?php renderResults(getActiveWebcams()); ?> 

<h2>Disabled cams</h2>
<?php renderResults(getInactiveWebcams(), 'lightyellow'); ?>
</body>
</html>
