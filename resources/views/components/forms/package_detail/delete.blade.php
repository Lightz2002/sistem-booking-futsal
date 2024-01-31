<x-modal name="delete-package-detail" :show="false">
  <form wire:submit.prevent="deletePackageDetail" class="p-6">
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Are you sure you want to delete this package detail ?') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __("This action can't be reverted !") }}
      </p>

      <div class="mt-6 flex justify-end">
          <x-secondary-button x-on:click="$dispatch('close'); ">
              {{ __('Cancel') }}
          </x-secondary-button>

          <x-danger-button class="ms-3">
              {{ __('Delete Package Detail') }}
          </x-danger-button>
      </div>
  </form>
</x-modal>