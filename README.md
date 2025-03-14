# Cryptocurrency Price Aggregator

## Overview
This project implements a cryptocurrency price aggregation system using Laravel, Laravel Queue (Redis), Supervisor, WebSockets (Laravel Reverb), and Laravel LiveWire. The system fetches cryptocurrency prices from multiple exchanges, computes the average price, stores the results in a database, and broadcasts the updates in real time via WebSockets.


## Author
Developed by Yakubu Abiola

## a setup of this project to a cloud server now available at http://crypto.aob.com.ng(http://crypto.aob.com.ng)


## How It Works Design decisions and trade-offs, Known issues or limitations and possible improvements
For a detailed bussiness logict  of how this project works, please refer to the [How It Works](./HOW_IT_WORKS.md) section.

## Good news; The docker is now fully functional. (:
## Features
- Configurable cryptocurrency pairs and exchanges.
- Fetches price data in parallel from multiple exchanges.
- Stores price data in the database and caches results.
- Provides a REST API to retrieve the last known cryptocurrency prices.
- WebSocket API for real-time updates.
- Laravel LiveWire frontend to display price data with live updates.
- Implements SOLID principles and unit tests.

## Prerequisites
Ensure you have the following installed:
- **PHP 8.3+**
- **Composer**
- **Laravel 11**
- **Redis**
- **Node.js & NPM**
- **Supervisor** (for managing queue workers)
- **Database** (MySQL, PostgreSQL, or SQLite)

## Packages Used
the following packages are implemented in this project:
- **Laravel Horizon for serving and managing queuue+**
- **Laravel Reverb for realtime communitcation**
- **Laravel Echo . the WS connection at the front end**
- **Livewire. a template engine**


## API Endpoints
### Fetch Cryptocurrency Prices (REST API)
**Endpoint:**
```http
GET /api/crypto-prices
```

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "symbol": "BTCUSDT",
            "averagePrice": 45000.25,
            "priceChange": 2.5,
            "exchanges": ["binance", "mexc"],
            "serverTime": "2025-02-23 12:34:56"
        }
    ]
}
```

## WebSocket Events
The WebSocket broadcasts cryptocurrency price updates on the `crypto-prices` channel.

**Event:**
```json
{
    "event": "CryptoPriceUpdated",
    "data": {
        "crypto": {
            "BTCUSDT": {
                "averagePrice": 45000.25,
                "priceChange": 2.5,
                "exchanges": ["binance", "mexc"],
                "serverTime": "2025-02-23 12:34:56"
            }
        }
    }
}
```


## How It Works Design decisions and trade-offs, Known issues or limitations and possible improvements
For a detailed bussiness logict  of how this project works, please refer to the [How It Works](./HOW_IT_WORKS.md) section.

## Installation  & Running the Application with Docker
cd into the project root directory:

```sh
# Clone the repository
git clone https://github.com/yakubu234/crypto-aggregation-software.git
cd crypto-aggregation-software


# copying the enviromental varriables
please ensure you .docker-env varriables are set. (note that this will be copied to replace .env) . check below for settings details.
```

```ini
# Database Configuration setup required. you can make changes as you like.
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

#higly recommemded to use same setting as below
# Broadcating driver
BROADCAST_CONNECTION=reverb
BROADCAST_DRIVER=reverb

FILESYSTEM_DISK=local

CACHE_STORE=database
CACHE_PREFIX=

HORIZON_DOMAIN=localhost
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

REVERB_PORT=8080
REVERB_HOST=127.0.0.1
REVERB_SCHEME=http
REVERB_APP_ID=510578
REVERB_APP_KEY=qhxbwgflshg1ufehgtt6
REVERB_APP_SECRET=r4ejeattunzkaggtph9e

CRYPTO_EXCHANGES=binance,mexc,kucoin,huobi,bybit #exchanges
CRYPTO_PAIRS=BTC,ETH,ETHBTC,BTCUSDT,XRPBTC #pairs
CRYPTO_FETCH_INTERVAL=60 #time interval for fetching ; i.e 60 seconds
CRYPTO_API_KEY=3htbhru459te9ivgi959ure9 #free crypto api API KEY
CRYPTO_API_URL=https://api.freecryptoapi.com/v1/getData
```

```sh
# When you are done setting up the .docker-env (note that this will be copied to replace .env)
Continue to run the docker images, by first building or using the one-liner command


# build the images
docker compose build

# Start the application
docker compose up

[OR]

# Start the application in deamon mode
docker compose up -d


# Once the Application is up visit the url below on your browser
http://localhost:8080

# To stop the application
docker compose down
```


## Installation without dockerization
Clone the repository and install dependencies:

```sh
# Clone the repository
git clone https://github.com/yakubu234/crypto-aggregation-software.git
cd crypto-aggregation-software

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install && npm run dev

# Copy and configure environment variables
cp .env.example .env

