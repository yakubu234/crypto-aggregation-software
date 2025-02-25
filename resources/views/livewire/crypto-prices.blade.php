<div wire:poll.5s>
   <div class="crypto-prices-container">
      <div class="row mb-3">
         <div class="col-12 text-center" wire:ignore>
            @include('components.layouts.timer')
         </div>
      </div>
      <div class="app-container" wire:ignore>
         <div class="card-container"></div>
      </div>
      @include('components.layouts.footer')
   </div>
</div>
