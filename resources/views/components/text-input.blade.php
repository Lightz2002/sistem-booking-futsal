@props(['checked' => false, 'readonly' => false, 'type' => 'text', 'model' => ''])

@php
    $inputClass = 'shadow-sm border-gray-300  rounded-md';
    if ($readonly) {
        $inputClass .= ' focus:!border-0 focus:!ring-0 active:!outline-none bg-gray-200 text-gray-500';
    } else {
        $inputClass .= ' focus:border-indigo-500 focus:ring-indigo-500';
    }
@endphp

@if ($type === 'textarea')
    <textarea {{ $model ? 'wire:model.blur=' . $model : '' }}
    type="{{ $type }}" {{ $attributes->except('type')->merge(['class' => $inputClass, 'readonly' => $readonly]) }}>
        {{ $slot }}
    </textarea>
@elseif ($type === 'radio')
    <input {{ $model ? 'wire:model.blur=' . $model : '' }} type="{{ $type }}" {{ $attributes->merge(['class' => $inputClass, 'type' => 'radio', 'readonly' => $readonly, 'checked' => $checked]) }}>
@else
    <input 
    {{ $model ? 'wire:model.blur=' . $model : '' }} 
    type="{{ $type }}" {{ $attributes->merge(['class' => $inputClass, 'type' => 'text', 'readonly' => $readonly]) }}>
@endif
