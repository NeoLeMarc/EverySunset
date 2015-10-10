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

function toSeconds(timestring){
    var sstring = timestring.split(":");
    var hours = sstring[0];
    var minutes = sstring[1];
    var seconds = sstring[2];
    var result = (hours*3600) + (minutes*60) + seconds *1;
    return result;
}

function lz(number){
    if(number < 10)
        return "0" + number;
    else
        return number;
}

function toTimestring(seconds){
    var hours = Math.floor(seconds / 3600);
    seconds = seconds - (hours*3600);
    var minutes = Math.floor(seconds/60);
    seconds = seconds - (minutes * 60);
    return "" + lz(hours) + ":" + lz(minutes) + ":" + lz(seconds);
}

var json;
var ticks = 0;
var ttsSeconds = 0;

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
        ttsSeconds = toSeconds(tts.innerHTML);
    }); 
}
init(0);

interval = window.setInterval(function() { 
    if(ticks-- > 0){
        var counter = document.getElementById("counter");
        ttsSeconds--;
        counter.innerHTML = ticks;
        tts.innerHTML = toTimestring(ttsSeconds);
    } else 
        init(0);
}, 1000)


