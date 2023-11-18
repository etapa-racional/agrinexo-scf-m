import cdsapi

c = cdsapi.Client()

c.retrieve(
    'seasonal-postprocessed-single-levels',
    {
        'format': 'grib',
        'originating_centre': 'ecmwf',
        'system': '51',
        'variable': [
            '2m_temperature_anomaly', 'maximum_2m_temperature_in_the_last_24_hours_anomaly',
            'minimum_2m_temperature_in_the_last_24_hours_anomaly',
            'total_precipitation_anomalous_rate_of_accumulation',
        ],
        'product_type': 'ensemble_mean',
        'year': ['2023',],
        'month': [
            '01', '02', '03',
            '04', '05', '06',
            '07', '08', '09',
            '10', '11',
        ],
        'leadtime_month': [
            '1', '2', '3',
            '4', '5', '6',
        ],
    },
    'ECMWF2023.grib')