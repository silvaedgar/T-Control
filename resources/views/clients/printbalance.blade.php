<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>T-Control (Balance Cliente)</title>
</head>
<body>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
              <div class="row">
                <div class="col-md-5 col-sm-4">
                    <span style="font-size: large; "> Balance de Movimientos </span>
                </div>
                <div class="col-sm-5">
                    <h5> Cliente:  {{ $movements[0]->names }} </h5>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="clients" style="width: 100%">
                <thead class=" text-primary">
                    <th>Renglon</th>
                    <th>Fecha</th>
                    <th>Compra</th>
                    <th>Abono</th>
                    <th>Saldo</th>
            </thead>
                <tbody>
                    @php
                        $balance = $movements[0]->balance;
                    @endphp
                    @if ($movements[0]->type != 'Balance')
                        @foreach ($movements as $movement)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ date("d-m-Y",strtotime($movement->date))  }} </td>
                            <td> {{ ($movement->type == 'Compras' ? number_format($movement->mountbalance,2).' ('.$movement->symbol.')' : '') }}
                                {{ ($movement->symbol == '$' && $movement->type == 'Compras' ? '    ('.(number_format($movement->mount,2)).' $)' : '') }} </td>
                            </td>
                            <td> {{ ($movement->type == 'Pagos' ?  number_format($movement->mountbalance,2) : '') }}
                                {{ ($movement->symbol == '$' && $movement->type == 'Pagos' ? '    ('.(number_format($movement->mount,2))." $movement->symbol )" : '') }} </td>
                            <td>
                                {{ number_format($balance,2) }} (BsD)
                                @php
                                    $balance = ($movement->type == 'Pagos' ? $balance + $movement->mountbalance : $balance - $movement->mountbalance);
                                @endphp
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    {{-- Fila del Balance Inicial en caso de ser diferemte de 0 --}}
                    @if ($balance != 0)
                        <tr>
                            <td> {{ (count($movements) == 1 ? 1 : count($movements)  + 1) }} </td>
                            <td>  Balance Inicial </td>
                            <td>  {{ $movements[0]->name }} </td>
                            <td>  </td>
                            <td> {{ number_format($balance,2) }} (BsD) </td>
                            <td> </td>
                        </tr>
                    @endif

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>

</html>
