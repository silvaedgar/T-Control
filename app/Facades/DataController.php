<?php

namespace App\Facades;


class DataController {

    public function create($controller,$data=[]) {
        switch ($controller) {
            case "Supplier" :
                return ['links_create' => [['message' => 'Crear Factura', 'url' =>route('purchases.create')],
                        ['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')]],
                        'buttons' => []];
            case "Client" :
                return ['links_create' => [['message' => 'Crear Factura', 'url' =>route('sales.create')],
                        ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]],
                        'buttons' => []];
            case "Product" :                
                return ['links_create' => [['message' => 'Pago Proveedor', 'url' =>route('paymentsuppliers.create')],
                        ['message' => 'Compra', 'url' =>route('purchases.create')],
                        ['message' => 'Pago Cliente', 'url' =>route('paymentclients.create')],
                        ['message' => 'Venta', 'url' =>route('sales.create')]],
                            'purchase_coin_symbol' => $data['calc_coin_other']['symbol'],
                            'purchase_rate' => $data['rate_other'], 'sale_rate' => $data['rate'],
                            'sale_coin_symbol' => $data['calc_coin']['symbol']];
            case "Purchase" :
                return ['links_create' => [['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')],
                        ['message' => 'Venta', 'url' => route('sales.create')],
                        ['message' => 'Pago a Cliente', 'url' => route('paymentclients.create')]]];
            case "Sale" :                        
                return ['links_create' =>  [['message' => 'Pago a Cliente', 'url' => route('paymentclients.create')],
                            ['message' => 'Compra', 'url' => route('purchases.create')],
                            ['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')]]];
            case "PaymentClient" :
                return ['links_create' =>  [['message' => 'Crear Factura', 'url' => route('sales.create')],
                            ['message' => 'Compra', 'url' => route('purchases.create')],
                            ['message' => 'Pago Proveedor', 'url' => route('paymentsuppliers.create')]]];
            case "PaymentSupplier" :
                return ['links_create' =>  [['message' => 'Crear Factura', 'url' => route('purchases.create')],
                            ['message' => 'Venta', 'url' => route('sales.create')],
                            ['message' => 'Pago Cliente', 'url' => route('paymentclients.create')]]];
        }
    }

    public function index($controller,$data=[]) {
        switch ($controller) {
            case "Supplier" :
                return array_merge($this->create($controller),
                    ['buttons' => [['message' => 'Listado','icon' => 'print','url' => '','target'=>false],
                        ['message' => 'Deuda','icon' => 'print','url' => route('suppliers.listcreditors'),'target'=>true],
                        ['message' => 'Crear','icon' => 'person_add','url' => route('suppliers.create'),'target' => false]]]);
            case "Client" :
                return array_merge($this->create($controller),
                    ['buttons' => [['message' => 'Listado','icon' => 'print','url' => '','target'=>false],
                        ['message' => 'Deudores','icon' => 'print','url' => route('clients.list_debtor'),'target'=>true],
                        ['message' => 'Crear','icon' => 'person_add','url' => route('clients.create'),'target' => false]]]);
            case "Product" :
                return array_merge($this->create($controller,$data),['buttons' => [['message' => 'Generar PDF', 'icon' => 'print','url' => route('products.list_print'),'target' => true],
                        ['message' => 'Crear Producto','icon' => 'person_add','url' => route('products.create'),'target' => false]]]);
            case "PaymentClient" :   // los buttons de estos 4 estan en el form del filter en la vista
            case "PaymentSupplier" :
            case "Purchase" :
            case "Sale" :
                return array_merge($this->create($controller),['data_filter' => $data['data_filter']]);
        }
    }
}
