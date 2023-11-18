import AGNSCFCFG
import xarray as xr
import math
import json
import datetime
import psycopg2
import cfgrib

def historicalclimate(dsg, lat, lon):
    r = (90 - lat)
    if lon > 0:
        c = lon
    else:
        c = (360 + lon)
    fl = []
    for icf in range(0, 2, 1):
        ds = dsg[icf]
        # print(ds)
        timesteps = int(len(ds['time']))
        stepsteps = int(len(ds['step']))
        for timestep in range(0, timesteps, 1):
            for stepstep in range(0, stepsteps, 1):
                dtu = int(ds['t2a'][timestep, stepstep, int(r), int(c)].time) / 1000000000
                curdbdate = datetime.datetime.utcfromtimestamp(dtu)
                curdmdate = datetime.datetime.utcfromtimestamp(dtu) + datetime.timedelta(
                    microseconds=-1 + int(ds['t2a'][timestep, stepstep, int(r), int(c)]['step']) / 1000)
                curdm = {
                    "clat": -9999,
                    "clon": -9999,
                    "orig": curdbdate.strftime("%Y-%m-%d"),
                    "date": curdmdate.strftime("%Y-%m-%d"),
                    "tmed": -9999,
                    "tmin": -9999,
                    "tmax": -9999,
                    "prcp": -9999
                }
                a = ds['t2a'][timestep, stepstep, int(r), int(c)]
                A = float(a)
                A = round(A, 1)
                curdm['tmed'] = A
                a = ds['mn2t24a'][timestep, stepstep, int(r), int(c)]
                A = float(a)
                A = round(A, 1)
                curdm['tmin'] = A
                a = ds['mx2t24a'][timestep, stepstep, int(r), int(c)]
                A = float(a)
                A = round(A, 1)
                curdm['tmax'] = A
                import calendar
                nrmdays = calendar.monthrange(curdmdate.year, curdmdate.month)[1]
                a = ds['tpara'][timestep, stepstep, int(r), int(c)]
                A = float(a)
                A = round(A * 1000 * 3600 * 24 * nrmdays, 1)
                curdm['prcp'] = A
                if not math.isnan(a):
                    curdm['clat'] = float(a.latitude)
                    curdm['clon'] = float(a.longitude)
                    fl.append(curdm)
    return fl


def ImportNCEPForecast(baspath, conn):
    ER4CSVINVFT2 = [{"climaticfile": "svr/forecasts/NCEP.grib"}, {"climaticfile": "svr/forecasts/NCEP2023.grib"}]
    dsg = []
    localpath = baspath + ER4CSVINVFT2[0]["climaticfile"]
    dsg.append(xr.open_dataset(localpath, engine='cfgrib'))
    localpath = baspath + ER4CSVINVFT2[1]["climaticfile"]
    dsg.append(xr.open_dataset(localpath, engine='cfgrib'))
    cur = conn.cursor()
    for iin in range(0, 1, 1):
        asqlcdm = ""
        cur.execute("SELECT xxx, rfr FROM csvdfc WHERE stn IS NULL ORDER BY rfr LIMIT 1;")
        cells = cur.fetchall()
        print("cache csvdfc n")
        for res in cells:
            lck = res[1]
            mmm = res[0]
            cur.execute("UPDATE csvdfc SET stn=FALSE WHERE rfr='" + lck + "';")
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
                    print(lck[3:8], lat, lck[11:17], lon)
                fl = historicalclimate(dsg, lat, lon)
                sqlcdm = 'DELETE FROM csvmfc WHERE mmm=' + str(mmm) + ' AND fsr=\'N\';'
                try:
                    cur.execute(sqlcdm)
                except psycopg2.Error:
                    print(sqlcdm)
                    quit()
                for cr in fl:
                    if (asqlcdm == ""):
                        asqlcdm = asqlcdm + '\n' + \
                                  'INSERT INTO csvmfc (mmm,tmd, tmx, tmn, prc, tms,ftm,fsr) ' + \
                                  'VALUES (' + str(mmm) + ',' + \
                                  str(cr['tmed']) + ',' + str(cr['tmax']) + ',' + str(cr['tmin']) + ',' + \
                                  str(cr['prcp']) + ',\'' + \
                                  str(cr['date']) + '\',\'' + str(cr['orig']) + '\',\'N\')'
                    else:
                        asqlcdm = asqlcdm + '\n' + \
                                  ', (' + str(mmm) + ',' + \
                                  str(cr['tmed']) + ',' + str(cr['tmax']) + ',' + str(cr['tmin']) + ',' + \
                                  str(cr['prcp']) + ',\'' + \
                                  str(cr['date']) + '\',\'' + str(cr['orig']) + '\',\'N\')'
                flj = json.dumps(fl, indent=4)
                print(flj)
                asqlcdm = asqlcdm + ';'
                cur.execute(asqlcdm)
                cur.execute("UPDATE csvdfc SET stn=TRUE WHERE rfr='" + lck + "';")
            except psycopg2.Error:
                print(asqlcdm)
                cur.execute("UPDATE csvdfc SET stn=NULL WHERE rfr='" + lck + "';")
                print("error")
                quit()
    cur.close()

ImportNCEPForecast(AGNSCFCFG.GetBasePath(),AGNSCFCFG.ConnectDatabase())