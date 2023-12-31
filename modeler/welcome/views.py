import os
from django.shortcuts import render
from django.conf import settings
from django.http import HttpResponse
from django.shortcuts import redirect

from . import database
from .models import PageView
import psycopg2
import psycopg2.extras
import datetime
import time

# Create your views here.

def index(request):

    """Takes an request object as a parameter and creates an pageview object then responds by rendering the index view."""
    hostname = os.getenv('HOSTNAME', 'unknown')
    PageView.objects.create(hostname=hostname)
    
    service_name = os.getenv('DATABASE_SERVICE_NAME', '').upper().replace('-', '_')
    conn = psycopg2.connect(
        host=os.getenv('{}_SERVICE_HOST'.format(service_name)),
        database= os.getenv('DATABASE_NAME'),
        user=os.getenv('DATABASE_USER'),
        password=os.getenv('DATABASE_PASSWORD'),
        port="5432")
    conn.autocommit = True
    cur = conn.cursor(cursor_factory = psycopg2.extras.RealDictCursor)
    cur.execute("CREATE TABLE IF NOT EXISTS sampletb (xxx VARCHAR(50));")
    cur.execute("INSERT INTO sampletb (xxx) VALUES ('"+datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")+"');")  
    
    return render(request, 'welcome/index.html', {
        'hostname': hostname,
        'database': database.info(),
        'count': PageView.objects.count()
    })

def health(request):
    """Takes an request as a parameter and gives the count of pageview objects as reponse"""
    return HttpResponse(PageView.objects.count())

import subprocess
def runCommandUPD(request):
    subprocess.Popen(["python3 /opt/app-root/src/agrinexo-scf-update.py &"], shell=True, stdin=None, stdout=None, stderr=None, close_fds=True)
    txt = "OK"
    return HttpResponse(txt)
