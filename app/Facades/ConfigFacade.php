<?php

namespace App\Facades;

use App\Traits\FiltersTrait;

class ConfigFacade
{
    use FiltersTrait;

    public function labels($component, $collection = null, $update = false, $filter = [])
    {
        $label = 'label' . $component;
        $config = array_merge($this->$label($update), $this->labelShared($collection, $update, $filter));
        return $config;
    }

    public function labelShared($collection, $update, $filter)
    {
        return [
            'data' => ['collection' => $collection, 'update' => $update, 'dataFilter' => $this->dataFilter($filter)],
            'isFormIndex' => false,
            'cols' => 2,
            'hasFilter' => false,
        ];
    }

    public function labelProductGroups($update = false)
    {
        return [
            'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo Grupos de Productos'],
            'header' => ['title' => '', 'subTitle' => 'Detalle del Grupo', 'form' => 'maintenance.product-groups.form'],
            'buttons' => [['message' => 'Crear Grupo', 'url' => route('maintenance.productgroups.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Descripción', 'Acción'], 'include' => 'maintenance.product-groups.table'],
            'router' => ['routePost' => 'maintenance.productgroups.' . ($update ? 'update' : 'store'), 'routeIndex' => 'maintenance.productgroups.index', 'item' => '$productgroup'],
            'links_create' => [],
            'links_header' => [],
        ];
    }
    public function labelProductCategories($update = false)
    {
        return [
            'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo Categoria de Productos'],
            'header' => ['title' => '', 'subTitle' => 'Detalle de la Categoria', 'form' => 'maintenance.product-categories.form'],
            'buttons' => [['message' => 'Crear Categoria', 'url' => route('maintenance.productcategories.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Descripción', 'Grupo', 'Acción'], 'include' => 'maintenance.product-categories.table'],
            'router' => ['routePost' => 'maintenance.productcategories.' . ($update ? 'update' : 'store'), 'routeIndex' => 'maintenance.productcategories.index', 'item' => '$productcategory'],
            'links_create' => [],
            'links_header' => [],
        ];
    }
    public function labelPaymentForms($update = false)
    {
        return [
            'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo Formas de Pago'],
            'header' => ['title' => '', 'subTitle' => 'Detalle de la Forma de Pago', 'form' => 'maintenance.payment-forms.form'],
            'buttons' => [['message' => 'Crear Forma de Pago', 'url' => route('maintenance.paymentforms.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Descripción', 'Grupo', 'Acción'], 'include' => 'maintenance.payment-forms.table'],
            'router' => ['routePost' => 'maintenance.paymentforms.' . ($update ? 'update' : 'store'), 'routeIndex' => 'maintenance.paymentforms.index', 'item' => '$paymentform'],
            'links_create' => [],
            'links_header' => [],
        ];
    }

