   <tr>
       <td> {{ $loop->iteration }} </td>
       <td> {{ date('d-m-Y H:i:s', strtotime($item->date_value)) }} </td>
       <td> {{ $item->BaseCurrency->name }} ({{ $item->BaseCurrency->symbol }})
           ----
           {{ $item->Coin->name }} ({{ $item->Coin->symbol }})
           <x-badge-null :item="!$item->activo" :message="$item->activo ? 'Tasa Actual' : ''"></x-badge-null>

       </td>
       <td> 1{{ $item->BaseCurrency->symbol }} = {{ $item->Coin->symbol }}
           {{ number_format($item->purchase_price, 2) }} </td>
       <td> 1{{ $item->BaseCurrency->symbol }} = {{ $item->Coin->symbol }}
           {{ number_format($item->sale_price, 2) }} </td>
   </tr>
