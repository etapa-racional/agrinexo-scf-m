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

TRUNCATE csvdgg CASCADE;

INSERT INTO csvdgg (rfr) VALUES ('LTN90000LNO180000'),
                ('LTN90000LNO135000'),
                ('LTN90000LNO090000'),
                ('LTN90000LNO045000'),
                ('LTN90000LNE000000'),
                ('LTN90000LNE045000'),
                ('LTN90000LNE090000'),
                ('LTN90000LNE135000'),
                ('LTN45000LNO180000'),
                ('LTN45000LNO135000'),
                ('LTN45000LNO090000'),
                ('LTN45000LNO045000'),
                ('LTN45000LNE000000'),
                ('LTN45000LNE045000'),
                ('LTN45000LNE090000'),
                ('LTN45000LNE135000'),
                ('LTN00000LNO180000'),
                ('LTN00000LNO135000'),
                ('LTN00000LNO090000'),
                ('LTN00000LNO045000'),
                ('LTN00000LNE000000'),
                ('LTN00000LNE045000'),
                ('LTN00000LNE090000'),
                ('LTN00000LNE135000'),
                ('LTS45000LNO180000'),
                ('LTS45000LNO135000'),
                ('LTS45000LNO090000'),
                ('LTS45000LNO045000'),
                ('LTS45000LNE000000'),
                ('LTS45000LNE045000'),
                ('LTS45000LNE090000'),
                ('LTS45000LNE135000'),
                ('LTS90000LNO180000'),
                ('LTS90000LNO135000'),
                ('LTS90000LNO090000'),
                ('LTS90000LNO045000'),
                ('LTS90000LNE000000'),
                ('LTS90000LNE045000'),
                ('LTS90000LNE090000'),
                ('LTS90000LNE135000');
                
UPDATE csvdgm SET stt=NULL;

UPDATE csvdfc SET ste=NULL, stn=NULL, sta=NULL;
      """

    try:
        cur.execute(sqlcdm)
    except psycopg2.Error:
        print(sqlcdm)
        quit()

ResetDatabase(AGNSCFCFG.ConnectDatabase())