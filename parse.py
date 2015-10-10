#!/usr/bin/env python
# -*- coding: utf-8
import MySQLdb, re
connection = MySQLdb.connect(host="localhost", user="everysunset", db="everysunset", passwd="Abendrot23")
cursor = connection.cursor()

f = open("Liste2.txt")
f.readline()
line = f.readline()
data = []
while line:
    if line.find(" ") > 0:
        if line.find(" \"") < 0:
            line = line.replace("\"", " \"", 1)
        print "Line:!!"
        print line
        coordinates, title = line.split(" \"", 1)
        lat, lon = re.split(",\s*", coordinates, 1)
        url = f.readline().strip()
        if url != 'http://www.everysunset.de/nowebcam.jpg' and lat != "" and lon != "":
            title = title.replace("\"", "")
            title = title.strip()
            url = url.strip()
            data.append((float(lat), float(lon), title, url))
        line = f.readline()
    else:
        print "nothing found in line"
        print line
        f.readline()
        line = f.readline()
print data
print len(data)

for dataset in data:
    query = "INSERT INTO webcams (lat, lon, title, url) VALUES (%s, %s, %s, %s)"
    print dataset
    cursor.execute(query, dataset)

connection.commit()
#connection.rollback()
connection.close()