# Generate application key
php artisan key:generate
```

## Configuration
Edit the `.env` file and set up the following configurations: you can make changes as you like: NB: if you are to run it on docker, make the changes in .docker-env 

```ini
# Database Configuration setup required. you can make changes as you like.
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

#higly recommemded to use same setting as below
# Broadcating driver
BROADCAST_CONNECTION=reverb
BROADCAST_DRIVER=reverb

FILESYSTEM_DISK=local

CACHE_STORE=database
CACHE_PREFIX=

HORIZON_DOMAIN=localhost
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

REVERB_PORT=8080
REVERB_HOST=127.0.0.1
REVERB_SCHEME=http
REVERB_APP_ID=510578
REVERB_APP_KEY=qhxbwgflshg1ufehgtt6
REVERB_APP_SECRET=r4ejeattunzkaggtph9e

CRYPTO_EXCHANGES=binance,mexc,kucoin,huobi,bybit #exchanges
CRYPTO_PAIRS=BTC,ETH,ETHBTC,BTCUSDT,XRPBTC #pairs
CRYPTO_FETCH_INTERVAL=60 #time interval for fetching ; i.e 60 seconds
CRYPTO_API_KEY=3htbhru459te9ivgi959ure9 #free crypto api API KEY
CRYPTO_API_URL=https://api.freecryptoapi.com/v1/getData
```

### Exchange & Fetch Interval Configuration
Modify `config/exchange.php` to specify cryptocurrency pairs, exchanges, and API details:

```php
return [
    'pairs' => [
        'list' => ['BTCUSDC', 'BTCUSDT', 'BTCETH'],
    ],
    'exchange' => [
        'list' => ['binance', 'mexc', 'huobi'],
    ],
    'url' => env('CRYPTO_API_URL', 'https://crypto-api.example.com'),
    'api_key' => env('CRYPTO_API_KEY'),
];
```

## Database Migration
Run migrations to set up database tables:

```sh
php artisan migrate
```

## Running the Application without docker
Start the necessary services in separate terminal sessions:

```sh
# Start WebSocket Server
php artisan reverb:start

# Start Queue Worker
php artisan horizon

# Start Schedule Worker
php artisan  schedule:work
# Start Laravel Server
php artisan serve

# Start Webpack for LiveWire
npm run dev
```

## Running the Application with a bash script
You can as well run below commands as a one liner at the root directory of the project to stat all neccessary services :

```sh
#  from the terminal
./start-services.sh

# or this 
bash start-services.sh
```

## Running Tests
### Unit & Feature Tests
Ensure Redis and your database are running before executing tests.

```sh
php artisan test
```

### Queue & WebSocket Testing
1. Dispatch a test job manually:

   ```sh
   php artisan horizon:start
   ```

2. Listen for WebSocket events:

   ```sh
   php artisan reverb:work --debug
   ```


## How It Works Design decisions and trade-offs, Known issues or limitations and possible improvements
For a detailed bussiness logict  of how this project works, please refer to the [How It Works](./HOW_IT_WORKS.md) section.

## Deployment Notes
Kindly note that for better performances, it is encouraged to setup a supervisor for the backgroup processess, the configs below are list of the background tasks. 

- Use **Supervisor** to manage queue workers in production.
- Set up **Redis** for queue and cache management.
- Configure **NGINX/Apache** for Laravel and WebSocket proxying.

### Supervisor Configuration Example for horizon
Create a configuration file `/etc/supervisor/conf.d/laravel-horizon.conf`:

```ini
[program:laravel-horizon]
command=php artisan horizon
directory=/var/www
autostart=true
autorestart=true
stderr_logfile=/var/www/storage/logs/horizon.log
stdout_logfile=/var/www/storage/logs/horizon.log
```

### Supervisor Configuration Example for scheduler
Create a configuration file `/etc/supervisor/conf.d/laravel-schedule.conf`:

```ini
[program:laravel-scheduler]
command=php artisan schedule:work
directory=/var/www
autostart=true
autorestart=true
stderr_logfile=/var/www/storage/logs/schedule.log
stdout_logfile=/var/www/storage/logs/schedule.log
```

### Supervisor Configuration Example for reverb
Create a configuration file `/etc/supervisor/conf.d/laravel-reverb.conf`:

```ini
[program:laravel-reverb]
command=php artisan reverb:start --debug --host=0.0.0.0 --port=8080
directory=/var/www
autostart=true
autorestart=true
stderr_logfile=/var/www/storage/logs/reverb.log
stdout_logfile=/var/www/storage/logs/reverb.log
```

Restart Supervisor:

```sh
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```
## Author
Developed by Yakubu Abiola


## How It Works Design decisions and trade-offs, Known issues or limitations and possible improvements
 
For a detailed bussiness logict  of how this project works, please refer to the [How It Works](./HOW_IT_WORKS.md) section.

## a setup of this project to a cloud server now available at http://crypto.aob.com.ng(http://crypto.aob.com.ng)


## License
This project is licensed under the MIT License.
