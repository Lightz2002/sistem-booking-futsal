

<x-modal name="edit-package" :show="false">
  <form wire:submit.prevent="editPackage" class="p-6 text-gray-800" enctype="multipart/form-data">

      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Edit Package') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __('') }}
      </p>

      <div class="mt-6">
          <x-input-label for="code" value="{{ __('Code') }}"  />

          <x-text-input
              model="editPackageForm.code"
              id="code"
              name="code"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Code') }}"
          />

          <x-input-error :messages="$errors->get('editPackageForm.code')" class="mt-2" />
      </div>

      <div class="mt-6">
          <x-input-label for="name" value="{{ __('Name') }}"  />

          <x-text-input
              model="editPackageForm.name"
              id="name"
              name="name"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Name') }}"
          />

          <x-input-error :messages="$errors->get('editPackageForm.name')" class="mt-2" />
      </div>

      <div class="mt-6">
          <x-input-label for="valid_end" value="{{ __('Valid End') }}"  />

          <x-text-input
              model="editPackageForm.valid_end"
              id="valid_end"
              type="date"
              name="valid_end"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Valid End') }}"
          />

          <x-input-error :messages="$errors->get('editPackageForm.valid_end')" class="mt-2" />
      </div>

      <div class="mt-6">
          <x-input-label for="image" value="{{ __('Image') }}"  />

          <x-text-input
              model="editPackageForm.image"
              id="image"
              type="file"
              name="image"
              accept="image/*"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Image') }}"
          />

          <x-input-error :messages="$errors->get('editPackageForm.image')" class="mt-2" />

          <div wire:loading wire:target='editPackageForm.image' class="bg-indigo-600 text-white mt-2 animate-pulse w-3/4 px-4 py-1 rounded-full max-h-6  text-sm">Uploading...</div>
      </div>

      <div class="mt-6">
        <x-input-label for="field" value="{{ __('Field') }}"  />

        <x-select
            model="editPackageForm.field"
            :options="$fieldAutoCompletes"
            :data="['id' => 'field', 'name' => 'field']"
        />

        <x-input-error :messages="$errors->get('editPackageForm.field')" class="mt-2" />
    </div>

      <div>
          <x-input-error :messages="$errors->get('editPackageForm.id')" class="mt-2" />
      </div>

      <div class="mt-6 flex justify-end">
          <x-secondary-button x-on:click="$dispatch('close');">
              {{ __('Cancel') }}
          </x-secondary-button>

          <x-primary-button class="ms-3">
              {{ __('Save') }}
          </x-primary-button>
      </div>
  </form>
</x-modal>