<h3 class="card-title ">Listado de Productos del Sistema </h4>
    <table style=" font-size:11px; border: 2rem; ">
        <thead class=" text-primary">
            <th>Item</th>
            <th>Codigo</th>
            <th style="width: 8%">Nombre</th>
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
                    <td> {{ $product->productCategory->productGroup->description }} </td>
                    <td> {{ $product->productCategory->description }} </td>
                    <td> {{ number_format($product->sale_price, 2) }} </td>
                    <td> {{ number_format($product->cost_price, 2) }}
                        {{ !$product->activo ? 'Producto Eliminado' : '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
