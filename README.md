# Cryptocurrency Price Aggregator

## Overview
This project implements a cryptocurrency price aggregation system using Laravel, Laravel Queue (Redis), Supervisor, WebSockets (Laravel Reverb), and Laravel LiveWire. The system fetches cryptocurrency prices from multiple exchanges, computes the average price, stores the results in a database, and broadcasts the updates in real time via WebSockets.

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

## Installation
Clone the repository and install dependencies:

```sh
# Clone the repository
git clone https://github.com/your-repository.git
cd your-repository

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
Edit the `.env` file and set up the following configurations: change only the database setting and also update `CRYPTO_API_KEY` to your API key. 

```ini
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:+N2vngdqHagMxvm0+wEtqnMJ0b5XuTBziaK3zNKc4vE=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=daily
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug


SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

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

#higly recommemded to use same setting as below
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

CRYPTO_EXCHANGES=binance,mexc,kucoin,huobi,bybit
CRYPTO_PAIRS=BTC,ETH,ETHBTC,BTCUSDT,XRPBTC
CRYPTO_FETCH_INTERVAL=60
CRYPTO_API_KEY=3htbhru459te9ivgi959ure9
CRYPTO_API_URL=https://api.freecryptoapi.com/v1/getData

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
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

## Running the Application
Start the necessary services in separate terminal sessions:

```sh
# Start WebSocket Server
php artisan reverb:start

# Start Queue Worker
php artisan horizon:start

# Start Schedule Worker
php artisan  schedule:work
# Start Laravel Server
php artisan serve

# Start Webpack for LiveWire
npm run dev
```

## Running the Application
You can as well run the commands as a one liner by getting into theroot directory of the project and type:

```sh
#  from the terminal
./start-services.sh

# or this 
bash start-services.sh
```

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

## Deployment Notes
Kindly note that for better performances, it is encouraged to setup a supervisor for the backgroup processess, the configs below are list of the background tasks. 

- Use **Supervisor** to manage queue workers in production.
- Set up **Redis** for queue and cache management.
- Configure **NGINX/Apache** for Laravel and WebSocket proxying.

### Supervisor Configuration Example for horizon
Create a configuration file `/etc/supervisor/conf.d/laravel-horizon.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/artisan horizon
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/storage/logs/horizon.log
```

Restart Supervisor:

```sh
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-horizon:*
```


### Supervisor Configuration Example for scheduler
Create a configuration file `/etc/supervisor/conf.d/laravel-schedule.conf`:

```ini
[program:laravel-scheduler]
process_name=%(program_name)s
command=php /var/www/artisan schedule:work
autostart=true
autorestart=true
user=your_user
stderr_logfile=/var/www/storage/logs/supervisor-scheduler.err.log
stdout_logfile=/var/www/storage/logs/supervisor-scheduler.out.log
```

Restart Supervisor:

```sh
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-schedule:*
```
## Author
Developed by Yakubu Abiola

## a setup of this project to a cloud server will be available soones. once completed, the url will be added here for testing.
## The docker is not fully functional yet.

## License
This project is licensed under the MIT License.
