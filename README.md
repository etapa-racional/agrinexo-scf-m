# AGRINEXO SCF - Seasonal Climate Forecast Modeler

## Inspiration

Seasonal climate forecasts with adequate resolution have the potential to become a key tool for farm planning, allowing for the adjustment of the cultivated areas and for the optimization of farming operations (planning of sowing, selection of optimal crop variety, planning of fertilisation and field interventions, pest and disease risk assessment, and irrigation management).

Agricultural production losses resulting from drought and heatwaves are largely driven by yield declines, with no significant changes in harvested area [1]. Although those crop losses are not avoidable under most circumstances, improved seasonal climate forecasts would allow decision makers to adjust cultivated areas, irrigation, and fertilisation, to the expected productivity in adverse conditions.

## What it does

AGRINEXO SCF is a seasonal climate forecast modeler, estimating key climatic parameters for the next six months and enabling for the comparison and combination of seasonal forecasts based on diverse climatic models.

AGRINEXO SCF estimates and evaluates the forecast skill of monthly precipitation, monthly average maximum temperature, monthly average mean temperature, and monthly average minimum temperature. Ensemble C, which comprises 58.33% of ECMWF forecast, 20.83% of NCEP forecast and 20.83% of AGRINEXO forecast, provides an improved forecast skill.

If you just want a seasonal climate forecast for a specific area of interest or curious about its forecast skill, you can check Ensemble C forecasts at [agrinexo.com](https://agrinexo.com/scf/analyser/). The current pre-release version of AGRINEXO SCF is being tested over a sample of geographic locations comprising about a thousand locations (one degree grid cells). If your specific area of interest is not included in our sample, just add it using the add marker tool on top left of the map and it should be ready in a couple of minutes.

If you are developing climate related forecast models, you can fork AGRINEXO SCF [source code](https://github.com/etapa-racional/agrinexo-scf-m/) and deploy it on [Red Hat OpenShift](https://www.redhat.com/en/technologies/cloud-computing/openshift) using the template included in the repository and downloading climate data and forecasts from [Copernicus Climate Data Store](https://cds.climate.copernicus.eu/).

## How we built it

Seasonal forecasts based on [ECMWF](https://www.ecmwf.int/) and [NCEP](https://www.weather.gov/ncep/) models are derived from [Seasonal forecast anomalies on single levels](https://cds.climate.copernicus.eu/cdsapp#!/dataset/seasonal-postprocessed-single-levels?tab=overview) and climatic data is derived from [ERA5 monthly averaged data on single levels from 1959 to present](https://cds.climate.copernicus.eu/cdsapp#!/dataset/reanalysis-era5-single-levels-monthly-means), both available from the [Copernicus Climate Change Service]( https://climate.copernicus.eu/) as open data.

![AGRINEXO SCF Data Sources and Containers](https://agrinexo.com/en/rwst/images/I131-AGRINEXO-SCF-DIAGRAM.PNG)

Seasonal forecasting methods can be broadly categorized into dynamical (based on physical principles), empirical (based on the observed statistical relations between the variables) and hybrid (including considerations based on physical principles, combined with the observed statistical relations between the variables). For forecasting large-scale spatial patterns, machine learning-based empirical models are capable of competing with or outperforming existing dynamical models [2].

AGRINEXO seasonal forecasts are empirical forecasts, obtained by training a Support Vector Regression model on a dataset comprising the 22.5-67.5-degrees North and South sections of a global grid climate time-series and a 1-degree grid cell climate time-series of the specific forecast location. Each forecast is based on the previous 36 months, to encompass effects that are not traceable in the prevailing conditions over the last year [3], but that appear to be relevant for the forecast.

## What's next for AGRINEXO SCF

The overall forecast skill of the diverse seasonal climate forecast models is not ideal. Specifically, the Anomaly Correlation Coefficient [4], should be above 0.5 for a forecast to be considered consistently more usable than the climate normal for a specific location. The chaotic nature of weather [5] limits the forecast skill that should be reasonably expected from seasonal climate forecast models. However, the comparison of diverse models over specific regions, shows that models with similar overall forecast skill perform better at diverse regions, therefore suggesting that each of the models is somewhat inconsistent or incomplete. We are working on it!

We have released the source code of AGRINEXO SCF under the MIT license, because it is often difficult to discuss specific modelling approaches and results, without analysing the source code of the artifacts used in its implementation. AGRINEXO SCF source code is derived and composed from fragments of diverse solutions we have built over the years. Several sections of the code were rewritten so that they do not depend on specific proprietary libraries and the solution often resorts to different techniques to implement similar features. AGRINEXO SCF source code requires substantial consolidation and documentation efforts, to make it more easily understandable and reusable. We are working on it!

Occasionally, the frontier between assisting and preventing gets blurred. We strive to make AGRINEXO SCF a responsible and human-friendly machine learning solution in the sense that: the forecast skill of each of the forecasts is always presented (it does not try to impersonate humans or simulate a knowledge it does not have); references of cited articles, data sources and artifacts are included in the source code and in the documentation (there may be a few missing references, but to the best of our knowledge the solution does not automate plagiarism or copyright infringement); source code is shared so that the model and the results can be discussed and eventually discarded or improved (the solution is not likely to assist humans to the point of preventing them from discussing, thinking, writing or developing mathematical models).

## References

[1] _Brás, T. A., Seixas, J., Carvalhais, N., & Jägermeyr, J. (2021). Severity of drought and heatwave crop losses tripled over the last five decades in Europe. Environmental Research Letters, 16(6), 065012._

[2] _Gibson, P. B., Chapman, W. E., Altinok, A., Delle Monache, L., DeFlorio, M. J., & Waliser, D. E. (2021). Training machine learning models on climate model output yields skillful interpretable seasonal precipitation forecasts. Communications Earth & Environment, 2(1), 159._

[3] _Abreu, V.M. (2019). Analysis and Forecasting of Agricultural Commodity Prices (ePMA Prototype). MSc Thesis. Instituto Superior Técnico, University of Lisbon._

[4] _Owens, R. G. & Hewson, T. D. (2018). ECMWF Forecast User Guide._

[5] _Firth, W. J. (1991). Chaos--predicting the unpredictable. BMJ: British Medical Journal, 303(6817), 1565._

## Built With 
- apexcharts
- copernicus.eu
- javascript
- jquery
- leaflet.js
- openshift
- openstreetmap
- php
- postgresql
- python
- scikit-learn
- tabulator
