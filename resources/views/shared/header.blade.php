{{-- para form de purchase funciona los col --}}
<div class="row">
    @if ($config['cols'] == 2)
        {{-- Si entra aqui es el header de los form que no son compras o pagos --}}
        <div
            class="col-sm-{{ !$config['isFormIndex'] ? 8 : 4 }} col-md-{{ !$config['isFormIndex'] ? 7 : 5 }} col-lg-{{ !$config['isFormIndex'] ? 8 : 4 }}">
            <h4 class="card-title"> {{ $config['header']['title'] }} </h4>
            @foreach ($config['links_create'] as $key => $link)
                <a style="color: #99ffff; font-size: 12px" href="{{ $link['url'] }}">
                    {{ $key > 0 ? ' | ' : '' }} {{ $link['message'] }} </a>
            @endforeach
        </div>
        <div
            class="col-sm-{{ !$config['isFormIndex'] ? 4 : 8 }} col-md-{{ !$config['isFormIndex'] ? 5 : 7 }} col-lg-{{ !$config['isFormIndex'] ? 4 : 8 }} text-end">
            @if (!$config['isFormIndex'])
                <a class="text-white" href="{{ $config['links_header']['url'] }}">
                    {{ $config['links_header']['message'] }} </a>
            @else
                @foreach ($config['buttons'] as $key => $button)
                    <a href="{{ $button['url'] }}" target="{{ $button['target'] ? '_blank' : '' }}">
                        <button class="btn btn-info"> {{ $button['message'] }}
                            <i class="material-icons" aria-hidden="true">{{ $button['icon'] }}</i>
                        </button> </a>
                @endforeach
            @endif
        </div>
    @else
        <div class="col-sm-4 col-lg-4">
            <span style="font-size:17px"> {{ $config['header']['title'] }} </span> <br />
            <span style="font-size: 13px"> {{ $config['header']['subTitle'] }} </span>
            @if ($config['header']['subTitle'] == '')
                @foreach ($config['links_create'] as $key => $link)
                    {{-- {{ dd($link) }} --}}
                    <a style="color: #99ffff; font-size: 12px" href="{{ $link['url'] }}">
                        {{ $key > 0 ? ' | ' : '' }} {{ $link['message'] }} </a>
                @endforeach
            @endif
        </div>
        <div class="col-sm-5 col-md-4 col-lg-4">
            <span id="message_title" style="font-size:15.5px"> {{ $config['header']['title2'] }}</span>
            @if (isset($config['router']['routePrintBalance']))
                <a href="{{ route($config['router']['routePrintBalance'], $movements[0]->client_id) }}" target="_blank"
                    class="text-dark">
                    <button type="button" class="bg-info" data-toggle="tooltip" data-placement="top"
                        title="Imprimir Balance">
                        <i class="fa fa-print" aria-hidden="true"></i> </button> </a>
            @endif
            <br />
            <span id="message_subtitle" style="font-size: 14px"> {{ $config['header']['subTitle2'] }}</span>
        </div>
        <div class="col-sm-3 col-md-4 col-lg-4 text-center" style="font-size:17px">
            <a class="text-white" href="{{ $config['links_header']['url'] }}">
                {{ $config['links_header']['message'] }} </a> <br />
            @if ($config['isFormIndex'])
                @foreach ($config['links_create'] as $key => $link)
                    <a class="float-md-end" style="color: #99ffff; font-size: 12px" href="{{ $link['url'] }}">
                        {{ $link['message'] }} {{ $key > 0 ? ' | ' : '' }}</a>
                @endforeach
            @endif

        </div>
    @endif
</div>
