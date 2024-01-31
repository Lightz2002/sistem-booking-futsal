@props(['value'])

<div>
    {{ \Carbon\Carbon::parse($value)->format('l, d M Y') }}
</div>
