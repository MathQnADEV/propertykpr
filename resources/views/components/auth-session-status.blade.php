@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#333333]']) }}>
        {{ $status }}
    </div>
@endif
