<h3 class="card-title ">Deuda de Proveedores  al {{ now() }} </h4>
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
         @foreach ($suppliers as $supplier)
                <tr>
                    <td> {{$loop->iteration}} </td>
                    <td style= "text-align: center"> {{ $supplier->name }} </td>
                    <td> {{ $supplier->balance }} $ </td>
                </tr>
                @php
                    $total += $supplier->balance
                @endphp
         @endforeach
    </tbody>
    <br>
    <span> Total Monto Adeudado:  {{ $total }} $ </span>
</table>

