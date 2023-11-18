import AGNSCFCFG
from dateutil.relativedelta import *
import datetime
import psycopg2
import psycopg2.extras
from sklearn import svm

def Forecast(baspath,conn):

    cur = conn.cursor(cursor_factory = psycopg2.extras.RealDictCursor)
    cur.execute("SELECT acndlk.dws, csvdfc.xxx as xxxdfc FROM acndlk " +
                "INNER JOIN csvdfc ON csvdfc.rfr=acndlk.dws " +
                "INNER JOIN csvdgm ON csvdgm.rfr=acndlk.dws " +
                "WHERE csvdfc.sta IS NULL " +
                "AND csvdgm.stt IS TRUE " +
                "AND csvdfc.ste IS TRUE " +
                "AND csvdfc.stn IS TRUE " +
                "ORDER BY dws " +
                "LIMIT 1;")
    res = cur.fetchone()
    if res is not None:
        print("aiv")
        mmmdws = res['xxxdfc']
        dws = res['dws']
        cur.execute("UPDATE csvdfc SET sta=FALSE WHERE rfr='" + dws + "';")
        conn.commit()
        sqlcdm='DELETE FROM csvmfc WHERE mmm=' + str(mmmdws) + ' AND fsr=\'A\';' + \
               'DELETE FROM csvmfc WHERE mmm=' + str(mmmdws) + ' AND fsr=\'B\';' + \
               'DELETE FROM csvmfc WHERE mmm=' + str(mmmdws) + ' AND fsr=\'C\';'
        cur.execute(sqlcdm)
        print("mstep")
        sqlcdmdws=""
        sqlcdmens=""
        ftmyear=AGNSCFCFG.GetFSTyear()
        ftmmonth=AGNSCFCFG.GetFSTmonth()
        for mstep in range(-43,1,1):
            ftm = datetime.date(ftmyear, ftmmonth, 1) + relativedelta(months=mstep)
            itm = datetime.date(ftmyear, ftmmonth, 1) + relativedelta(months=mstep-360)
            stat00sql='SELECT (EXTRACT(Year From csvmgm.tms)*10 + DIV(EXTRACT(Month From csvmgm.tms)::numeric+2,3)) as tri, csvmgm.tms as tms,' \
                    'csvmgm.tmd - csvrgm.tmd as tmd, ' \
                    'csvmgm.tmx - csvrgm.tmx as tmx, ' \
                    'csvmgm.tmn - csvrgm.tmn as tmn, ' \
                    'csvmgm.prc - csvrgm.prc as prc ' \
                    'FROM csvdgm ' \
                    'INNER JOIN csvmgm ON csvdgm.xxx=csvmgm.mmm ' \
                    'INNER JOIN csvrgm ON csvdgm.xxx=csvrgm.mmm AND EXTRACT(Month From csvmgm.tms)=EXTRACT(Month From csvrgm.tms) ' \
                    'WHERE csvdgm.rfr=\'' + dws + '\' AND csvmgm.tms<$$' + ftm.strftime('%Y-%m-%d') +'$$ ' \
                    'AND csvmgm.tms>=$$' + itm.strftime('%Y-%m-%d') +'$$ ' \
                    'ORDER BY tms;'
            print(stat00sql)
            #input("...")
            cur.execute(stat00sql)
            stat00res = cur.fetchall()

            srefs=[
                'LTN90000LNO180000',
                'LTN90000LNO135000',
                'LTN90000LNO090000',
                'LTN90000LNO045000',
                'LTN90000LNE000000',
                'LTN90000LNE045000',
                'LTN90000LNE090000',
                'LTN90000LNE135000',
                'LTN45000LNO180000',
                'LTN45000LNO135000',
                'LTN45000LNO090000',
                'LTN45000LNO045000',
                'LTN45000LNE000000',
                'LTN45000LNE045000',
                'LTN45000LNE090000',
                'LTN45000LNE135000',
                'LTN00000LNO180000',
                'LTN00000LNO135000',
                'LTN00000LNO090000',
                'LTN00000LNO045000',
                'LTN00000LNE000000',
                'LTN00000LNE045000',
                'LTN00000LNE090000',
                'LTN00000LNE135000',
                'LTS45000LNO180000',
                'LTS45000LNO135000',
                'LTS45000LNO090000',
                'LTS45000LNO045000',
                'LTS45000LNE000000',
                'LTS45000LNE045000',
                'LTS45000LNE090000',
                'LTS45000LNE135000',
                'LTS90000LNO180000',
                'LTS90000LNO135000',
                'LTS90000LNO090000',
                'LTS90000LNO045000',
                'LTS90000LNE000000',
                'LTS90000LNE045000',
                'LTS90000LNE090000',
                'LTS90000LNE135000'
                   ]

            srefs=[
                'LTN45000LNO180000',
                'LTN45000LNO135000',
                'LTN45000LNO090000',
                'LTN45000LNO045000',
                'LTN45000LNE000000',
                'LTN45000LNE045000',
                'LTN45000LNE090000',
                'LTN45000LNE135000',
                'LTS45000LNO180000',
                'LTS45000LNO135000',
                'LTS45000LNO090000',
                'LTS45000LNO045000',
                'LTS45000LNE000000',
                'LTS45000LNE045000',
                'LTS45000LNE090000',
                'LTS45000LNE135000'
            ]

            srix=36
            mrix=348
            statres=[]
            for irf in range(0,len(srefs),1):
                statressql='SELECT (EXTRACT(Year From csvmgg.tms)*10 + DIV(EXTRACT(Month From csvmgg.tms)::numeric+2,3)) as tri, csvmgg.tms as tms,' \
                          'csvmgg.tmd - csvrgg.tmd as tmd, ' \
                          'csvmgg.tmx - csvrgg.tmx as tmx, ' \
                          'csvmgg.tmn - csvrgg.tmn as tmn, ' \
                          'csvmgg.prc - csvrgg.prc as prc ' \
                          'FROM csvdgg ' \
                          'INNER JOIN csvmgg ON csvdgg.xxx=csvmgg.mmm ' \
                          'INNER JOIN csvrgg ON csvdgg.xxx=csvrgg.mmm ' \
                          'AND EXTRACT(Month From csvmgg.tms)=EXTRACT(Month From csvrgg.tms) ' \
                          'WHERE csvdgg.rfr=\''+ srefs[irf] + '\' AND csvmgg.tms<$$' \
                          + ftm.strftime('%Y-%m-%d') +'$$ AND csvmgg.tms>=$$' \
                          + itm.strftime('%Y-%m-%d') +'$$ ORDER BY tms;'
                print(statressql)
                #input("...")
                cur.execute(statressql)
                statrescur = cur.fetchall()
                statres.append(statrescur)

            for fstep in range(0,6,1):
                statrw={
                    "tmd":0,
                    "tmx":0,
                    "tmn":0,
                    "prc":0,
                }
                statrw['ftm']=ftm
                statrw['tms']=ftm + relativedelta(months=fstep+1) - relativedelta(days=1)
                curM=1
                tmdX=[]
                tmdY=[]
                tmdcurY=[]
                tmdcurX=[]
                for ix in range(srix,mrix):
                    rix=ix
                    tix=rix+fstep
                    #print("learntarget",str(ix) ,str(stat00res[tix]['tms']), str(stat00res[tix]['tmd']))
                    tmdcurY.append(stat00res[tix]['tmd'])
                    for lix in range(rix-srix,rix,1):
                        #print("learnbase",str(lix),str(stat00res[lix]['tms']), str(stat00res[lix]['tmd']))
                        tmdcurX.append(stat00res[lix]['prc'])
                        for irf in range(0,len(srefs),1):
                            tmdcurX.append(statres[irf][lix]['prc'])
                    tmdX.append(tmdcurX)
                    tmdcurX=[]
                #tmdreg = mlmodel.GradientBoostingRegressor()
                tmdreg = svm.SVR()
                tmdreg.fit(tmdX, tmdcurY)

                tmdX=[]
                tmdcurX=[]
                for ix in range(359,360):
                    rix=ix
                    tix=rix+fstep
                    #print("forecasttarget",str(fstep),str(statrw['tms']))
                    for lix in range(rix-srix,rix,1):
                        #print("forecastbase",str(lix),str(stat00res[lix]['tms']), str(stat00res[lix]['tmd']))
                        tmdcurX.append(stat00res[lix]['prc'])
                        for irf in range(0,len(srefs),1):
                            tmdcurX.append(statres[irf][lix]['prc'])
                    tmdX.append(tmdcurX)
                tmdp=tmdreg.predict(tmdX)[0]
                statrw['tmd']=tmdp

                tmxX=[]
                tmxY=[]
                tmxcurY=[]
                tmxcurX=[]
                for ix in range(srix,mrix):
                    rix=ix
                    tix=rix+fstep
                    #print("learntarget",str(ix) ,str(stat00res[tix]['tms']), str(stat00res[tix]['tmx']))
                    tmxcurY.append(stat00res[tix]['tmx'])
                    for lix in range(rix-srix,rix,1):
                        #print("learnbase",str(lix),str(stat00res[lix]['tms']), str(stat00res[lix]['tmx']))
                        tmxcurX.append(stat00res[lix]['tmn'])
                        for irf in range(0,len(srefs),1):
                            tmxcurX.append(statres[irf][lix]['tmn'])
                        tmxcurX.append(stat00res[lix]['tmx'])
                        for irf in range(0,len(srefs),1):
                            tmxcurX.append(statres[irf][lix]['tmx'])
                    tmxX.append(tmxcurX)
                    tmxcurX=[]
                #tmxreg = mlmodel.GradientBoostingRegressor()
                tmxreg = svm.SVR()
                tmxreg.fit(tmxX, tmxcurY)
                tmxX=[]
                tmxcurX=[]
                for ix in range(359,360):
                    rix=ix
                    tix=rix+fstep
                    #print("forecasttarget",str(fstep),str(statrw['tms']))
                    for lix in range(rix-srix,rix,1):
                        #print("forecastbase",str(lix),str(stat00res[lix]['tms']), str(stat00res[lix]['tmx']))
                        tmxcurX.append(stat00res[lix]['tmn'])
                        for irf in range(0,len(srefs),1):
                            tmxcurX.append(statres[irf][lix]['tmn'])
                        tmxcurX.append(stat00res[lix]['tmx'])
                        for irf in range(0,len(srefs),1):
                            tmxcurX.append(statres[irf][lix]['tmx'])
                    tmxX.append(tmxcurX)
                tmxp=tmxreg.predict(tmxX)[0]
                statrw['tmx']=tmxp

                curM=1
                tmnX=[]
                tmnY=[]
                tmncurY=[]
                tmncurX=[]
                for ix in range(srix,mrix):
                    rix=ix
                    tix=rix+fstep
                    #print("learntarget",str(ix) ,str(stat00res[tix]['tms']), str(stat00res[tix]['tmn']))
                    tmncurY.append(stat00res[tix]['tmn'])
                    for lix in range(rix-srix,rix,1):
                        #print("learnbase",str(lix),str(stat00res[lix]['tms']), str(stat00res[lix]['tmn']))
                        tmncurX.append(stat00res[lix]['prc'])
                        for irf in range(0,len(srefs),1):
                            tmncurX.append(statres[irf][lix]['prc'])
                    tmnX.append(tmncurX)
                    tmncurX=[]
                #tmnreg = mlmodel.GradientBoostingRegressor()
                tmnreg = svm.SVR()
                tmnreg.fit(tmnX, tmncurY)
                tmnX=[]
                tmncurX=[]
                for ix in range(359,360):
                    rix=ix
                    tix=rix+fstep
                    #print("forecasttarget",str(fstep),str(statrw['tms']))
                    for lix in range(rix-srix,rix,1):
                        #print("forecastbase",str(lix),str(stat00res[lix]['tms']), str(stat00res[lix]['tmn']))
                        tmncurX.append(stat00res[lix]['prc'])
                        for irf in range(0,len(srefs),1):
                            tmncurX.append(statres[irf][lix]['prc'])
                    tmnX.append(tmncurX)
                tmnp=tmnreg.predict(tmnX)[0]
                statrw['tmn']=tmnp

                curM=1
                prcX=[]
                prcY=[]
                prccurY=[]
                prccurX=[]
                for ix in range(srix,mrix):
                    rix=ix
                    tix=rix+fstep
                    #print("learntarget",str(ix) ,str(stat00res[tix]['tms']), str(stat00res[tix]['prc']))
                    prccurY.append(stat00res[tix]['prc'])
                    for lix in range(rix-srix,rix,1):
                        #print("learnbase",str(lix),str(stat00res[lix]['tms']), str(stat00res[lix]['prc']))
                        prccurX.append(stat00res[lix]['tmd'])
                        for irf in range(0,len(srefs),1):
                            prccurX.append(statres[irf][lix]['tmd'])
                    prcX.append(prccurX)
                    prccurX=[]
                #prcreg = mlmodel.GradientBoostingRegressor()
                prcreg = svm.SVR()
                prcreg.fit(prcX, prccurY)

                prcX=[]
                prccurX=[]
                for ix in range(359,360):
                    rix=ix
                    tix=rix+fstep
                    #print("forecasttarget",str(fstep),str(statrw['tms']))
                    for lix in range(rix-srix,rix,1):
                        #print("forecastbase",str(lix),str(stat00res[lix]['tms']), str(stat00res[lix]['prc']))
                        prccurX.append(stat00res[lix]['tmd'])
                        for irf in range(0,len(srefs),1):
                            prccurX.append(statres[irf][lix]['tmd'])
                prcX.append(prccurX)
                prcp=prcreg.predict(prcX)[0]
                statrw['prc']=prcp

                if (sqlcdmdws==""):
                    sqlcdmdws = 'INSERT INTO csvmfc (mmm,tmd, tmx, tmn, prc, tms,ftm,fsr) ' + \
                             'VALUES (' + str(mmmdws) + ',' + \
                             str(statrw['tmd']) + ',' + str(statrw['tmx']) + ',' + str(statrw['tmn']) + ',' + \
                             str(statrw['prc']) + \
                             ',\'' + str(statrw['tms']) + '\',\'' + str(statrw['ftm']) + \
                             '\',\'A\') '
                else:
                    sqlcdmdws = sqlcdmdws + '\n, (' + str(mmmdws) + ',' + \
                             str(statrw['tmd']) + ',' + str(statrw['tmx']) + ',' + str(statrw['tmn']) + ',' + \
                             str(statrw['prc']) + \
                             ',\'' + str(statrw['tms']) + '\',\'' + str(statrw['ftm']) + \
                             '\',\'A\') '

                enssql='SELECT * FROM csvmfc ' + \
                           'WHERE mmm='+ str(mmmdws) + ' ' + \
                           'AND  tms=\'' + str(statrw['tms']) +'\' ' + \
                           'AND  ftm=\'' + str(statrw['ftm']) +'\' ' + \
                           'AND  fsr=\'N\';'
                #input(enssql)
                cur.execute(enssql)
                ensrow = cur.fetchone()
                if statrw['tms']==statrw['ftm'] + relativedelta(months=1) - relativedelta(days=1):
                        if (sqlcdmens==""):
                            sqlcdmens = 'INSERT INTO csvmfc (mmm,tmd, tmx, tmn, prc, tms,ftm,fsr) ' + \
                                        'VALUES (' + str(mmmdws) + ',' + \
                                        str(ensrow['tmd']) + ',' + \
                                        str(ensrow['tmx']) + ',' + \
                                        str(ensrow['tmn']) + ',' + \
                                        str(ensrow['prc']) + \
                                        ',\'' + str(statrw['tms']) + '\',\'' + str(statrw['ftm']) + \
                                        '\',\'B\') '
                        else:
                            sqlcdmens = sqlcdmens + '\n, (' + str(mmmdws) + ',' + \
                                        str(ensrow['tmd']) + ',' + \
                                        str(ensrow['tmx']) + ',' + \
                                        str(ensrow['tmn']) + ',' + \
                                        str(ensrow['prc']) + \
                                        ',\'' + str(statrw['tms']) + '\',\'' + str(statrw['ftm']) + \
                                        '\',\'B\') '
                else:
                        if (sqlcdmens==""):
                            sqlcdmens = 'INSERT INTO csvmfc (mmm,tmd, tmx, tmn, prc, tms,ftm,fsr) ' + \
                                        'VALUES (' + str(mmmdws) + ',' + \
                                        str((ensrow['tmd'] + statrw['tmd'])/2) + ',' + \
                                        str((ensrow['tmx'] + statrw['tmx'])/2) + ',' + \
                                        str((ensrow['tmn'] + statrw['tmn'])/2) + ',' + \
                                        str((ensrow['prc'] + statrw['prc'])/2) + \
                                        ',\'' + str(statrw['tms']) + '\',\'' + str(statrw['ftm']) + \
                                        '\',\'B\') '
                        else:
                            sqlcdmens = sqlcdmens + '\n, (' + str(mmmdws) + ',' + \
                                        str((ensrow['tmd'] + statrw['tmd'])/2) + ',' + \
                                        str((ensrow['tmx'] + statrw['tmx'])/2) + ',' + \
                                        str((ensrow['tmn'] + statrw['tmn'])/2) + ',' + \
                                        str((ensrow['prc'] + statrw['prc'])/2) + \
                                        ',\'' + str(statrw['tms']) + '\',\'' + str(statrw['ftm']) + \
                                        '\',\'B\') '

                nenssql='SELECT * FROM csvmfc ' + \
                       'WHERE mmm='+ str(mmmdws) + ' ' + \
                       'AND  tms=\'' + str(statrw['tms']) +'\' ' + \
                       'AND  ftm=\'' + str(statrw['ftm']) +'\' ' + \
                       'AND  fsr=\'E\';'
                #input(enssql)
                cur.execute(nenssql)
                nensrow = cur.fetchone()
                if statrw['tms']==statrw['ftm'] + relativedelta(months=1) - relativedelta(days=1):
                    sqlcdmens = sqlcdmens + '\n, (' + str(mmmdws) + ',' + \
                                str((nensrow['tmd'])) + ',' + \
                                str((nensrow['tmx'])) + ',' + \
                                str((nensrow['tmn'])) + ',' + \
                                str((nensrow['prc'])) + \
                                ',\'' + str(statrw['tms']) + '\',\'' + str(statrw['ftm']) + \
                                '\',\'C\') '
                else:
                        sqlcdmens = sqlcdmens + '\n, (' + str(mmmdws) + ',' + \
                                    str((ensrow['tmd'] * 0.25 + nensrow['tmd'] * 0.5 + statrw['tmd'] * 0.25)) + ',' + \
                                    str((ensrow['tmx'] * 0.25 + nensrow['tmx'] * 0.5 + statrw['tmx'] * 0.25)) + ',' + \
                                    str((ensrow['tmn'] * 0.25 + nensrow['tmn'] * 0.5 + statrw['tmn'] * 0.25)) + ',' + \
                                    str((ensrow['prc'] * 0.25 + nensrow['prc'] * 0.5 + statrw['prc'] * 0.25)) + \
                                    ',\'' + str(statrw['tms']) + '\',\'' + str(statrw['ftm']) + \
                                    '\',\'C\') '

        #input("...")
        print(sqlcdmdws)
        sqlcdmdws = sqlcdmdws +';'
        cur.execute(sqlcdmdws)

        print(sqlcdmens)
        sqlcdmens = sqlcdmens +';'
        cur.execute(sqlcdmens)

        cur.execute("UPDATE csvdfc SET sta=TRUE WHERE rfr='" + dws + "';")
        cur.close()

Forecast(AGNSCFCFG.GetBasePath(),AGNSCFCFG.ConnectDatabase())