<h3 class="card-title ">Listado de Productos del Sistema </h4>
<table style="border: 2rem">
    <thead class=" text-primary">
        <th>Item</th>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Grupo</th>
        <th>Categoria</th>
        <th>Precio Vta</th>
        <th>Precio Costo</th>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td> {{ $loop->iteration }} </td>
            <td> {{ $product->code }} </td>
            <td> {{ $product->name }} </td>
            <td> {{ $product->description }} </td>
            <td> {{ $product->category }} </td>
            <td> {{ $product->sale_price }} </td>
            <td> {{ $product->cost_price }} </td>
        </tr>
        @endforeach
    </tbody>
</table>

