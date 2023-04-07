<div class="table-responsive">
    <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
        <thead class="text-primary">
            @foreach ($config['table']['header'] as $item)
                <th> {{ $item }}</th>
            @endforeach
        </thead>
        <tbody>
            @forelse ($config['data']['collection'] as $item)
                @include($config['table']['include'])
            @empty
                <tr>
                    <td colspan="{{ count($config['table']['header']) }}" class="font-weight-bold"> No existen registros
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- @if (count($collection) > 0 && $collection->hasPages())
        {{ $collection->links() }}
    @endif --}}
</div>
