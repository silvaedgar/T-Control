<?php

namespace App\Traits;

trait FiltersTrait
{

    public function dataFilter($filter)
    {
        // el status inicial es Select que dice Seleccione un .....
        $data_filter = ['message' => "Status: Todos", "date_start" =>'',  "date_end" => '', 'status'=>'Select'];
        if (count($filter) > 0) {
            foreach ($filter as $key ) {
                switch ($key[1]) {
                case '=':  // es status
                    $data_filter['status'] = $key[2];
                    $data_filter['message'] = "Status: ".$key[2];
                    break;
                case '>=': // dia de inicio
                    $data_filter['date_start'] = $key[2];
                    $data_filter['message'] .= " Fecha Inicial: ".date('d-m-Y', strtotime($data_filter['date_start']));
                    break;
                case '<=': // dia de inicio
                    $data_filter['date_end'] = $key[2];
                    $data_filter['message'] .= " Fecha Final: ".date('d-m-Y', strtotime($data_filter['date_end']));
                    break;
                }
            }
        }
        return $data_filter;
    }

    public function createFilter($data,$field)
    {
        $filter = [];
        if ($data['status'] != 'Select' && $data['status'] != 'Todos' ) {
            $filtro = ['status','=', $data['status']];
            array_push($filter, $filtro);
        }
        if (!empty($data['startdate'])) {
            $filtro = [$field,'>=',$data['startdate']];
            array_push($filter, $filtro);
        }
        if (!empty($data['enddate'])) {
            $filtro = [$field,'<=',$data['enddate']];
            array_push($filter, $filtro);
        }
        return $filter;
    }

}
