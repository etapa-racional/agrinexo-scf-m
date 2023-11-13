import AGNSCFCFG
import datetime
import psycopg2
import netCDF4 as nc
import math
import calendar
import psycopg2.extras

def historicalclimate(dst,dsp, lat, lon):
    r = (90 - lat) / 45
    if lon >= 0:
        c = (lon) / 45
    else:
        c = (360 + lon) / 45
    fl = []


    prevstep=0
    for icf in range(0,1,1):
        ds = dst[icf]
        #print(ds)
        monthdays = int(len(ds['time'])/24)
        for daystep in range(0, monthdays, 1):
            for hourstep in range(0, 24, 1):
                timeidx=daystep*24+hourstep
                try:
                    a = ds['t2m'][timeidx, int(r), int(c)]
                except IndexError:
                    a = ds['t2m_0001'][timeidx, int(r), int(c)]
                    if (math.isnan(a)):
                        a = ds['t2m_0005'][timeidx, int(r), int(c)]
                A = float(a)
                dtu = int(ds['time'][daystep*24+hourstep])
                base = datetime.datetime(1900, 1, 1) + datetime.timedelta(hours=dtu)
                if hourstep == 0:
                    curdmdate = base
                    curdm = {
                        "date": curdmdate.strftime("%Y-%m-%d"),
                        "tmed": -9999,
                        "tmin": -9999,
                        "tmax": -9999,
                        "prcp": -9999,
                        "lat": -9999,
                        "lon": -9999
                    }
                A = round(A - 273.15, 1)
                if A< curdm['tmin'] or curdm['tmin'] == -9999:
                    curdm['tmin'] = A
                if A > curdm['tmax'] or curdm['tmax'] == -9999:
                    curdm['tmax'] = A
                if hourstep == 23:
                    curdm['tmed'] = round((curdm['tmin'] * 0.5 + curdm['tmax'] * 0.5), 1)
                    dayoftheyear = curdmdate.timetuple().tm_yday+15
                    curdm['lat'] = float(ds['latitude'][int(r)])
                    curdm['lon'] = float(ds['longitude'][int(c)])
                    fl.append(curdm)

        ds = dsp[icf]
        #print(ds)
        months = int(len(ds['time'])/24)
        for daystep in range(0, months, 1):
            A = 0
            for hourstep in range(0,24,1):
                try:
                    a = ds['tp'][daystep*24+hourstep, int(r), int(c)]
                except IndexError:
                    a = ds['tp_0001'][daystep*24+hourstep, int(r), int(c)]
                    if (math.isnan(a)):
                        a = ds['tp_0005'][daystep*24+hourstep, int(r), int(c)]
                if not math.isnan(float(a)):
                    A = A + float(a)
                dtu = int(ds['time'][daystep*24+hourstep])
                base = datetime.datetime(1900, 1, 1) + datetime.timedelta(hours=dtu)
            cmdaystep = daystep + prevstep
            curdm = fl[cmdaystep]
            nrmdays =calendar.monthrange(base.year,base.month)[1]
            A = round(nrmdays * A * 1000, 1) #ajustar para dias do mês en vez de 30
            curdm['prcp'] = A
        prevstep= prevstep + months
    return fl

