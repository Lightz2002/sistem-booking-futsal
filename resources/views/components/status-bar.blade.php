@props([
    'statuses',
    'selectedStatus'
])

@php
    $statusCases = [
      'active' => [
        'name' => 'active',
        'activeStyle' => '!bg-teal-100 !text-teal-800',
        'hoverStyle' => 'hover:bg-teal-100 hover:text-teal-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                  </svg>

                  '
      ],
      'rejected' => [
        'name' => 'Rejected',
        'activeStyle' => '!bg-red-100 !text-red-800',
        'hoverStyle' => 'hover:bg-red-100 hover:text-red-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                  </svg>

                  '
      ],
      'verifying' => [
        'name' => 'verifying',
        'activeStyle' => '!bg-blue-100 !text-blue-800',
        'hoverStyle' => 'hover:bg-blue-100 hover:text-blue-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1. 
                    5" stroke="currentColor" class="w-4 h-4 me-2 font-bold">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                    </svg>'
      ],
      'delivering' => [
        'name' => 'Delivering',
        'activeStyle' => '!bg-indigo-100 !text-indigo-800',
        'hoverStyle' => 'hover:bg-indigo-100 hover:text-indigo-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 font-bold">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                  </svg>'
      ],
      'confirmed' => [
        'name' => 'Confirmed',
        'activeStyle' => '!bg-green-100 !text-green-800',
        'hoverStyle' => 'hover:bg-green-100 hover:text-green-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 font-bold">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>'
      ],
      'upcoming' => [
        'name' => 'upcoming',
        'activeStyle' => '!bg-purple-100 !text-purple-800',
        'hoverStyle' => 'hover:bg-purple-100 hover:text-purple-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 font-bold">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
            </svg>'
      ],
      'history' => [
        'name' => 'history',
        'activeStyle' => '!bg-cyan-100 !text-cyan-800',
        'hoverStyle' => 'hover:bg-cyan-100 hover:text-cyan-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2 font-bold">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>'
      ]
    ];

    $statusArr = [];

    foreach ($statuses as $status) {
      $status = strtolower($status);
      $statusArr[] = $statusCases[$status];
    }

@endphp

<div class="flex mb-4">
  @foreach ($statusArr as $item)
  <div
      wire:click="filterStatus('{{ $item['name'] }}')"
      class="hover:cursor-pointer me-2 text-left w-32 py-2 flex font-bold items-center rounded-md text-slate-800 bg-slate-400 text-sm px-4 {{ $item['hoverStyle'] }}
      {{ $selectedStatus == $item['name'] ? $item['activeStyle'] : '' }}">
      {!! $item['icon'] !!}

      {{ ucwords($item['name']) }}
  </div>
  @endforeach
</div>
