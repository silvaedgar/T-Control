<div class="row">
    @if ($config['cols'] == 2)

        {{-- Si entra aqui es el header de los form que no son compras o pagos --}}
        <div class="col-sm-3 col-md-5 col-lg-{{ !$config['isFormIndex'] ? 8 : 4 }}">
            <h4 class="card-title"> {{ $config['header']['title'] }} </h4>
            @foreach ($config['links_create'] as $key => $link)
                <a style="color: #99ffff; font-size: 12px" href="{{ $link['url'] }}">
                    {{ $key > 0 ? ' | ' : '' }} {{ $link['message'] }} </a>
            @endforeach
        </div>
        <div class="col-sm-8 col-md-6 col-lg-{{ !$config['isFormIndex'] ? 4 : 8 }}" style="margin-top: -10px">
            @if (!$config['isFormIndex'])
                <a class="float-end text-white" href="{{ $config['links_header']['url'] }}">
                    {{ $config['links_header']['message'] }} </a>
            @else
                @foreach ($config['buttons'] as $key => $button)
                    <a class="float-end" href="{{ $button['url'] }}" target="{{ $button['target'] ? '_blank' : '' }}">
                        <button class="btn btn-info me-2"> {{ $button['message'] }}
                            <i class="material-icons" aria-hidden="true">{{ $button['icon'] }}</i>
                        </button> </a>
                @endforeach
            @endif
        </div>
    @else
        <div class="col-sm-4">
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
        <div class="col-sm-5 col-md-4">
            <span id="message_title" style="font-size:15.5px"> {{ $config['header']['title2'] }}</span>
            <br />
            <span id="message_subtitle" style="font-size: 14px"> {{ $config['header']['subTitle2'] }}</span>
            @if (isset($config['link_print_detail']))
                <a href="{{ $config['link_print_detail'] }}" target="_blank" class="text-dark"> <button type="button"
                        class="bg-info" data-toggle="tooltip" data-placement="top" title="Imprimir Balance">
                        <i class="fa fa-print" aria-hidden="true"></i> </a>
            @endif
        </div>
        <div class="col-sm-3 col-md-4" style="font-size:17px">
            <div class="row me-2">
                <div class="col-sm-12 text-center">
                    <a class="text-white" href="{{ $config['links_header']['url'] }}">
                        {{ $config['links_header']['message'] }} </a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-end">
                    @if (!isset($config['header']['title3']))
                        @foreach ($config['links_create'] as $key => $link)
                            <a style="color: #99ffff; font-size: 12px" href="{{ $link['url'] }}">
                                {{ $key > 0 ? ' | ' : '' }} {{ $link['message'] }} </a>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    @endif
</div>
