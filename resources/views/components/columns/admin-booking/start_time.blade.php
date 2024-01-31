@props(['value'])

<div>
    {{ \Carbon\Carbon::parse($value)->format('H:i') }}
</div>
