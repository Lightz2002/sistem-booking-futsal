<x-modal name="confirm-package" :show="false">
  <form wire:submit.prevent="confirmPackage" class="p-6">
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Are you sure you want to confirm this package ?') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ __('Once package is confirmed, allotments will be generated until package valid end date.
        The confirmed package cannot be edited and deleted, and the allotments also cannot be deleted ') }}
      </p>

      <div class="mt-6 flex justify-end">
          <x-secondary-button x-on:click="$dispatch('close'); ">
              {{ __('Cancel') }}
          </x-secondary-button>

          <x-primary-button class="ms-3">
              {{ __('Confirm And Generate Allotment') }}
          </x-primary-button>
      </div>
  </form>
</x-modal>