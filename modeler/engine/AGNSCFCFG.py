import os
import psycopg2

def ConnectDatabase():
    baspath = str(os.path.realpath(__file__))
    baspath = baspath[0:len(baspath)-16]
    service_name = os.getenv('DATABASE_SERVICE_NAME', '').upper().replace('-', '_')
    conn = psycopg2.connect(
        host=os.getenv('{}_SERVICE_HOST'.format(service_name)),
        database= os.getenv('DATABASE_NAME'),
        user=os.getenv('DATABASE_USER'),
        password=os.getenv('DATABASE_PASSWORD'),
        port="5432")
    conn.autocommit = True
    return conn

def GetBasePath():
    baspath = "var/opt/"
    return baspath