def ImportGlobalGrid(baspath,conn):

    ER4CSVINVFT2= [{"climaticfile": "svr/grib_directory/ERA5SLMMT2MREF45X45.nc"}]
    ER4CSVINVFPR = [{"climaticfile": "svr/grib_directory/ERA5SLMMPRCREF45X45.nc"}]
    dst=[]
    localpath = baspath + ER4CSVINVFT2[0]["climaticfile"]
    dst.append(nc.Dataset(localpath))
    dsp=[]
    localpath = baspath + ER4CSVINVFPR[0]["climaticfile"]
    dsp.append(nc.Dataset(localpath))
    cur = conn.cursor(cursor_factory = psycopg2.extras.RealDictCursor)
    for iin in range(0,41,1):
        cur.execute("SELECT xxx, rfr FROM csvdgg WHERE stt IS NULL LIMIT 1;")
        res = cur.fetchone()
        #print("cache csvdgg")
        if res is not None:
            lck = res['rfr']
            mmm = res['xxx']
            print(lck[3:8],lck[11:17])
            cur.execute("UPDATE csvdgg SET stt=FALSE WHERE rfr='" + lck + "';")
            try:
                if lck[8:10] == 'LN' and 0 <= int(lck[11:17]) <= 180000:
                    if lck[2] == 'N':
                        lat = float(lck[3:8]) / 1000
                    if lck[2] == 'S':
                        lat = -float(lck[3:8]) / 1000
                    if lck[10] == 'E':
                        lon = float(lck[11:17]) / 1000
                    if lck[10] == 'O':
                        lon = -float(lck[11:17]) / 1000
                fl = historicalclimate(dst,dsp, lat, lon)

                sqlcdm='DELETE FROM csvmgg WHERE mmm=' + str(mmm) + ';'
                try:
                    cur.execute(sqlcdm)
                except psycopg2.Error:
                    print(sqlcdm)
                    quit()
                sqlcdm='DELETE FROM csvrgg WHERE mmm=' + str(mmm) + ';'
                try:
                    cur.execute(sqlcdm)
                except psycopg2.Error:
                    print(sqlcdm)
                    quit()

                sqlcdm=""
                for cr in fl:
                    if (sqlcdm==""):
                        sqlcdm = 'INSERT INTO csvmgg (mmm,tmd, tmx, tmn, prc, tms) ' + \
                                 'VALUES (' + str(mmm) + ',' + \
                                 str(cr['tmed']) + ',' + str(cr['tmax']) + ',' + str(cr['tmin']) + ',' + \
                                 str(cr['prcp']) + \
                                ',\'' + str(cr['date']) + '\') '
                    else:
                        sqlcdm = sqlcdm + '\n, (' + str(mmm) + ',' + \
                                 str(cr['tmed']) + ',' + str(cr['tmax']) + ',' + str(cr['tmin']) + ',' + \
                                 str(cr['prcp']) + \
                                 ',\'' + str(cr['date']) + '\') '
                try:
                    sqlcdm = sqlcdm +';'
                    cur.execute(sqlcdm)
                except psycopg2.Error:
                    print(sqlcdm)
                    quit()
                #flj = json.dumps(fl, indent=4)
                #print(flj)

                statsql='SELECT csvdgg.xxx as mmm, EXTRACT(Month From csvmgg.tms), AVG(csvmgg.tmd) as tmd,' \
                        ' AVG(csvmgg.tmx) AS tmx, AVG(csvmgg.tmn) AS tmn, ' \
                        'AVG(csvmgg.prc) AS prc, ' \
                        'MIN(csvmgg.tms) as tms ' \
                        'FROM csvdgg ' \
                        'INNER JOIN csvmgg ON csvdgg.xxx=csvmgg.mmm ' \
                        'WHERE EXTRACT(Year From csvmgg.tms)>=1993 AND EXTRACT(Year From csvmgg.tms)<=2016  AND ' \
                        'csvdgg.xxx=' + str(mmm) + ' ' \
                        'GROUP BY csvdgg.xxx, EXTRACT(Month From csvmgg.tms)'

                #print(statsql)
                cur.execute(statsql)

                statres = cur.fetchall()
                sqlcdm=""
                for statrw in statres:
                    if (sqlcdm==""):
                        sqlcdm = 'INSERT INTO csvrgg (mmm,tmd, tmx, tmn, prc, tms) ' + \
                                 'VALUES (' + str(mmm) + ',' + \
                                 str(statrw['tmd']) + ',' + str(statrw['tmx']) + ',' + str(statrw['tmn']) + ',' + \
                                 str(statrw['prc']) + \
                                 ',\'' + str(statrw['tms']) + '\') '
                    else:
                        sqlcdm = sqlcdm + '\n, (' + str(mmm) + ',' + \
                                 str(statrw['tmd']) + ',' + str(statrw['tmx']) + ',' + str(statrw['tmn']) + ',' + \
                                 str(statrw['prc']) + \
                                 ',\'' + str(statrw['tms']) + '\') '
                try:
                    sqlcdm = sqlcdm +';'
                    cur.execute(sqlcdm)
                except psycopg2.Error:
                    print(sqlcdm)
                    quit()

                cur.execute("UPDATE csvdgg SET stt=TRUE, lat="+
                            str(cr['lat']) +", lon="+ str(cr['lon']) +
                            " WHERE rfr='" + lck + "';")
            except psycopg2.Error:
                print("UPDATE csvdgg SET stt=NULL WHERE rfr='" + lck + "';")

ImportGlobalGrid(AGNSCFCFG.GetBasePath(),AGNSCFCFG.ConnectDatabase())