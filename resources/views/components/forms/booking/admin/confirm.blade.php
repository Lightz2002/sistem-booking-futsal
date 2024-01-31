<x-modal name="confirm-booking-payment" :show="false">
  <form wire:submit.prevent="confirmBookingPayment" class="p-6">
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Are you sure you want to confirm this booking payment ?') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ __('') }}
      </p>

      <div class="mt-6 flex justify-end">
          <x-secondary-button x-on:click="$dispatch('close'); ">
              {{ __('Cancel') }}
          </x-secondary-button>

          <x-primary-button class="ms-3">
              {{ __('Confirm') }}
          </x-primary-button>
      </div>
  </form>
</x-modal>