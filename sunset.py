#!/usr/bin/env python
# -*- encoding: utf-8
# http://lexikon.astronomie.info/zeitgleichung/
import math, pytz, time, re, urllib2, socket
timeout = 5
socket.setdefaulttimeout(timeout)
from math import *
from datetime import time as dtime
from datetime import datetime
import sys
sys.path.append("pytzwhere")
from tzwhere import tzwhere
tz = tzwhere.tzwhere(shapely=True, forceTZ=True)
sunriseAngle = math.radians(-50/60)
day = datetime.now().timetuple().tm_yday

def coordinatesToDecimal(strCoordinates):
    a, b = map (lambda x : re.findall('\d+|N|W|S|O', x), strCoordinates.split(','))
    lat = dmsToDD(a[0], a[1], 0, a[2].strip())
    lon = dmsToDD(b[0], b[1], 0, b[2].strip())
    print "Lat: %f - Long: %f" % (lat, lon)
    return lat, lon

def dmsToDD(d, m = 0, s = 0, h = 'N'):
    if h in ('N', 'O'): 
        sign = +1
    else:
        sign = -1 
    return (float(d) + (float(m)/60) + (float(s)/3600))*sign

def getTimeDifference(ilat):
    lat = math.radians(ilat)
    dekl= 0.4095 * math.sin(0.016906*(day - 80.086))
    return 12 * acos((sin(sunriseAngle) - sin(lat)*sin(dekl)) / (cos(lat)*cos(dekl)))/math.pi

def adjust(time, lon):
    return time - lon/15

def getSunrise(lat, lon, timezone):
    return adjust(12 - getTimeDifference(lat), lon)

def getSunset(lat, lon, timezone):
    return adjust(12 + getTimeDifference(lat), lon)

def toClockTime(itime):
    if (itime < 0):
        itime = itime + 24
    if (itime > 24):
        itime = itime - 24
    hours = int(itime)
    minutes = (itime - hours) * 60
    return dtime(int(hours), int(minutes)).strftime('%H:%M')

def getLocalTime(stime, timezone):
    stime = stime + time.strftime(" - %Y-%m-%d")
    tz = pytz.timezone(timezone)
    target = tz.utcoffset(datetime.now(), timezone)
    pd = datetime(*time.strptime(stime, '%H:%M - %Y-%m-%d')[:6])
    targettime = pd + target
    return time.strftime('%H:%M', targettime.timetuple()) 

def lookupCoordinates(lat, lon):
     timezone = tz.tzNameAt(lat, lon)
     print "Timezone: %s" % timezone
     print "timeDifference: %f" % getTimeDifference(lat)
     print "Sunrise: %s (%f) UTC" % (toClockTime(getSunrise(lat, lon, timezone)), getSunrise(lat, lon, timezone))
     print "Sunset:  %s (%f) UTC" % (toClockTime(getSunset(lat, lon, timezone)), getSunset(lat, lon, timezone))
     print "Sunrise: %s LOC" % getLocalTime(toClockTime(getSunrise(lat, lon, timezone)), timezone)
     print "Sunset:  %s LOC" % getLocalTime(toClockTime(getSunset(lat, lon, timezone)), timezone)
     return toClockTime(getSunrise(lat, lon, timezone)), toClockTime(getSunset(lat, lon, timezone))
 
def interactiveMode():
    while True:
        try:
            lat, lon = coordinatesToDecimal(raw_input("Coordinates>"))
            lookupCoordinates(lat, lon)
            print "----------------------------"
        except e:
            print e

def getHttpStatus(url):
    print "fetching url: %s" % url
    try:
        response = urllib2.urlopen(url)
        code = response.getcode()
        response.close()
    except:
        code = 999 
    return code

def dbMode():
    errordata = []
    updata = []
    import MySQLdb
    import MySQLdb.cursors
    connection = MySQLdb.connect(host='localhost', user='root', db='everysunset', cursorclass=MySQLdb.cursors.DictCursor)
    cursor = connection.cursor()
    cursor.execute("SELECT * FROM webcams")
    results = cursor.fetchall()

    for result in results:
        print "Title: %s" % result["title"]
        print "URL: %s" % result["url"]
        timezone = tz.tzNameAt(float(result["lat"]), float(result["lon"]))
        if timezone != None:
            sunrise, sunset = lookupCoordinates(float(result["lat"]), float(result["lon"]))
            returncode = getHttpStatus(result["url"])
            if returncode == 200:
                updata.append((result['id'], sunrise, sunset, "200", "OK"))
            else:
                updata.append((result['id'], sunrise, sunset, str(returncode), "HTTP ERROR"))
        else:
            print "!! INVALID TIMEZONE !!"
            updata.append((result['id'], None, None, None, "invalid timezone"))
            errordata.append(result)
        print "------------------------"

    for data in updata:
        sql = "REPLACE INTO status (webcam_id, sunrise, sunset, http_status, comment) values (%s, %s, %s, %s, %s)"
        cursor.execute(sql, data)
    connection.commit()

if __name__ == "__main__":
    if len(sys.argv) < 2: 
        interactiveMode()
    elif sys.argv[1] == '--db':
        dbMode()
