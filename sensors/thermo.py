#!/usr/bin/python
# -*- coding: utf-8 -*-
 
#================================================================
#                          thermo.py
#================================================================

#Import des fichiers
import os
import calendar
import time
import MySQLdb

# Initialisation des variables
DB_SERVER ='localhost'
DB_USER='root'
DB_PWD='aquarium'
DB_BASE='aquarium'
sonde1 = "/sys/bus/w1/devices/w1_bus_master1/28-00000659ad60/w1_slave"
sonde2 = "/sys/bus/w1/devices/w1_bus_master1/28-0416b06767ff/w1_slave"
datebuff = calendar.timegm(time.gmtime())
sondes = [sonde1, sonde2]
sonde_value0 = 0
sonde_value1 = 0
debut=time.time()

#Définition des fonctions
def query_db(sql):
    try:
        cursor = db.cursor()
        cursor.execute(sql)
        db.rollback()
    except MySQLdb.DataError as e:
        print("DataError")
        print(e)
    except MySQLdb.InternalError as e:
        print("InternalError")
        print(e)
    except MySQLdb.IntegrityError as e:
        print("IntegrityError")
        print(e)
    except MySQLdb.OperationalError as e:
        print("OperationalError")
        print(e)
    except MySQLdb.NotSupportedError as e:
        print("NotSupportedError")
        print(e)
    except MySQLdb.ProgrammingError as e:
        print("ProgrammingError")
        print(e)
    except :
        print("Unknown error occurred")

def read_file(sonde):
    try:
        f = open(sonde, 'r')
        lines = f.readlines()
        f.close()
        return lines
    except:
        exit

#Debut du programme
print ("--- Acquisition des températures ---")		

#Acquisition de la première sonde
lines0 = read_file(sondes[0])
while lines0[0].strip()[-3:] != 'YES':
    time.sleep(0.2)
    lines0 = read_file(sondes[0])
temp_raw0 = lines0[1].split("=")[1]
sonde_value0 = round(int(temp_raw0) / 1000.0, 1)

#Acquisition de la deuxième sonde
lines1 = read_file(sondes[1])
while lines1[0].strip()[-3:] != 'YES':
    time.sleep(0.2)
    lines1 = read_file(sondes[1])
temp_raw1 = lines1[1].split("=")[1]
sonde_value1 = round(int(temp_raw1) / 1000.0, 1)

#Enregistrement dans la base de données
db = MySQLdb.connect(host=DB_SERVER, user=DB_USER, passwd=DB_PWD, db=DB_BASE, connect_timeout=5)
query_db("INSERT INTO value (id_sensor, timestamp, value) VALUES ('1','%s', '%s'),('2','%s', '%s')" % (datebuff,sonde_value0,datebuff,sonde_value1))
db.close()

#Affichage dans le shell
print ("Température (id=1) = '%s'" % (sonde_value0))  
print ("Température (id=2) = '%s'" % (sonde_value1))

#Calcul temps exécution
#fin=time.time()
#delai=fin-debut
#delai=round(delai,2)
#print ('Temps d\'éxécution : %s sec' % (delai))
