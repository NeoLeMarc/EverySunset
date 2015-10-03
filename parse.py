#!/usr/bin/env python
# -*- coding: utf-8
f = open("/tmp/webcams.txt")
f.readline()
line = f.readline()
data = []
while line:
    if line.find(" ") > 0:
        if line.find(" \"") < 0:
            line = line.replace("\"", " \"", 1)
        coordinates, title = line.split(" ", 1)
        lat, lon = coordinates.split(",")
        url = f.readline().strip()
        if url != 'http://www.everysunset.de/nowebcam.jpg':
            data.append((lat, lon, title, url))
        line = f.readline()
    else:
        f.readline()
        f.readline()
        line = f.readline()
print data
print len(data)
