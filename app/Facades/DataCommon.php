<?php

namespace App\Facades;

use App\Facades\DataControllerFacade;

class DataCommon {

    public function generate_data($controller,$data,$option) {
        $sub_header = '';
        $message_title  = '';
        $message_subtitle = '';
        $cols = 2;
        $link_print_details = '';
        $links_header =  ['message' => '','url' => ''];
        switch ($controller) {
            case "Supplier" :
                switch ($option) {
                    case 'Balance' :
                        $sub_header = 'Moneda de Calculo: '.$data['calc_coin']['symbol'];
                        $message_title = 'Proveedor: '.$data['supplier'];
                        $message_subtitle = $data['message_balance'];
                        $cols = 3;
                    case 'Create' :
                    case 'Edit' :
                        $links_header =  ['message' => 'Atras','url' => url()->previous()];
                    default : break;
                }
                break;
            case "Client" :
                switch ($option) {
                    case "Balance" :
                        $sub_header = 'Moneda de Calculo: '.$data['calc_coin']['symbol'].
                                        ' - Tasa: '.number_format($data['rate'],2);
                        $message_title = 'Cliente: '.$data['client'];
                        $message_subtitle = $data['message_balance'];
                        // $link_print_details = "route ('clients.printbalance',[$movements[0]->client_id,$mensaje])";
                        $cols = 3;
                    case 'Create' :
                    case 'Edit' :  $links_header =  ['message' => 'Atras','url' => url()->previous()];
                    default: break;
                }
                break;
            case "Product" :
                if ($option != 'Index') {
                    $links_header =  ['message' => 'Ir al Listado','url' => route('products.index')];
                    $message_title = 'Tasa Conversion Venta:  '.number_format($data['rate'],2);
                    $message_subtitle = 'Tasa Conversion Compra:  '.number_format($data['rate_other'],2);
                    $cols = 3;
                }
                break;
            case "PaymentClient" :
            case "PaymentSupplier" :
            case "Sale" :
            case "Purchase" :
                switch ($option) {
                    case "Create" :
                        $links_header = ['message' => 'Atras','url' => url()->previous()];
                        $sub_header = "Moneda de Calculo: ".$data['calc_coin']['symbol'];
                        if ($controller == 'PaymentClient' || $controller == 'Sale')
                            $sub_header .= ' - Tasa :'.number_format($data['rate'],2);
                        $message_title =  '0.00 '.$data['calc_coin']['symbol'];
                        $message_subtitle = ($data['calc_coin']['symbol'] != $data['base_coin']['symbol'] ? '0.00 '.$data['base_coin']['symbol'] : '');
                        $cols = 3;
                        break;
                    case "Edit" :
                        $links_header = ['message' => 'Atras','url' => url()->previous()];
                        $sub_header = "Facturada en ".$data['invoice']->coin->symbol;
                        switch ($controller) {
                            case "PaymentClient":
                            case "Sale":
                                $sub_header .= ' - Tasa :'.number_format($data['invoice']['rate_exchange'],2);
                                break;
                            case "PaymentSupplier":
                            case "Purchase":
                                if ($data['calc_coin']['id'] != $data['invoice']['coin_id'])
                                    $sub_header .= ' - Tasa :'.number_format($data['invoice']['rate_exchange'],2);

                        }
                        $message_title = "Monto: ".$data['invoice']['mount']." ".$data['invoice']->coin->symbol;
                        if ($data['mount_other'] > 0) {
                            if ($controller == 'Purchase' || $controller == "PaymentSupplier")
                                $message_subtitle =  $data['calc_coin']['id'] != $data['invoice']['coin_id'] ?
                                    ("Monto en ".($data['invoice']['coin_id'] == $data['calc_coin']['id'] ?
                                        $data['base_coin']['symbol'] : $data['calc_coin']['symbol']).': '.number_format($data['mount_other'],2)) :
                                            '';
                            else
                                $message_subtitle =  "Monto en ".($data['invoice']['coin_id'] == $data['calc_coin']['id'] ?
                                        $data['base_coin']['symbol'] : $data['calc_coin']['symbol']).': '.number_format($data['mount_other'],2);
                        }
                        $cols = 3;
                    default: break;
                }
            default : break;
        }
        // return ['header' => $data['header'], 'sub_header' => $sub_header, 'message_title' => $message_title,
        //     'message_subtitle' => $message_subtitle, 'cols' => $cols,
        //     'links_header' => $links_header, 'controller' => $controller,
        //     'base_coin' => !isset($data['base_coin']) ? null : $data['base_coin'],
        //     'calc_coin' => !isset($data['calc_coin']) ? null : $data['calc_coin'],
        //     'rate' => !isset($data['rate']) ? '' : $data['rate']];

        return ['header' => $data['header'], 'sub_header' => $sub_header, 'message_title' => $message_title,
            'message_subtitle' => $message_subtitle, 'cols' => $cols,
            'links_header' => $links_header, 'controller' => $controller,
            'base_coin_id' => !isset($data['base_coin']) ? 0 : $data['base_coin']['id'],
            'base_coin_symbol'=> !isset($data['base_coin']) ? '' : $data['base_coin']['symbol'],
            'calc_coin_id' => !isset($data['calc_coin']) ? 0 : $data['calc_coin']['id'],
            'calc_coin_symbol' => !isset($data['calc_coin']) ? '' : $data['calc_coin']['symbol'],
            'rate' => !isset($data['rate']) ? '' : $data['rate']];
    }

    public function index($controller,$data) {
        $data_controller = DataControllerFacade::index($controller,$data);
        return array_merge($this->generate_data($controller,$data,'Index'),$data_controller);
    }

    public function create($controller,$data=[]) {
        $data_controller = DataControllerFacade::create($controller,$data);
        return array_merge($this->generate_data($controller,$data,'Create'),$data_controller);
    }

    public function edit($controller,$data=[]) {
        $data_controller = DataControllerFacade::create($controller,$data);
        return array_merge($this->generate_data($controller,$data,'Edit'),$data_controller);
    }

    public function balance($controller,$data) {
        $data_controller = DataControllerFacade::create($controller);
        return array_merge($this->generate_data($controller,$data,'Balance'),$data_controller);;
    }
}
