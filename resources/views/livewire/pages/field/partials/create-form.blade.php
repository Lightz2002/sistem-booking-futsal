<?php
use Livewire\Volt\Component;

new class extends Component {

}
?>

<x-modal name="add-field" :show="false" focusable>
  <form wire:submit.prevent="$parent.addField" class="p-6" enctype="multipart/form-data">

      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Add Field') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __('') }}
      </p>

      <div class="mt-6">
          <x-input-label for="name" value="{{ __('Name') }}"  />

          <x-text-input
              wire:model.blur="$parent.name"
              id="name"
              name="name"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Name') }}"
          />

          <x-input-error :messages="$errors->get('name')" class="mt-2" />
      </div>

      <div class="mt-6">
          <x-input-label for="image" value="{{ __('Image') }}"  />

          <x-text-input
              wire:model="$parent.image"
              id="image"
              name="image"
              type="file"
              accept="image/*"
              class="my-1 block w-3/4"
              placeholder="{{ __('Image') }}"
          />


          <progress wire:loading wire:target="image" class="bg-indigo-600 progress w-56">Uploading...</progress>

          <x-input-error :messages="$errors->get('image')" class="mt-2" />
      </div>

      <div class="mt-6 flex justify-end">
          <x-secondary-button x-on:click="$dispatch('close')">
              {{ __('Cancel') }}
          </x-secondary-button>

          <x-primary-button class="ms-3">
              {{ __('Save') }}
          </x-primary-button>
      </div>
  </form>
</x-modal>