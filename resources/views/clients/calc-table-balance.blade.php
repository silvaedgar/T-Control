@php
if ($movement->count_in_bs == 'N') {
    print number_format($movement->mount_balance, 2) . '(' . $data_common['calc_coin_symbol'] . ')';
    $mount = $movement->mount_balance;
    if ($movement->coin_id != $data_common['calc_coin_id']) {
        if ($movement->coin_id == $data_common['base_coin_id']) {
            print ' - ' . number_format($movement->mount, 2) . '(' . $data_common['base_coin_symbol'] . ')';
        } else {
            print ' - ' . number_format($movement->mount * $movement->rate_exchange, 2) . '(' . $data_common['base_coin_symbol'] . ')';
        }
    }
    print number_format($movement->mount, 2) . "($movement->symbol)";
    $mount = $movement->mount;
} else {
    if ($movement->coin_id == $data_common['base_coin_id']) {
        $mount = $movement->mount;
        print number_format($movement->mount, 2) . '(' . $data_common['base_coin_symbol'] . ')';
    } else {
        print number_format($movement->mount_balance, 2) . '(' . $movement->symbol . ')';
        print ' - ' . number_format($movement->mount * $movement->rate_exchange, 2) . '(' . $data_common['base_coin_symbol'] . ')';
        $mount = $movement->mount * $movement->rate_exchange;
    }
} // Caso de Paola y Livia
@endphp
