<?php


/* 
1. cara kt bkin tgl hr ini smpe 30 itu gimn?
2. lalu next hrs cra kt tau tglnya brp smpe brp aja gimn?
3. cra kt nampilin jdi baris sesuai tgl itu gimn?
*/

use Carbon\Carbon;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Models\Field;
use App\Models\Package;
use App\Models\Allotment;
use Livewire\Attributes\Url;
use App\Livewire\Forms\FilterCustomerBookingFieldForm;

new class extends Component {
    public FilterCustomerBookingFieldForm $filterCustomerBookingFieldForm;

    use WithFileUploads, WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    public function mount () {
        $this->filterCustomerBookingFieldForm->setFilter();
    }

    public function with(): array
    {
        $filters = [
            'search' => $this->search,
            'date_from' => $this->filterCustomerBookingFieldForm->date_from,
            'date_until' => $this->filterCustomerBookingFieldForm->date_until,
            'day' =>  $this->filterCustomerBookingFieldForm->day,
            'field' => $this->filterCustomerBookingFieldForm->field,
            'status' => ''
        ];


        $fieldAutoCompletes = Field::select('name')->get();
        $fields = Field::filterBookingField($filters)->paginate(50);

        $today = Carbon::parse(todayDate());
        $showedBookingDateUntil = Carbon::parse($this->filterCustomerBookingFieldForm->date_until);

        return [
            'fieldAutoCompletes' => $fieldAutoCompletes,
            'fields' => $fields,
            'totalShowedBookingDays' => Carbon::parse($today)->diffInDays(Carbon::parse($showedBookingDateUntil)),
        ];
    }

    public function searchBookingFields() {
        $this->resetPage();
    }

    public function filterBookingFields() {
        $this->filterCustomerBookingFieldForm->validate();

        $this->dispatch('close-modal', 'filter-field-customer-booking');
    }

    public function resetFilter() {
        $this->filterCustomerBookingFieldForm->setFilter();
    }

    public function redirectToFieldDetail($fieldId) {
        $this->redirectRoute('customer-bookings.field-detail', ['field' => $fieldId]);
    }
}
//

?>

<div class="h-full  rounded-md mx-auto">
    <x-alert name="success-alert"></x-alert>

    <div class="flex items-center mb-4">
        <h1 class="font-bold text-2xl">Bookings</h1>
    </div>

    <x-search model="search" search="searchBookingFields">
        <button @click="$dispatch('open-modal', 'filter-field-customer-booking')" class="flex items-center bg-white shadow-sm py-2 px-4 me-4 border border-slate-500 text-slate-500  rounded-md hover:bg-indigo-800 hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>

            <span>Filter</span>
        </button>
    </x-search>

    <x-forms.booking.customer.filter-field :fieldAutoCompletes="$fieldAutoCompletes"/>

    {{-- list --}}
        {{-- @for($day = 0; $day <= $totalShowedBookingDays; $day++)
            @php
                $rowDate = Carbon::parse(todayDate())->addDays($day);
            @endphp

            <div class="flex gap-4">
                <div class="text-center bg-white rounded-md p-4 mb-2 min-w-40">
                    <h3 class="font-bold text-lg mb-2">{{ $rowDate->format('d M') }}</h3>
                    <h5 class="text-md text-indigo-600">{{ $rowDate->format('l') }}</h5>
                </div>

                <div class="bg-white rounded-md mb-2 p-4 min-w-40">1</div>
                <div class="bg-white rounded-md mb-2 p-4 min-w-40">2</div>
                <div class="bg-white rounded-md mb-2 p-4 min-w-40">3</div>
                <div class="bg-white rounded-md mb-2 p-4 min-w-40">4</div>
                <div class="bg-white rounded-md mb-2 p-4 min-w-40">5</div>
            </div>
        @endfor --}}

    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-2">
        @foreach($fields as $field)
        <div class="w-full sm:max-w-56 overflow-hidden bg-white rounded-lg  hover:cursor-pointer hover:transition-all transition-transform transform hover:scale-105 shadow-lg dark:bg-gray-800"
        wire:key="{{ $field->id }}"
        wire:click="redirectToFieldDetail({{ $field->id }})"
        >
            <img class="object-cover w-full h-48" src="{{ $field->image }}" alt="{{ $field->name }}">
            <div class="px-4 py-2 bg-gray-900">
                <h1 class="text-lg font-bold text-white">{{ $field->name }}</h1>
                <h3 class="text-sm font-bold text-gray-400">{{ $field->start_time . ' - ' . $field->end_time  }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    {{ $fields->withQueryString()->links() }}

</div>