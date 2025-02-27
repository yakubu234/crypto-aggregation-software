
## Architecture Overview

### **Key Components**
1. **Job System**: 
   - `**FetchCryptoPricesJob**` (Job) fetches and queues cryptocurrency prices.
   - Prices are fetched asynchronously from configured exchanges.
   - Fetches prices from multiple exchanges using `CryptoPriceFetcherService`.
   - Passes the fetched data to `CryptoAggregatorService` for processing.
   - Dispatches `CryptoPriceUpdated` event for real-time updates.
2. **Services**: **CryptoAggregatorService** (Service)
   - `CryptoPriceFetcherService` calls external APIs to retrieve prices.
    - Aggregates data by calculating:
     - **Average price** using the `last` field from the API response using the `CryptoAggregatorService` 
     - **Calculates Price change percentage** using the `daily_change_percentage` field in the `CryptoAggregatorService`
     - **Exchange count** to ensure accuracy of exchanges returning data for each pairs.
   - `CryptoPriceService` handles data retrieval and caching.
3. **Events**:
   - `CryptoPriceUpdated` event is triggered after processing prices, broadcasting the new data via WebSockets.
4. **Database**:
   - `CryptoPrice` model stores cryptocurrency prices.
5. **APIs**:
   - REST API (`/api/crypto-prices`) fetches stored prices.
   - WebSocket API emits real-time price updates.
6. **Frontend (Laravel LiveWire)**:
   - Displays crypto price data in real-time.
   - Updates UI with price changes using WebSockets.
   
---
## How It Works

### **API Workflow**
1. **Triggering the API Call**:
   - A scheduled Laravel job (`FetchCryptoPricesJob`) is executed periodically using Supervisor.
   - **Supervisor for Queue Management:** Ensures jobs are processed efficiently without manual intervention.
   - This job invokes `CryptoPriceFetcherService` to fetch prices asynchronously from multiple exchanges.

2. **Processing & Storing Data**:
   - The fetched data is passed to `CryptoAggregatorService` to compute average prices.
   - **Using the `last` field:** Ensures accuracy in calculating average prices.
   - **Price Change Calculation:** Uses the `daily_change_percentage` field correctly.
   - Aggregated data is persisted in the `crypto_prices` table.
   - Cached data is updated to improve response times.

3. **Emitting Events**:
   - After persisting data, the `CryptoPriceUpdated` event is dispatched.
   - The event triggers a WebSocket broadcast to notify all subscribed clients.

4. **Frontend Updates**:
   - On page load, the frontend fetches the latest prices from the REST API.
   - The WebSocket connection listens for real-time updates and updates the UI dynamically.
   - UI elements animate when new data is received.

---
## Design Decisions & Trade-offs

### **Asynchronous Fetching**
- Used Laravel’s HTTP pooling to fetch exchange data in parallel.
- Ensures minimal latency and accurate price aggregation.

### **WebSocket vs Polling**
- WebSocket API reduces unnecessary API calls, ensuring efficient real-time updates.
- Polling would have introduced additional server load.

### **Caching Strategy**
- Used Laravel’s cache (`Redis`) to minimize redundant database queries.
- Cached price data refreshes at configured intervals.

### **Error Handling & Retries**
- Implemented retry mechanisms in queued jobs.
- Logs failures and reattempts fetching missing prices.

---
## Testing

### **Unit & Feature Tests**
- Wrote unit tests for core services (`CryptoPriceFetcherService`, `CryptoAggregatorService`).
- Feature tests ensure API and WebSocket behaviors.

Run tests with:
```sh
php artisan test
```

- **Frontend UI Enhancements**
  - Animated price changes.
  - Responsive design.

## .docker-env setup
- Ensure the `.docker-env` file is correctly configured before running the project.

- **To Run Dockerized Setup**
  ```sh
  docker-compose up --build -d

---
## CI/CD (GitHub Actions Workflow)

### **Pipeline Workflow**
1. Runs tests.
2. Builds the project and ensures all dependencies are resolved.
<!-- 3. Deploys the application if all tests pass. -->

---
## Known Issues & Possible Improvements

### **Limitations**
- Current WebSocket reconnection logic may need refinement for handling network drops.
- Name of the Pairs are fetched from the API response, a better mapping with local data representing pairs in humman readable for is suggested. i.e BTC/USDT and not BTCUSDT.
- The logos for each left operand pairs can be persisted into the database and mapped accordingly to each pair when returned, presently uses one single log for all configure pairs. 
- The frontend lacks deep historical price visualization.

### **Improvements**
- Introduce historical price trend charts.
- Implement pagination for large datasets in REST API.
- Optimize frontend with more UI enhancements and transitions.

---
## Final Notes
- For production deployment, use queue workers with `supervisorctl` for stability.
- Contributions and improvements are welcome!

## Author
Developed by Yakubu Abiola

## Setup Documentations
For a detailed setup documentation, please refer to the [READ ME MARKDONW](./README.md) section.

## a setup of this project to a cloud server now available at http://crypto.aob.com.ng(http://crypto.aob.com.ng)

<!-- **Happy coding!** 🚀 -->

## Conclusion
This implementation meets all technical requirements and follows best practices. If any improvements are needed, please review the above documentation before making changes.
