<span class="{{ !$item ? 'badge rounded-pill bg-warning text-dark float-middle ' : '' }}">
    {{ !$item && $message == '' ? config('constants.MESSAGE_CANCELED') : '' }}
    {{ $message }} </span>
</span>
