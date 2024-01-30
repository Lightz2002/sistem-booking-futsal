<!-- resources/views/components/alpine-select.blade.php -->

@props(['options' => [], 'model' => '', 'placeholder' => 'Select an option', 'data' => [], 'allowInput' => false])

<div class="relative  w-3/4 z-9999" x-data="{ open: false }">
    <x-text-input class="mt-1 block  w-full overflow-x-hidden overflow-y-auto" @focus="open = true;" 
    x-on:click.away="open = false"
    wire:model.live="{{ $model }}">
    </x-text-input>

    <ul x-show="open" @close-autocomplete.window="open = false" class="absolute top-15 bg-white w-full shadow-md rounded-md max-h-40 overflow-y-auto py-4">
    {{-- x-bind:class="{ 'overflow-hidden': open }"> --}}
        <li @click="$wire.{{ $model }} = '';" class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white">-- Select Options --</li>

        @if (count($options) === 0)
            <li class="text-gray-400  px-4 py-2 ">No Options Found</li>
        @endif

        @foreach($options as $option)
            <li @click="$wire.{{ $model }} = '{{ $option->name }}';" class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-white">{{ $option->name }}</li>
        @endforeach 
    </ul>

    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 absolute right-3 top-1/2 translate-y-[-50%] hover:cursor-pointer"
    @click.stop="open = true;"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
      </svg>
</div>
