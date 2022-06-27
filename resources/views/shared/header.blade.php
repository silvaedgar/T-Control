{{-- para form de purchase funciona los col --}}
<div class="row">
    @if ($data_common['cols'] == 2)
        {{-- Si entra aqui es el header de los form que no son compras o pagos --}}
        <div class="col-sm-2 col-md-5 col-xl-6  align-middle">
            {{-- {{ dd($data_common) }} --}}
            <h4 class="card-title "> {{ $data_common['header'] }} </h4>
            @foreach ($data_common['links_create'] as $key => $link)
                <a style="color: #99ffff; font-size: 12px" href="{{ $link['url'] }}">
                    {{ $key > 0 ? ' | ' : '' }} {{ $link['message'] }} </a>
            @endforeach
        </div>
        <div class="col-sm-8 col-md-6 col-xl-6 justify-end">
            <a class="text-white" href="{{ $data_common['links_header']['url'] }}">
                {{ $data_common['links_header']['message'] }} </a>
            {{-- <div class="col-sm-6 col-md-5 col-xl-3  justify-end"> --}}
            @foreach ($data_common['buttons'] as $key => $button)
                <a href="{{ $button['url'] }}" target="{{ $button['target'] ? '_blank' : '' }}">
                    <button class="btn btn-info"> {{ $button['message'] }}
                        <i class="material-icons" aria-hidden="true">{{ $button['icon'] }}</i>
                    </button> </a>
            @endforeach
        </div>
    @else
        <div class="col-sm-4 col-lg-4 ">
            <span style="font-size:17px"> {{ $data_common['header'] }} </span> <br />
            <span style="font-size: 13px"> {{ $data_common['sub_header'] }} </span>
            @if ($data_common['sub_header'] == '')
                @foreach ($data_common['links_create'] as $key => $link)
                    {{-- {{ dd($link) }} --}}
                    <a style="color: #99ffff; font-size: 12px" href="{{ $link['url'] }}">
                        {{ $key > 0 ? ' | ' : '' }} {{ $link['message'] }} </a>
                @endforeach
            @endif
        </div>
        <div class="col-sm-5 col-lg-5">
            <span id="message_title" style="font-size:17px"> {{ $data_common['message_title'] }}
            </span><br />
            <span id="message_subtitle" style="font-size: 14px"> {{ $data_common['message_subtitle'] }}</span>
        </div>
        <div class="col-sm-3 col-md-3 col-lg-3" style="font-size:17px">
            <a class="text-white" href="{{ $data_common['links_header']['url'] }}">
                {{ $data_common['links_header']['message'] }} </a> <br />
            @if ($data_common['sub_header'] != '')
                @foreach ($data_common['links_create'] as $key => $link)
                    {{-- {{ dd($link) }} --}}
                    <a style="color: #99ffff; font-size: 12px" href="{{ $link['url'] }}">
                        {{ $key > 0 ? ' | ' : '' }} {{ $link['message'] }} </a>
                @endforeach
            @endif

        </div>
    @endif
</div>
