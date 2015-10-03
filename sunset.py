#!/usr/bin/env python
# http://lexikon.astronomie.info/zeitgleichung/
import math
from math import *
arccos = math.acos
lon = math.radians(13.5)
lat = math.radians(52.5)
sunriseAngle = math.radians(-50/60)
day = 30
dekl= 0.4095 * math.sin(0.016906*(day - 80.086))

print "latPolar: %f - deklination: %f - sunriseAngle: %f" % (lat, dekl, sunriseAngle)

timeDifference = 12 * arccos((sin(sunriseAngle) - sin(lat)*sin(dekl)) / (cos(lat)*cos(dekl)))/math.pi
sunrise = 12 - timeDifference
sunset  = 12 + timeDifference

print "timeDifference: %f" % timeDifference
print "Sunrise: %f" % sunrise
print "Sunset:  %f" % sunset 
