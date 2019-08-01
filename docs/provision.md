DevOps

##### COMPONENTS THAT NEED TO BE RUNNING AND MONITORED
* `nginx`
* `mysql`
* `artisan queue:work`
* `laravel-echoserver`
* `smtp`
* `redis`

##### PROVIDERS
* Petros Diveris
  * Free. Limitations
* Amazon
  * Eva to ask about pricing
* Digital Ocean
  * Eva to ask about pricing
* Traditional ISPs e.g. Hetzner
  * Again, Eva to ask about pricing

##### PREREQUISITIES
* Debian
* Docker
* Min 8GB RAM
* Min 4 cores
* Min 50GB disk

##### MONITORING
* Free web page level http://montastic.com/

##### Look at
* Ubuntu MicroK8s
* 

## POST DEPLOYMENT
Until I find how to instruct Laravel mix to produce sane JS output for/from my React components, after deployment it might be worth manually uglifying the main output
* `uglifyjs  public/js/app.js --compress -o apismall.js` and
* replace site's api.js with newly creayed file



