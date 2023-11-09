import os
from django.shortcuts import render
from django.conf import settings
from django.http import HttpResponse

from . import database
from .models import PageView

# Create your views here.

def index(request):
    """Takes an request object as a parameter and creates an pageview object then responds by rendering the index view."""
    hostname = os.getenv('HOSTNAME', 'unknown')
    PageView.objects.create(hostname=hostname)

    return render(request, 'welcome/index.html', {
        'hostname': hostname,
        'database': database.info(),
        'count': PageView.objects.count()
    })

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
    cur.execute("INSERT INTO sampletb (xxx) VALUES ('"+datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")+"');")

def health(request):
    """Takes an request as a parameter and gives the count of pageview objects as reponse"""
    return HttpResponse(PageView.objects.count())
