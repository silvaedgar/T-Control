<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductCategory;
use App\Models\Tax;
use App\Models\Coin;
use App\Models\UnitMeasure;
use App\Traits\GetDataCommonTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use PDF;

class ProductController extends Controller
{
    use GetDataCommonTrait;

    public function __construct() {
        $this->middleware('role');
    }

    public function index()
    {
        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin_purchase = $this->get_base_coin('calc_currency_purchase')->first();
        $rate =  $this->get_base_coin_rate($calc_coin_purchase->id);
        $data_purchase = $rate->first();  // obtiene la tasa de cambio de compras

        $calc_coin_sale = $this->get_base_coin('calc_currency_sale')->first();
        $rate =  $this->get_base_coin_rate($calc_coin_sale->id);
        $data_sale = $rate->first();  // obtiene la tasa de cambio de compras

        $products = Product::select('products.*','product_categories.description as category','product_groups.description')
                    ->join('product_categories','products.category_id','=','product_categories.id')
                    ->join('product_groups','product_categories.group_id','=','product_groups.id')
                    ->orderby('name')->get();

        $data_common = ['base_coin_symbol' => $base_coin->symbol,
            'sale_symbol' => $calc_coin_sale->symbol, 'sale_rate' => $data_sale->sale_price,
            'purchase_symbol' => $calc_coin_purchase->symbol, 'purchase_rate' => $data_purchase->purchase_price,
            'header' => 'Listado de Productos',
            'buttons' =>[['message' => 'Generar PDF', 'icon' => 'print','url' => route('products.listprint'),'target' => true],
                ['message' => 'Crear Producto','icon' => 'person_add','url' => route('products.create'),'target' => false]],
            'links_create' => [['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')],
                        ['message' => 'Crear Factura de Compra', 'url' =>route('purchases.create')],
                        ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')],
                        ['message' => 'Crear Factura de Venta', 'url' =>route('sales.create')]],
            'data_filter' => '', 'cols' => 2,
            'links_header' => ['message' => '','url' => ''],
            'controller' => 'Product'];
        return view('products.index',compact('products','data_common'));
    }

    public function create()
    {
        $unitmeasures = UnitMeasure::where('status','Activo')->get();
        $groups = ProductGroup::where('status','Activo')->orderby('description')->get();
        $taxes = Tax::where('status','Activo')->get();

        $base_coin = $this->get_base_coin('base_currency')->first();

        $calc_coin_purchase = $this->get_base_coin('calc_currency_purchase')->first();
        $rate =  $this->get_base_coin_rate($calc_coin_purchase->id);
        $data_purchase = $rate->first();  // obtiene la tasa de cambio de compras

        $calc_coin_sale = $this->get_base_coin('calc_currency_sale')->first();
        $rate =  $this->get_base_coin_rate($calc_coin_sale->id);
        $data_sale = $rate->first();  // obtiene la tasa de cambio de compras

        $rate_sale = ($calc_coin_sale->id != $base_coin->id ? $data_sale->sale_price : 1 / $data_sale->sale_price);
        $rate_purchase = ($calc_coin_purchase->id != $base_coin->id ? $data_purchase->purchase_price : 1 / $data_purchase->purchase_price);

        $data_common = ['header' => 'Creando Producto', 'sub_header' => '', 'base_coin_id' => $base_coin->id,
            'base_coin_symbol'=> $base_coin->symbol,
            'sale_coin_symbol' => $calc_coin_sale->symbol, 'purchase_coin_symbol' => $calc_coin_purchase->symbol,
            'sale_rate' => $rate_sale, 'purchase_rate' => $rate_purchase,
            'message_title' => 'Tasa Conversion Venta:  '.number_format($rate_sale,3),
            'message_subtitle' => 'Tasa Conversion Compra:  '.number_format($rate_purchase,3),
            'controller' => 'Product', 'buttons' => [], 'cols' => 3,
            'message_title' => '',
            'message_subtitle' => '',
            'links_header' => ['message' => 'Ir al Listado','url' => route('products.index')],
            'links_create' => [['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')],
                        ['message' => 'Crear Factura de Compra', 'url' =>route('purchases.create')],
                        ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')],
                        ['message' => 'Crear Factura de Venta', 'url' =>route('sales.create')]]];

        return view('products.create',compact('groups','taxes','unitmeasures','data_common'));
    }

    public function store(StoreProductRequest $request)
    {
        $imagen = $this->getimage($request);
        Product::create($request->all());
        return redirect()->route('products.index')->with('status',"Ok_Creación del producto $request->name");
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $group_id = DB::table('products')->select('product_groups.id as id')
            ->join('product_categories','products.category_id', 'product_categories.id')
            ->join('product_groups','product_categories.group_id', 'product_groups.id')
            ->where('products.id', $product->id)->get();
        $category_tmp = DB::table('products')->select('product_groups.id as group_id')
                    ->join('product_categories','products.category_id','=','product_categories.id')
                    ->join('product_groups','product_categories.group_id','=','product_groups.id')
                    ->where('products.id','=',$product->id)
                    ->first();

        $categories =  ProductCategory::where('group_id',$category_tmp->group_id)->get();  // esta es una API en productgroup controller hay que llamar la api
        $groups = ProductGroup::where('status','Activo')->orderby('description')->get();
        $taxes = Tax::where('status','Activo')->get();

        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin_purchase = $this->get_base_coin('calc_currency_purchase')->first();
        $rate =  $this->get_base_coin_rate($calc_coin_purchase->id);
        $data_purchase = $rate->first();  // obtiene la tasa de cambio de compras

        $calc_coin_sale = $this->get_base_coin('calc_currency_sale')->first();
        $rate =  $this->get_base_coin_rate($calc_coin_sale->id);
        $data_sale = $rate->first();  // obtiene la tasa de cambio de compras


        $rate_sale = ($calc_coin_sale->id != $base_coin->id ? $data_sale->sale_price : 1 / $data_sale->sale_price);
        $rate_purchase = ($calc_coin_purchase->id != $base_coin->id ? $data_purchase->purchase_price : 1 / $data_purchase->purchase_price);

        $data_common = ['header' => 'Editando Producto', 'base_coin_id' => $base_coin->id,
            'base_coin_symbol'=> $base_coin->symbol,
            'sale_coin_symbol' => $calc_coin_sale->symbol, 'sale_rate' => $rate_sale,
            'purchase_coin_symbol' => $calc_coin_purchase->symbol, 'purchase_rate' => $rate_purchase ,
            'message_title' => 'Tasa Conversion Venta:  '.number_format($rate_sale,3),
            'message_subtitle' => 'Tasa Conversion Compra:  '.number_format($rate_purchase,3),
            'controller' => 'Client', 'buttons' => [], 'cols' => 2,
            'links_header' => ['message' => 'Ir al Listado','url' => route('products.index')],
            'links_create' => [['message' => 'Generar Pago a Proveedor', 'url' =>route('paymentsuppliers.create')],
                        ['message' => 'Crear Factura de Compra', 'url' =>route('purchases.create')],
                        ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')],
                        ['message' => 'Crear Factura de Venta', 'url' =>route('sales.create')]]];

        return view('products.edit',compact('product','groups','taxes','categories','group_id','data_common'));
    }

    public function update(UpdateProductRequest $request)
    {
        $imagen = $this->getimage($request);
        $product = Product::find($request->id);
        $product->update($request->all());
        return redirect()->route('products.index')->with('status',"Ok_Actualización del producto $request->name");
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->status = $product->status == 'Inactivo' ? 'Activo' : 'Inactivo';
        $product->save();
        $message = $product->status == 'Inactivo' ? 'elimino' : 'restauro';
        return redirect()->route('products.index')->with("status","Ok_Se $message el producto $product->name con exito.");
    }

    public function listprint() {

        $products = Product::orderBy('name')->where('status','Activo')->get();
        $pdf = PDF::loadView('products.report',['products' => $products]);
        return $pdf->stream();
    }

    public function getimage($request) {
        if ($request->imagefile  != '')  // Variable imagen es la que se guarda en la BD
            return Storage::url($request->file('imagefile')->store('public\images'));
        return '';
    }
}
