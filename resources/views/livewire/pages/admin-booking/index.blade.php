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
use App\Livewire\Forms\FilterAdminBookingFieldForm;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public FilterAdminBookingFieldForm $filterAdminBookingFieldForm;

    use WithFileUploads, WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'date';
    #[Url(as: 'direction')]
    public $sortDirection = 'asc';
    #[Url(as: 'status')]
    public $status = 'verifying';
    public $additionalWhere = '';

    public function setAdditionalWhere($status) {
        $validatedValue = Carbon::now()->format('Y-m-d H:i');


        if ($status === 'upcoming') {
            $this->additionalWhere = "CONCAT(DATE_FORMAT(allotments.date, '%Y-%m-%d'),' ',DATE_FORMAT(allotments.start_time, '%H:%i')) >= '$validatedValue'";
            $this->filterAdminBookingFieldForm->reset('date_from', 'date_until');
        } else if ($status === 'history') {
            $this->additionalWhere = "CONCAT(DATE_FORMAT(allotments.date, '%Y-%m-%d'),' ',DATE_FORMAT(allotments.start_time, '%H:%i')) < '$validatedValue'";
            $this->filterAdminBookingFieldForm->reset('date_from', 'date_until');
        } else {
            $this->additionalWhere = '';
            $this->filterAdminBookingFieldForm->setFilter();
        }
    }

    public function mount () {
        $this->filterAdminBookingFieldForm->setFilter();
        $this->setAdditionalWhere($this->status);
    }

    public function with(): array
    {
        $filters = [
            'search' => $this->search,
            'date_from' => $this->filterAdminBookingFieldForm->date_from,
            'date_until' => $this->filterAdminBookingFieldForm->date_until,
            'day' =>  $this->filterAdminBookingFieldForm->day,
            'field' => $this->filterAdminBookingFieldForm->field,
            'status' => $this->status
        ];


        $fieldAutoCompletes = Field::select('name')->get();

        $today = Carbon::parse(todayDate());
        $showedBookingDateUntil = Carbon::parse($this->filterAdminBookingFieldForm->date_until);

        $allotments = Allotment::filter($filters)
        ->orderBy($this->sortBy, $this->sortDirection)
        ->orderBy('start_time', 'asc');

        if ($this->additionalWhere) {
            $allotments->whereRaw($this->additionalWhere);
        }
        
        $tableColumns = [
            
            [
                'key' => 'field_name',
                'label' => 'Field',
            ],
            [
                'key' => 'date',
                'label' => 'Date',
                'component' => 'columns.admin-booking.date'
            ],
            [
                'key' => 'start_time',
                'label' => 'Start Time',
                'component' => 'columns.admin-booking.start_time'
            ],
            [
                'key' => 'end_time',
                'label' => 'End Time',
                'component' => 'columns.admin-booking.end_time'
            ],
            [
                'key' => 'user',
                'label' => 'User',
            ],
            [
                'key' => 'action',
                'label' => 'Action',
                'component' => 'columns.admin-booking.action-upcoming'
            ]
        ];

        return [
            'fieldAutoCompletes' => $fieldAutoCompletes,
            'details' => $allotments->paginate(50),
            'detailColumns' => $tableColumns,
            'totalShowedBookingDays' => Carbon::parse($today)->diffInDays(Carbon::parse($showedBookingDateUntil)),
        ];
    }

    public function searchBookingFields() {
        $this->resetPage();
    }

    public function filterBookingFields() {
        $this->filterAdminBookingFieldForm->validate();

        $this->dispatch('close-modal', 'filter-field-admin-booking');
    }

    public function resetFilter() {
        if ($this->status === 'history' || $this->status === 'upcoming') {
            $this->filterAdminBookingFieldForm->reset();
        } else {
            $this->filterAdminBookingFieldForm->setFilter();
        }
    }

    public function filterStatus($status) {
        $this->setAdditionalWhere($status);
        $this->status = $status;
    }

    public function viewBookingDetail($bookingId) {
        $this->redirectRoute('admin-bookings.detail', ['allotment' => $bookingId]);
    }

    public function sort($key) {
        if ($key === 'action') return;

        $this->resetPage();

        if ($this->sortBy === $key) {
            $direction = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            $this->sortDirection = $direction;

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }
}
//

?>

<div class="h-full  rounded-md mx-auto">
    <x-alert name="success-alert"></x-alert>

    <div class="flex items-center mb-4">
        <h1 class="font-bold text-2xl">Bookings</h1>
    </div>

    <x-status-bar :statuses="['verifying', 'upcoming', 'history']" :selectedStatus="$status"></x-status-bar>

    <x-search model="search" search="searchBookingFields">
        <button @click="$dispatch('open-modal', 'filter-field-admin-booking')" class="flex items-center bg-white shadow-sm py-2 px-4 me-4 border border-slate-500 text-slate-500  rounded-md hover:bg-indigo-800 hover:text-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>

            <span>Filter</span>
        </button>
    </x-search>

    <x-forms.booking.admin.filter-field :fieldAutoCompletes="$fieldAutoCompletes"/>

    <x-table :details="$details" :detailColumns="$detailColumns" :sortBy="$sortBy" :sortDirection="$sortDirection"></x-table>

    {{ $details->withQueryString()->links() }}

</div>