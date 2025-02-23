<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" > </script> 
<script >
   async function fetchInitialPrices() {
      try {
         let response = await fetch('/api/crypto-prices');
         let data = await response.json();
         if (data.status === "success") {
            // perform the data astore here
            updateUI(data.data);
         }
      } catch (error) {
         console.error("Failed to fetch initial prices:", error);
      }
    }

    function updateLocalTime() {
        const localTimeElement = document.getElementById("current-time");
        if (localTimeElement) {
            localTimeElement.textContent =
                new Date().toLocaleTimeString();
        }
    }

    //call it once immediately so the time is shown right away.
    updateLocalTime();

    function formatMoney(value) {
        // Convert the value to a string
        const valueString = String(value);

        // Determine if a decimal point exists and capture the decimals
        let decimals = 0;
        if (valueString.includes('.')) {
            const parts = valueString.split('.');
            decimals = parts[1].length;
        }

        // Convert the value to a float for arithmetic operations
        const number = parseFloat(value);
        const multiplier = Math.pow(10, decimals);

        // Truncate the number (do not round)
        let truncated;
        if (number >= 0) {
            truncated = Math.floor(number * multiplier) / multiplier;
        } else {
            truncated = Math.ceil(number * multiplier) / multiplier;
        }

        // Format the truncated number with commas and the exact number of decimals.
        // Using toLocaleString for comma separation and fixed decimals.
        return truncated.toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    function updateUI(prices) {
        if (!prices || prices.length === 0) {
            console.warn("No prices available to update.");
            return; // Exit function early
        }
        const cardContainer = document.querySelector('.card-container');

        // Loop over the updated prices
        prices.forEach(price => {
            // Check if the crypto pair is empty; if so, skip this entry.
            if (!price.pair || price.pair.trim() === '') {
                return;
            }

            // Use a unique ID for each card based on the crypto pair.
            let card = document.getElementById(`card-${price.pair}`);

            // Build the card’s inner HTML from your template.
            const cardHTML = `
                <img src="${price.image || 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/1040483/dash.png'}" alt="${price.pair}">
                <div class="coin-data">
                <p class="coin-name">${price.pair} (${price.pair})</p>
                <p>${formatMoney(price.average_price)}</p>
                <div class="icon-indicator-and-time">
                    <p class="${price.price_change >= 0 ? 'pos' : 'neg'}">
                        ${price.price_change >= 0 ? '+' : ''}${price.price_change}% 
                        <span><i class="bi ${price.price_change >= 0 ? 'bi-arrow-up' : 'bi-arrow-down'}"></i></span>
                    </p>
                    <p class="time-wrapper">
                        <span><i class="bi bi-alarm"></i></span> 
                        <span class="time">${price.timestamp}</span>
                    </p>
                </div>
                <div class="exchanges-where-pulled">
                    <p>${price.exchange}</p>
                </div>
                </div>
                `;

            if (card) {
                // If the card already exists, update its content.
                card.innerHTML = cardHTML;
                // Attach an update animation if it's not the first render.
                card.classList.add('update-animation');
                card.addEventListener('animationend', () => {
                    card.classList.remove('update-animation');
                }, {
                    once: true
                });
            } else {
                // If the card does not exist, create it.
                card = document.createElement('div');
                card.className = 'card';
                card.id = `card-${price.pair}`;
                card.innerHTML = cardHTML;
                cardContainer.appendChild(card);

                // Animate the new card on first render.
                card.classList.add('initial-animation');
                card.addEventListener('animationend', () => {
                    card.classList.remove('initial-animation');
                }, {
                    once: true
                });
            }
        });
    }

    let retryAttempts = 0;

    function connectWebSocket() {
        console.log("Attempting to connect WebSocket...");

        window.Echo.channel('crypto-prices')
            .listen('.CryptoPriceUpdated', (event) => {
                console.log("Received update:");
                // perform the data store here
                updateUI(event.crypto);
            });

        window.Echo.connector.pusher.connection.bind('connected', function () {
            console.log("WebSocket successfully connected.");
            retryAttempts = 0;
        });

        window.Echo.connector.pusher.connection.bind('disconnected', function () {
            console.warn("WebSocket disconnected, attempting to reconnect...");

            let retryDelay = Math.min(5000 * Math.pow(2, retryAttempts), 60000);
            retryAttempts++;

            setTimeout(() => {
                connectWebSocket();
            }, retryDelay);
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
    fetchInitialPrices();
    connectWebSocket();
    });

    // Update the time every second.
    setInterval(updateLocalTime, 1000);

</script>