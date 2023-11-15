import AGNSCFCFG
import psycopg2
import psycopg2.extras

def ResetDatabase(conn):

    cur = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    sqlcdm = """
TRUNCATE csvmfc CASCADE; 

TRUNCATE csvmgm CASCADE;

TRUNCATE csvmgg CASCADE;

TRUNCATE csvrgm CASCADE;
      
TRUNCATE csvrgg CASCADE;

UPDATE csvdgm SET stt=NULL;

UPDATE csvdfc SET ste=NULL, stn=NULL, sta=NULL;
      """

    try:
        cur.execute(sqlcdm)
    except psycopg2.Error:
        print(sqlcdm)
        quit()

CreateDatabase(AGNSCFCFG.ConnectDatabase())
ResetDatabase(AGNSCFCFG.ConnectDatabase())