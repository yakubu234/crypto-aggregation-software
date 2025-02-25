<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CryptoPriceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The payload data.
     *
     * @var mixed
     */
    public $cryptos;

    /**
     * Indicates whether the data is processed and ready for broadcast.
     *
     * @var bool
     */
    public $isProcessed;

    /**
     * Create a new event instance.
     *
     * @param mixed $cryptos        Raw data (array) or processed data (model)
     * @param bool  $isProcessed Whether the data has been processed
     */
    public function __construct($cryptos, bool $isProcessed = false)
    {
        $this->cryptos = $cryptos;
        $this->isProcessed = $isProcessed;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('crypto-prices');
    }

    /**
     * Data to broadcast with the event.
     *
     * Only broadcasts if the data has been processed.
     *
     * @return array
     */
    public function broadcastWith()
    {
        if ($this->isProcessed == true) {
            return ['crypto' => $this->cryptos];
        }

        // When raw data is dispatched, no broadcast payload is emitted.
        return [];
    }

    /**
     * (Optional) Specify the broadcast event name.
     */
    public function broadcastAs()
    {
        return 'CryptoPriceUpdated';
    }
}
