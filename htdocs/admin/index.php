<?php
    include "../db.php";
    query("SET time_zone = '+00:00'");

    function getWebcams($active = true){
        if($active)
            $SQLactive = '1';
        else
            $SQLactive = '0';

        $SQL = " 
            SELECT *, if((sunset > time(now())), 
                         (timediff(sunset, time(now()))),
                         (addtime(timediff(sunset, time(now())), '24:00:00'))) as tts, time(now()) as curtime FROM status
            JOIN webcams ON webcams.id = status.webcam_id 
            WHERE http_status = 200
            AND   active = $SQLactive
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
        <table border=1 bgcolor=<?php echo $bgcolor; ?>>
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
                    <td><?php echo $row['url'] ?></td>
                    <td><a target="_blank" href="<?php echo $row['url']?>"><img src="<?php echo $row['url'] ?>" height=100/></a></td>
                    <td>Edit<br/>
                        <?php enableOrDisable($row) ?></br>
                        Delete</br></td>
                </tr>
            <?php } ?>        
        </table>
    <?php } ?>

<html>
<body>
<h1>Every Sunset - Webcam status</h1>

<h2>Active cams</h2>
<?php renderResults(getActiveWebcams()); ?> 

<h2>Disabled cams</h2>
<?php renderResults(getInactiveWebcams(), 'lightyellow'); ?>
</body>
</html>
