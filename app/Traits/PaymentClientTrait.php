<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PaymentClient;
use App\Models\Tax;

trait PaymentClientTrait
{
    public function getDisplayInfoPaymentClient($update=false, $payment=null,$filter=[])
    {
        // return
        // [
        //     'layout' => ['activePage' => 'sales', 'titlePage' => 'Modulo de Pagos de Clientes'],
        //     'header' => ['title' => '', 'subTitle' => '', 'title2' => '', 'subTitle2' => '', 'form' => '$payment-clients.form', 'messageSave' => 'Grabar Pago'],
        //     'var_header' => ["labelCaption" => "Cliente", 'name' => 'client_id', 'date' => 'payment_date', 'controller' => 'Pago'],
        //     'buttons' => [['message' => 'Reporte', 'url' => '', 'icon' => 'print', 'target' => true],
        //                   ['message' => 'Crear Pago', 'url' => route('paymentclients.create'), 'icon' => 'person_add', 'target' => false]],
        //     'table' => ['header' => ['Item', 'Cliente', 'Fecha', 'Monto', 'Forma de Pago', 'Acción'], 'include' => 'payment-clients.table'],
        //     'router' => ['routePost' => 'paymentclients.store', 'routeIndex' => 'paymentclients.index',
        //             'routeFilter' => 'paymentclients.index','item' => '$payment'],
        //     'data' => ['collection' => $payment, 'update' => $update, 'data_filter' => $this->data_filter($filter),
        //             'status' =>['Todos','Procesado','Anulado','Historico']],
        //     'links_create' => [
        //             ['message' => 'Venta', 'url' => route('sales.create')],
        //             ['message' => 'Compra', 'url' => route('purchases.create')],
        //             ['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')]],
        //     'links_header' => ['message' => 'Atras', 'url' => url()->previous()],
        //     'index' => false,
        //     'cols' => 3,
        //     'include' => ['header' => 'shared.form-header-payment', 'detail' => 'shared.form-details-payment'],
        // ];
    }

}
