Ojo esto no funciona las variables php definidas aqi no funcionan dentro de los includes
@php
$filter = isset($filter) ? $filter : [];
$status = 'Todos';
$datestart = '';
$dateend = '';
$payment = true;
if (isset($filter)) {
    //existe la variable filter verifica los mismos
    foreach ($filter as $key => $value) {
        switch ($filter[$key][1]) {
            case '=': // es status
                $status = $filter[$key][2];
                break;
            case '>=': // dia de inicio
                $datestart = $filter[$key][2];
                break;
            case '
    <=': // dia de inicio
                $dateend = $filter[$key][2];
                break;
        }
    }
}
@endphp
