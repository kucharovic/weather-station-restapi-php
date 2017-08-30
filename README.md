# Weather station REST API

Simple REST API written in PHP for home IoT weather station (eg. ESP8266 based). API accept data for humidity (in percents) and for temperature (in degree Celsius). API is secured by Access Token (`X-AUTH-TOKEN: mySecretToken123`).

## Installation

 1. `$ git clone https://github.com/kucharovic/weather-station-restapi-php new-dir && cd new-dir`
 2. `$ cat .env.dist > .env`
 3. `$ vi .env` and enter your configuration
 4. `$ composer install`
 5. `$ bin/console doctrine:schema:create`
 6. `$ bin/console doctrine:schema:update --force`
 7. `$ bin/console app:setup:new-sensor 'Living room'` and write down sensor ID
 8. `$ bin/console app:setup:new-access-token 'Living room sensor'` and write down Access Token

## Usage

To read data of sensor:
```
$ curl -XGET -H 'X-Auth-Token: MySecretToken123' 'http://localhost:8000/0dbee98b-c9a5-44e7-b47f-c75964b2a5c5'
```

To post data from sensor:

```
$ curl -XPOST -H 'X-Auth-Token: MySecretToken123' -d '{
    "sensor":"0dbee98b-c9a5-44e7-b47f-c75964b2a5c5",
    "datetime": "2017-08-23T08:02:22+02:00",
    "humidity": "65.33",
    "temperature": "24.75"
}' 'http://localhost:8000'
```