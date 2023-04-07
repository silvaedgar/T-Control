<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>T-Control (Balance Cliente)</title>
</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-5 col-sm-4">
                                <span style="font-size: large; "> Balance de Movimientos </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <span class="font-weight-bold" style="font-size: 20px">
                                    Cliente: {{ $movements[0]->names }} {{ $config['header']['subTitle2'] }}</span>


                                {{-- <h4 class="d-inline"> {{ $config['header']['subTitle2'] }} </h4> --}}
                            </div>
                            <div class="col-sm-3">
                                {{ $config['data']['calcCoin']->id != $config['data']['baseCoin']->id ? $config['header']['subTitle'] : '' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('shared.table-balance')
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
</body>

</html>
