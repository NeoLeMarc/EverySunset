#!/usr/bin/env python
# -*- coding: utf-8
import MySQLdb
connection = MySQLdb.connect(host="localhost", user="root", db="everysunset")
cursor = connection.cursor()

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
        if url != 'http://www.everysunset.de/nowebcam.jpg' and lat != "" and lon != "":
            title = title.replace("\"", "")
            title = title.strip()
            url = url.strip()
            data.append((float(lat), float(lon), title, url))
        line = f.readline()
    else:
        f.readline()
        f.readline()
        line = f.readline()
print data
print len(data)

for dataset in data:
    query = "INSERT INTO webcams (lat, lon, title, url) VALUES (%s, %s, %s, %s)"
    print dataset
    cursor.execute(query, dataset)

connection.commit()
connection.close()
