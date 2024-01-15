@props([
    'name',
    'type' => 'success',
    'show' => false,
])

@php
    $alert = (object) [
        'bgColor' => 'bg-emerald-500',
        'color' => 'text-emerald-500 dark:text-emerald-400',
        'icon' => ' <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
        </svg>
',
    ];
    
    switch (strtolower($type)) {
        case 'info':
            $alert->bgColor = 'bg-blue-500';
            $alert->color = 'text-blue-500 dark:text-blue-400';
            $alert->icon = ' <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
        </svg>';
            break;
        case 'error':
            $alert->bgColor = 'bg-red-500';
            $alert->color = 'text-red-500 dark:text-red-400';
            $alert->icon = '<svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z" />
        </svg>';
            break;
        case 'warning':
            $alert->bgColor = 'bg-yellow-400';
            $alert->color = 'text-yellow-500 dark:text-yellow-400';
            $alert->icon = '  <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
        </svg>
';
            break;
        default:
            /* success alert */
            break;
    }
    
@endphp


<div class="flex w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-md bg-opacity-50 fixed bottom-5 right-5" 
    x-data="{ show: false }" x-show="show" x-cloak 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    x-on:open-alert.window="$event.detail.name == '{{ $name }}' ? show = true : null; setTimeout(() => show = false, 5000);"
    x-on:close="console.log(show); show = false;"
>
    <div class="flex items-center justify-center w-12 {{ $alert->bgColor }} ">
        {!!  $alert->icon !!}
    </div>

    <div class="px-4 py-2 -mx-3">
        <div class="mx-3">
            <span class="font-semibold {{ $alert->color }}">{{ ucfirst($type) }}</span>
            <p class="text-sm text-gray-600">{{ session('message') }}</p>
        </div>
    </div>

    <button class="ml-auto me-4 p-1 transition-colors duration-300 transform rounded-md hover:bg-opacity-25 hover:bg-gray-600 focus:outline-none" x-on:click="$dispatch('close')">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>
</div>