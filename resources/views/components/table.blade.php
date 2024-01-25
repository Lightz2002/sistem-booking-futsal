@props([
  'details',
  'detailColumns',
  'sortBy' => 'created_at',
  'sortDirection' => 'asc',
]
)

<div class="mb-4  overflow-hidden rounded-lg shadow-md">
  <table class="table-auto w-full  text-left  border-slate-200 mb-2">
    <thead>
        <tr>
            <th
            class="p-3 border-b text-center  border-slate-200 bg-slate-200 text-slate-500 cursor-pointer">
                <p>No</p>
            </th>
  
            @foreach ($detailColumns as $column)
                <th
                    wire:click="sort('{{ $column['key'] }}')"
                    class="p-3 border-b  border-slate-200 bg-slate-200 text-slate-500 cursor-pointer">
                    <div class="flex">
                        {{ $column['label'] }}
                        @if ($sortBy === $column['key'])
                            @if ($sortDirection === 'asc')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            @endif
                        @endif
                    </div>
                </th>
            @endforeach
  
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $row)
            <tr>
                <td class="p-3 text-center border-b cursor-pointer bg-white text-gray-800 border-slate-200">
                    {{ $loop->iteration }}
                </td>
  
                @foreach ($detailColumns as $column)
                    <td class="p-3 border-b cursor-pointer bg-white text-gray-800 border-slate-200">
                        {{-- <div class="py-3 px-6 text-gray-800 flex items-center cursor-pointer"> --}}
                        {{-- <x-dynamic-component :id="$row['id']" :component="$column['component']" :value="$row[$column['key']]" :row="$row">
                        </x-dynamic-component> --}}
                        {{-- {{ $row[$column['key']] }} --}}
                        {{-- </div> --}}
                        <x-dynamic-component :id="$row['id']" :component="$column['component'] ?? 'columns.column'" :value="$row[$column['key']]"
                            :row="$row">
                        </x-dynamic-component>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
  </table>

  {{ $details->withQueryString()->links() }}
</div>