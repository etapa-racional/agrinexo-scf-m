"""
WSGI config for project project.

It exposes the WSGI callable as a module-level variable named ``application``.

For more information on this file, see
https://docs.djangoproject.com/en/1.11/howto/deployment/wsgi/
"""

import os

from django.core.wsgi import get_wsgi_application

os.environ.setdefault("DJANGO_SETTINGS_MODULE", "project.settings")

application = get_wsgi_application()

import psycopg2
import psycopg2.extras
import datetime
import time

conn = psycopg2.connect(
    host="postgresql.etapa-racional-dev.svc.cluster.local",
    database="sampledb",
    user="userPV3",
    password="6kyWtNgL76ekv0kF",
    port="5432")
conn.autocommit = True

cur = conn.cursor(cursor_factory = psycopg2.extras.RealDictCursor)
while True:
    cur.execute("INSERT INTO sampletb (xxx) VALUES ('"+datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")+"');")
    time.sleep(30)
print(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))