function loadJSON(callback) {   

    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType("application/json");
    xobj.open('GET', 'https://www.essential-operations.com/everysunset/webservices.php', true); 
    xobj.onreadystatechange = function () {
        if (xobj.readyState == 4 && xobj.status == "200") {
            // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
            callback(xobj.responseText);
        }
    };
    xobj.send(null);  
}
var json;
var ticks = 0;
function init(position) {
    loadJSON(function(response) {
        // Parse JSON string into object
        json = JSON.parse(response);
        var webcam = document.getElementById("bild");
        webcam.src = json["webcams"][position]["url"] + "?foo=" + new Date().getTime() ;
        webcam.alt = json["webcams"][position]["title"];
        var title = document.getElementById("title");
        title.innerHTML = webcam.alt;
        var tts = document.getElementById("tts");
        tts.innerHTML = json["webcams"][position]["tts"];
        ticks = 240;
    }); 
}
init(0);

interval = window.setInterval(function() { 
    if(ticks-- > 0){
        var counter = document.getElementById("counter");
        counter.innerHTML = ticks;
    } else 
        init(0);
}, 1000)


