#!/usr/bin/python
# -*- coding: utf-8 -*-

#================================================================
#                          ph.py
#================================================================

#Import des fichiers
import calendar;
import time
import RPi.GPIO as GPIO
import MySQLdb

# Initialisation des variables
DB_SERVER ='localhost'
DB_USER='root'
DB_PWD='aquarium'
DB_BASE='aquarium'
datebuff = calendar.timegm(time.gmtime())
ph = [0,0,0,0,0]
somme = 0
moyenne=0
SPICLK = 11
SPIMISO = 9
SPIMOSI = 10
SPICS = 8
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
GPIO.setup(SPIMOSI, GPIO.OUT)
GPIO.setup(SPIMISO, GPIO.IN)
GPIO.setup(SPICLK, GPIO.OUT)
GPIO.setup(SPICS, GPIO.OUT)

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

def readadc( adcnum, clockpin, mosipin, misopin, cspin ):
	if( (adcnum > 7) or (adcnum < 0)):
		return -1
	GPIO.output( cspin, True )
	GPIO.output( clockpin, False )
	GPIO.output( cspin, False )
	commandout = adcnum
	commandout |= 0x18
	commandout <<=3
	for i in range(5):
		if( commandout & 0x80 ):
			GPIO.output( mosipin, True )
		else:
			GPIO.output( mosipin, False )
		commandout <<= 1
		GPIO.output( clockpin, True )
		GPIO.output( clockpin, False )
	adcout = 0
	for i in range(12):
		GPIO.output( clockpin, True )
		GPIO.output( clockpin, False )
		adcout <<= 1
		if( GPIO.input(misopin)):
			adcout |= 0x1
	GPIO.output( cspin, True )
	adcout >>= 1
	return adcout

#Debut du programme
print ("--- Acquisition pH ---")

#Acquisition de la distance à 5 reprises
for x in range(5):
    try:
        trim_pot1 = readadc( 3, SPICLK, SPIMOSI, SPIMISO, SPICS )
        ph_mesure = ((trim_pot1 * ( 3300.0 / 1024.0))/1000)*3.2531+0.1614
        ph_round = round(ph_mesure,2)
        ph[x] = ph_round
        time.sleep(0.2)
    except KeyboardInterrupt:
        print ("You cancelled the program!")
        GPIO.cleanup()
	
#Récupération des valeurs mini et maxi puis suppression de ces valeurs (valeurs abberrantes)
max0 = max(ph)
min0 = min(ph)
ph.remove(max0)
ph.remove(min0)

#Calcul de la valeur moyenne des 3 valeurs restantes
for y in ph:
 somme = somme + y
 moyenne = somme / 3
moyenne=round(moyenne,2)

#Enregistrement dans la base de données
db = MySQLdb.connect(host=DB_SERVER, user=DB_USER, passwd=DB_PWD, db=DB_BASE, connect_timeout=10)
query_db("INSERT INTO value (id_sensor, timestamp, value) VALUES ('3','%s', '%s')" % (datebuff,moyenne))

#Affichage dans le shell
print ("pH moyen = '%s'" % (moyenne))

#Libération des ports GPIO
GPIO.cleanup()