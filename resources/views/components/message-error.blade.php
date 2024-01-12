@props(['hasError', 'message', 'clases' => '', 'color' => 'text-danger'])
{{-- <div> --}}
@if ($hasError)
    <span {{ $attributes->merge(['class' => "$color text-center"]) }}>
        {{ $message }}
    </span>
@endif
{{-- </div> --}}

{{-- <div>
    @if ($hasError)
        <span class="{{ isset($color) ? 'text-ligth' : 'text-danger' }}">{{ $message }}</span>
    @endif
</div> --}}
