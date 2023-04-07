<div id="panelButtons">
    <a href="{{ route($url . '.edit', $item) }}">
        <button class="btn-info" data-bs-toggle="tooltip" title="Editar {{ $messageToolTip }}">
            <i class="fa fa-edit"></i>
        </button>
    </a>

    <form action="{{ route($url . '.destroy', $item) }}" method="post" class="d-inline delete-item" id="formDelete">
        @csrf
        @method('delete')
        <button class="btn-danger delete-item" data-bs-toggle="tooltip" data-bs-name="{{ $item->description }}"
            data-bs-id="{{ $item->id }}" data-name="{{ $item->description }}"
            title="Eliminar {{ $messageToolTip }} {{ $item->description }}">
            <i class="fa fa-trash-o" aria-hidden="true"></i></button>
    </form>

    @if (isset($otherButtons))
        @foreach ($otherButtons as $button)
            <a href="{{ route($url . '.' . $button['url'], $item) }}">
                <button class="btn-primary" data-bs-toggle="tooltip" title="Ver Detalle">
                    <i class="{{ $button['icon'] }}" aria-hidden="true"></i>
                </button> </a>
        @endforeach
    @endif
</div>
