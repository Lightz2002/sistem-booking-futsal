@props([
  'model',
  'search',
])

<div {{ $attributes->merge(['class' => "flex items-center w-full mb-4"]) }} >
  {{ $slot }}

  <div class="w-1/3 relative ">
    <input wire:model.live="{{ $model }}" wire:keydown="{{ $search }}" name="search" type="search" placeholder="Search..."
        class="border-slate-500 text-slate-500  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full me-2">
  
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="stroke-slate-400 w-6 h-6 absolute top-1/2 right-10 translate-y-[-50%]">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
    </svg>
  </div>
</div>