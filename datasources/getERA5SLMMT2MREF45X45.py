import cdsapi
import os

c = cdsapi.Client()

c.retrieve(
    'reanalysis-era5-single-levels-monthly-means',
    {
        'format': 'grib',
        'year': [
            '1959','1960','1961','1962','1963','1964','1965','1966','1967','1968',
            '1969','1970','1971','1972','1973','1974','1975','1976','1977','1978',
            '1979','1980','1981','1982','1983','1984','1985','1986','1987','1988',
            '1989','1990','1991','1992','1993','1994','1995','1996','1997','1998',
            '1999','2000','2001','2002','2003','2004','2005','2006','2007','2008',
            '2009','2010','2011','2012','2013','2014','2015','2016','2017','2018',
            '2019','2020','2021','2022','2023'
        ],
        'month': [
            '01', '02', '03',
            '04', '05', '06',
            '07', '08', '09',
            '10', '11', '12',
        ],
        'time': [
            '00:00', '01:00', '02:00',
            '03:00', '04:00', '05:00',
            '06:00', '07:00', '08:00',
            '09:00', '10:00', '11:00',
            '12:00', '13:00', '14:00',
            '15:00', '16:00', '17:00',
            '18:00', '19:00', '20:00',
            '21:00', '22:00', '23:00',
        ],
        'variable': '2m_temperature',
        'product_type': 'monthly_averaged_reanalysis_by_hour_of_day',
        'grid': '45/45'
    },
    'ERA5SLMMT2MREF45X45.grib')

bashCommand = "grib_to_netcdf ERA5SLMMT2MREF45X45.grib -o ERA5SLMMT2MREF45X45.nc"
os.system(bashCommand)

