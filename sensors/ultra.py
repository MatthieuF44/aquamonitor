#!/usr/bin/python
# -*- coding: utf-8 -*-

#================================================================
#                          ultra.py
#================================================================

#Import des fichiers
import calendar;
import RPi.GPIO as GPIO
import time
import MySQLdb

# Initialisation des variables
DB_SERVER ='localhost'
DB_USER='root'
DB_PWD='aquarium'
DB_BASE='aquarium'
trigPin = 11
echoPin = 18
datebuff = calendar.timegm(time.gmtime())
distance = [0,0,0,0,0]
somme=0
moyenne=0
GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)

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

#Debut du programme
print ("--- Acquisition hauteur d'eau ---")

#Réglage des pins de la raspberry
GPIO.setup(trigPin,GPIO.OUT,initial=GPIO.LOW)
GPIO.setup(echoPin,GPIO.IN)
time.sleep(1)
GPIO.output(trigPin, False)

#Acquisition de la distance à 5 reprises
for x in range(5):
    try:
        time.sleep(0.5)
        GPIO.output(trigPin, True)
        time.sleep(0.000001)
        GPIO.output(trigPin, False)

        while GPIO.input(echoPin)==0:
            debutImpulsion = time.time()

        while GPIO.input(echoPin)==1:
            finImpulsion = time.time()

        distance[x] = round((finImpulsion - debutImpulsion) * 340 * 100 / 2, 2)

    except KeyboardInterrupt:
        print ("Le programme a été arrêté !")
        GPIO.cleanup()

#Récupération des valeurs mini et maxi puis suppression de ces valeurs (valeurs abberrantes)
max0 = max(distance)
min0 = min(distance)
distance.remove(max0)
distance.remove(min0)

#Calcul de la valeur moyenne des 3 valeurs restantes
for i in distance:
 somme = somme + i
 moyenne = somme / 3
moyenne=round(moyenne,2)

#Conversion cm en litres 
#(dimensions aquarium : profondeur 33,5cm largeur 77,5cm hauteur 38,4cm)
#(valeur offset 5,4cm)
hauteur_eau=3.84-((moyenne-5.4)/10)
litre=hauteur_eau*7.75*3.35
litre=round(litre,0)

#Enregistrement dans la base de données
db = MySQLdb.connect(host=DB_SERVER, user=DB_USER, passwd=DB_PWD, db=DB_BASE, connect_timeout=5)
query_db("INSERT INTO value (id_sensor, timestamp, value) VALUES ('4','%s', '%s')" % (datebuff,litre))

#Affichage dans le shell
print ("Hauteur d'eau moyenne = '%s' l" % (litre))

#Libération des ports GPIO
GPIO.cleanup()