    public function labelCoins($update = false)
    {
        return [
            'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo de Monedas'],
            'header' => ['title' => '', 'subTitle' => 'Detalle de la Moneda', 'form' => 'maintenance.coins.form'],
            'buttons' => [['message' => 'Crear Moneda', 'url' => url('maintenance.coins.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['', 'Item', 'Nombre', 'Simbolo', 'Acción'], 'include' => 'maintenance.coins.table'],
            'router' => ['routePost' => 'maintenance.coins.' . ($update ? 'update' : 'store'), 'routeIndex' => 'maintenance.coins.index', 'item' => '$coin'],
            'links_create' => [],
            'links_header' => [],
        ];
    }

    public function labelTaxes($update = false)
    {
        return [
            'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo de Impuestos'],
            'header' => ['title' => '', 'subTitle' => 'Detalle del Impuesto', 'form' => 'maintenance.taxes.form'],
            'buttons' => [['message' => 'Crear Impuesto', 'url' => route('maintenance.taxes.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', '% Impuesto', 'Detalle', 'Acción'], 'include' => 'maintenance.taxes.table'],
            'router' => ['routePost' => 'maintenance.taxes.' . ($update ? 'update' : 'store'), 'routeIndex' => 'maintenance.taxes.index', 'item' => '$tax'],
            'links_create' => [],
            'links_header' => [],
        ];
    }

    public function labelProducts($update = false)
    {
        return [
            'layout' => ['activePage' => 'store', 'titlePage' => 'Modulo de Productos'],
            'header' => ['title' => '', 'subTitle' => '', 'title2' => 'Tasa Venta ', 'subTitle2' => 'Tasa Compra ', 'form' => 'products.form'],
            'buttons' => [['message' => 'Listado', 'url' => route('products.list_print'), 'icon' => 'print', 'target' => true], ['message' => 'Crear Producto', 'url' => route('products.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Código', 'Nombre', 'Grupo', 'Categoria', 'Precio Vta', 'Precio Compra', 'Acción'], 'include' => 'products.table'],
            'router' => ['routePost' => 'products.' . ($update ? 'update' : 'store'), 'routeIndex' => 'products.index', 'item' => '$product'],
            'links_create' => [['message' => 'Compra', 'url' => route('purchases.create')], ['message' => 'Venta', 'url' => route('sales.create')], ['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')], ['message' => 'Pago de Cliente', 'url' => route('paymentclients.create')]],
            'links_header' => ['message' => 'Listado de Productos', 'url' => route('products.index')],
        ];
    }

    public function labelClients($update = false)
    {
        return [
            'layout' => ['activePage' => 'sales', 'titlePage' => 'Modulo de Clientes'],
            'header' => ['title' => '', 'subTitle' => 'Detalle del Cliente', 'form' => 'clients.form'],
            'buttons' => [['message' => 'Listado', 'url' => '', 'icon' => 'print', 'target' => false], ['message' => 'Deudores', 'url' => route('clients.list_debtor'), 'icon' => 'print', 'target' => true], ['message' => 'Crear Cliente', 'url' => route('clients.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'CI/RIF', 'Nombre', 'Saldo', 'Acción'], 'include' => 'clients.table'],
            'router' => ['routePost' => 'clients.' . ($update ? 'update' : 'store'), 'routeIndex' => 'clients.index', 'item' => '$client', 'routePrintBalance' => 'clients.print_balance'],
            'links_create' => [['message' => 'Crear Factura', 'url' => route('sales.create')], ['message' => 'Generar Pago de Cliente', 'url' => route('paymentclients.create')]],
            'links_header' => ['message' => 'Atras', 'url' => url()->previous()],
        ];
    }

    public function labelSuppliers($update = false)
    {
        return [
            'layout' => ['activePage' => 'purchases', 'titlePage' => 'Modulo de Proveedores'],
            'header' => ['title' => '', 'subTitle' => 'Detalle del Proveedor', 'form' => 'suppliers.form'],
            'buttons' => [['message' => 'Listado', 'url' => '', 'icon' => 'print', 'target' => false], ['message' => 'Deuda', 'url' => route('suppliers.listcreditors'), 'icon' => 'print', 'target' => true], ['message' => 'Crear Proveedor', 'url' => route('suppliers.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'CI/RIF', 'Nombre', 'Contacto', 'Saldo', 'Acción'], 'include' => 'suppliers.table'],
            'router' => ['routePost' => 'suppliers.' . ($update ? 'update' : 'store'), 'routeIndex' => 'suppliers.index', 'item' => '$supplier'],
            'links_create' => [['message' => 'Crear Factura', 'url' => route('purchases.create')], ['message' => 'Generar Pago a Proveedor', 'url' => route('paymentsuppliers.create')]],
            'links_header' => ['message' => 'Atras', 'url' => url()->previous()],
        ];
    }

    public function labelPaymentClients($update = false)
    {
        return [
            'layout' => ['activePage' => 'sales', 'titlePage' => 'Modulo de Pagos de Clientes'],
            'header' => ['title' => 'Pagos de Clientes', 'subTitle' => '', 'title2' => '', 'subTitle2' => '', 'form' => '$payment-clients.form', 'messageSave' => 'Grabar Pago'],
            'var_header' => ['labelCaption' => 'Cliente', 'id' => 'client_id', 'date' => 'payment_date', 'controller' => 'Pago'],
            'buttons' => [['message' => 'Reporte', 'url' => route('paymentclients.index', ['option' => 'Report', 'status' => 'Pendiente']), 'icon' => 'print', 'target' => true], ['message' => 'Crear Pago', 'url' => route('paymentclients.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Cliente', 'Fecha', 'Monto', 'Forma de Pago', 'Acción'], 'include' => 'payment-clients.table'],
            'router' => ['routePost' => 'paymentclients.store', 'routeIndex' => 'paymentclients.index', 'routeFilter' => 'paymentclients.index', 'item' => '$payment'],
            'links_create' => [['message' => 'Venta', 'url' => route('sales.create')], ['message' => 'Compra', 'url' => route('purchases.create')], ['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')]],
            'links_header' => ['message' => 'Atras', 'url' => url()->previous()],
            'include' => ['header' => 'shared.form-header-payment', 'detail' => 'shared.form-details-payment'],
        ];
    }

    public function labelPaymentSuppliers($update = false)
    {
        return [
            'layout' => ['activePage' => 'purchases', 'titlePage' => 'Modulo de Pagos a Proveedores'],
            'header' => ['title' => 'Pagos a Proveedores', 'subTitle' => '', 'title2' => '', 'subTitle2' => '', 'form' => 'payment-suppliers.form', 'messageSave' => 'Grabar Pago'],
            'var_header' => ['labelCaption' => 'Proveedor', 'id' => 'supplier_id', 'date' => 'payment_date', 'controller' => 'Pago'],
            'buttons' => [['message' => 'Reporte', 'url' => route('paymentsuppliers.index', ['option' => 'Report', 'status' => 'Todos']), 'icon' => 'print', 'target' => true], ['message' => 'Crear Pago', 'url' => route('paymentsuppliers.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Proveedor', 'Fecha', 'Monto', 'Forma de Pago', 'Acción'], 'include' => 'payment-suppliers.table'],
            'router' => ['routePost' => 'paymentsuppliers.store', 'routeIndex' => 'paymentsuppliers.index', 'routeFilter' => 'paymentsuppliers.index', 'item' => '$payment'],
            'links_create' => [['message' => 'Venta', 'url' => route('sales.create')], ['message' => 'Compra', 'url' => route('purchases.create')], ['message' => 'Pago de Cliente', 'url' => route('paymentclients.create')]],
            'links_header' => ['message' => 'Atras', 'url' => url()->previous()],
            'include' => ['header' => 'shared.form-header-payment', 'detail' => 'shared.form-details-payment'],
        ];
    }

    public function labelPurchases($update = false)
    {
        return [
            'layout' => ['activePage' => 'purchases', 'titlePage' => 'Modulo de Facturas de Compra'],
            'header' => ['title' => 'Facturas de Compra', 'subTitle' => '', 'title2' => '', 'subTitle2' => '', 'form' => 'purchases.form', 'messageSave' => 'Grabar Factura'],
            'var_header' => ['labelCaption' => 'Proveedor', 'name' => 'supplier_id', 'date' => 'purchase_date', 'controller' => 'Compra'],
            'buttons' => [['message' => 'Reporte', 'url' => route('purchases.index', ['option' => 'Report', 'status' => 'Todos']), 'icon' => 'print', 'target' => true], ['message' => 'Crear Factura', 'url' => route('purchases.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Proveedor', 'Fecha', 'Monto', 'Saldo Pendiente', 'Acción'], 'include' => 'purchases.table'],
            'router' => ['routePost' => 'purchases.store', 'routeIndex' => 'purchases.index', 'routeFilter' => 'purchases.index', 'item' => '$purchase'],
            'links_create' => [['message' => 'Venta', 'url' => route('sales.create')], ['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')], ['message' => 'Pago de Cliente', 'url' => route('paymentclients.create')]],
            'links_header' => ['message' => 'Atras', 'url' => url()->previous()],
            'include' => ['header' => 'shared.form-header-invoice', 'detail' => 'shared.form-details-invoice'],
        ];
    }

    public function labelSales($update = false)
    {
        return [
            'layout' => ['activePage' => 'sales', 'titlePage' => 'Modulo de Facturas de Venta'],
            'header' => ['title' => 'Facturas de Venta', 'subTitle' => '', 'title2' => '', 'subTitle2' => '', 'form' => 'sales.form', 'messageSave' => 'Grabar Factura'],
            'var_header' => ['labelCaption' => 'Cliente', 'name' => 'client_id', 'date' => 'sale_date', 'controller' => 'Venta'],
            'buttons' => [['message' => 'Reporte', 'url' => route('sales.index', ['option' => 'Report', 'status' => 'Todos']), 'icon' => 'print', 'target' => true], ['message' => 'Crear Factura', 'url' => route('sales.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Cliente', 'Fecha', 'Monto', 'Saldo Pendiente', 'Acción'], 'include' => 'sales.table'],
            'router' => ['routePost' => 'sales.' . ($update ? 'show' : 'store'), 'routeIndex' => 'sales.index', 'routeFilter' => 'sales.index', 'item' => '$sale'],
            'links_create' => [['message' => 'Compra', 'url' => route('purchases.create')], ['message' => 'Pago a Proveedor', 'url' => route('paymentsuppliers.create')], ['message' => 'Pago de Cliente', 'url' => route('paymentclients.create')]],
            'links_header' => ['message' => 'Atras', 'url' => url()->previous()],
            'include' => ['header' => 'shared.form-header-invoice', 'detail' => 'shared.form-details-invoice'],
        ];
    }

    public function labelCurrencyValue($update = false)
    {
        return [
            'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo de Monedas'],
            'header' => ['title' => '', 'subTitle' => 'Detalle de la Moneda', 'form' => 'maintenance.currency-values.form'],
            'buttons' => [['message' => 'Crear Valor Compra-Venta', 'url' => route('maintenance.currencyvalues.create'), 'icon' => 'person_add', 'target' => false]],
            'table' => ['header' => ['Item', 'Fecha', 'Moneda Relacionadas', 'Precio Compra', 'Precio Venta'], 'include' => 'maintenance.currency-values.table'],
            'router' => ['routePost' => 'maintenance.currencyvalues.' . ($update ? 'update' : 'store'), 'routeIndex' => 'maintenance.currencyvalues.index', 'item' => '$currencyvalue'],
            'links_create' => [],
            'links_header' => [],
        ];
    }
}
