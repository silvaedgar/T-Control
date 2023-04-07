<div>
    @if ($hasError)
        <span class="{{ isset($color) ? 'text-ligth' : 'text-danger' }}">{{ $message }}</span>
    @endif
</div>
