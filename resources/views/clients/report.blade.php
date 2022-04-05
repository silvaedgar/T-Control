<h3 class="card-title ">Deuda de Clientes  al {{ now() }} </h4>
    <table class="table-bordered">
        <thead class=" text-primary">
            <th>Renglon</th>
            <th>Nombre</th>
            <th>Saldo</th>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
             @foreach ($clients as $client)
                    <tr>
                        <td> {{$loop->iteration}} </td>
                        <td style= "text-align: center"> {{ $client->names }} </td>
                        <td> {{ $client->balance }}BsD </td>
                    </tr>
                    @php
                        $total += $client->balance;
                    @endphp
             @endforeach
        </tbody>
        <br>
        <span> Monto Total deudores: {{ $total }}</span>
    </table>
