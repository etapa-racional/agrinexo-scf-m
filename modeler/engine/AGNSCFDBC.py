import AGNSCFCFG
import psycopg2
import psycopg2.extras

def CreateDatabase(conn):

    cur = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
    sqlcdm = """

CREATE TABLE IF NOT EXISTS acndlk
(
    xxx serial NOT NULL,
    rfr character varying(20) NOT NULL,
    dsc character varying(50),
    minx double precision,
    miny double precision,
    dws character varying(20),
    arf double precision,
    CONSTRAINT acndlk_pkey PRIMARY KEY (xxx)
);

CREATE TABLE IF NOT EXISTS csvdfc (
  xxx serial NOT NULL,
  rfr varchar(20) NOT NULL,
  lat float DEFAULT NULL,
  lon float DEFAULT NULL,
  ste boolean DEFAULT NULL,
  stn boolean DEFAULT NULL,
  sta boolean DEFAULT NULL,
  CONSTRAINT csvdfc_pkey PRIMARY KEY (xxx),
  CONSTRAINT csvdfc_rfr UNIQUE (rfr) 
);

CREATE TABLE IF NOT EXISTS csvdgg (
  xxx serial NOT NULL,
  rfr varchar(20) NOT NULL,
  lat float DEFAULT NULL,
  lon float DEFAULT NULL,
  stt boolean DEFAULT NULL,
  CONSTRAINT csvdgg_pkey PRIMARY KEY (xxx),
  CONSTRAINT csvdgg_rfr UNIQUE (rfr)
);

CREATE TABLE IF NOT EXISTS csvdgm (
  xxx serial NOT NULL,
  rfr varchar(20) NOT NULL,
  lat float DEFAULT NULL,
  lon float DEFAULT NULL,
  stt boolean DEFAULT NULL,
  CONSTRAINT csvdgm_pkey PRIMARY KEY (xxx),
  CONSTRAINT csvdgm_rfr UNIQUE (rfr)
);

CREATE TABLE IF NOT EXISTS csvmfc (
  xxx serial NOT NULL,
  mmm int NOT NULL,
  tmd double precision NOT NULL,
  tmx double precision NOT NULL,
  tmn double precision NOT NULL,
  prc double precision NOT NULL,
  tms timestamp without time zone NOT NULL,
  ftm timestamp without time zone NOT NULL,
  fsr varchar(1) NOT NULL,
  CONSTRAINT csvmfc_pkey PRIMARY KEY (xxx)
);
CREATE INDEX IF NOT EXISTS csvmfc_mmm ON csvmfc USING btree (mmm ASC NULLS LAST);
ALTER TABLE IF EXISTS csvmfc
    ADD CONSTRAINT csvmfc_mmmtmsftmfsr UNIQUE (mmm, tms, ftm, fsr);
CREATE UNIQUE INDEX IF NOT EXISTS csvmfc_mmmtmsftmfsr_idx
    ON csvmfc USING btree
    (mmm ASC NULLS LAST, tms ASC NULLS LAST, ftm ASC NULLS LAST, fsr ASC NULLS LAST);

CREATE TABLE IF NOT EXISTS csvmgm (
  xxx serial NOT NULL,
  mmm int NOT NULL,
  tmd double precision NOT NULL,
  tmx double precision NOT NULL,
  tmn double precision NOT NULL,
  prc double precision NOT NULL,
  tms timestamp without time zone NOT NULL,
  CONSTRAINT csvmgm_pkey PRIMARY KEY (xxx)
);
ALTER TABLE IF EXISTS csvmgm
    ADD CONSTRAINT csvmgm_mmmtms UNIQUE (mmm, tms);
CREATE INDEX IF NOT EXISTS csvmgm_mmm ON csvmgm USING btree (mmm ASC NULLS LAST);


CREATE TABLE IF NOT EXISTS csvmgg (
  xxx serial NOT NULL,
  mmm int NOT NULL,
  tmd double precision NOT NULL,
  tmx double precision NOT NULL,
  tmn double precision NOT NULL,
  prc double precision NOT NULL,
  tms timestamp without time zone NOT NULL,
  CONSTRAINT csvmgg_pkey PRIMARY KEY (xxx)
);
CREATE INDEX IF NOT EXISTS csvmgg_mmm ON csvmgg USING btree (mmm ASC NULLS LAST);

CREATE TABLE IF NOT EXISTS csvrgm (
  xxx serial NOT NULL,
  mmm int NOT NULL,
  tmd double precision NOT NULL,
  tmx double precision NOT NULL,
  tmn double precision NOT NULL,
  prc double precision NOT NULL,
  tms timestamp without time zone NOT NULL,
  CONSTRAINT csvrgm_pkey PRIMARY KEY (xxx)
);
CREATE INDEX IF NOT EXISTS csvrgm_mmm ON csvrgm USING btree (mmm ASC NULLS LAST);
      
CREATE TABLE IF NOT EXISTS csvrgg (
  xxx serial NOT NULL,
  mmm int NOT NULL,
  tmd double precision NOT NULL,
  tmx double precision NOT NULL,
  tmn double precision NOT NULL,
  prc double precision NOT NULL,
  tms timestamp without time zone NOT NULL,
  CONSTRAINT csvrgg_pkey PRIMARY KEY (xxx)
);
CREATE INDEX IF NOT EXISTS csvrgg_mmm ON csvrgg USING btree (mmm ASC NULLS LAST);

      """

    try:
        cur.execute(sqlcdm)
    except psycopg2.Error:
        print(sqlcdm)
        quit()

CreateDatabase(AGNSCFCFG.ConnectDatabase())
