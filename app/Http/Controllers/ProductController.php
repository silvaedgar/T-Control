<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tax;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\ProductCategory;

use App\Http\Requests\ProductRequest;

use App\Facades\Config;

use App\Traits\CoinTrait;
use App\Traits\ProductTrait;
use App\Traits\ProductCategoryTrait;
use App\Traits\SharedTrait;

use PDF;

class ProductController extends Controller
{
    use CoinTrait, ProductTrait, ProductCategoryTrait, SharedTrait;

    public function __construct()
    {
        $this->middleware('role');
    }

    public function loadDataCoins($config)
    {
        $rate = $this->getBaseCoinRate($this->getBaseCoin('calc_currency_purchase')->first()->id)->first();
        $config['saleCoin'] = $this->getBaseCoin('calc_currency_sale')->first();
        $config['purchaseCoin'] = $this->getBaseCoin('calc_currency_purchase')->first();
        $config['baseCoin'] = $this->getBaseCoin('base_currency')->first();
        $config['saleCoin']->sale_price = $rate->sale_price;
        $config['purchaseCoin']->purchase_price = $rate->purchase_price;
        $config['header']['title2'] = 'Tasa Venta: ' . $rate->sale_price . ' ' . $config['baseCoin']->symbol;
        $config['header']['subTitle2'] = 'Tasa Compra: ' . $rate->purchase_price . ' ' . $config['baseCoin']->symbol;

        return $config;
    }

    public function index()
    {
        $config = Config::labels('Products', $this->getProducts()->get());
        $config['header']['title'] = 'Listado de Productos';
        $config['data']['coinSale'] = $this->getBaseCoin('calc_currency_sale')->first();
        $config['data']['coinPurchase'] = $this->getBaseCoin('calc_currency_purchase')->first();
        $config['isFormIndex'] = true;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('Products');
        $config['header']['title'] = 'Creando Producto';
        $config = $this->loadDataCoins($config);
        $categories = ProductCategory::GetProductCategories([['activo', '=', 1]])->get();
        $taxes = Tax::GetTaxes([['activo', '=', 1]])->get(); // Ejemplo de LocalScope
        return view('shared.create-edit', compact('config', 'categories', 'taxes'));
    }

    public function store(ProductRequest $request)
    {
        return $this->saveProduct($request);
    }

    public function edit(Product $product)
    {
        $config = Config::labels('Products', $product, true);
        $config['header']['title'] = 'Editando Producto: ' . $product->name;
        $config = $this->loadDataCoins($config);
        $categories = ProductCategory::GetProductCategories([['activo', '=', 1]])->get();
        $config['categories'] = $categories;
        $config['data']['purchases'] = PurchaseDetail::with('Purchase', 'Purchase.Supplier', 'Purchase.Coin')
            ->where('product_id', $product->id)
            ->orderBy(Purchase::select('purchase_date')->whereColumn('purchase_details.purchase_id', 'purchases.id'), 'desc')
            ->get();
        $taxes = Tax::GetTaxes()->get(); // Ejemplo de LocalScope
        return view('shared.create-edit', compact('config', 'categories', 'taxes', 'product'));
    }

    public function update(ProductRequest $request)
    {
        return $this->saveProduct($request);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->activo = !$product->activo;
        $product->save();
        $message = $product->status ? 'elimino' : 'restauro';
        return redirect()
            ->route('products.index')
            ->with('message_status', "Se $message el producto $product->name con exito.");
    }

    public function printProducts()
    {
        $products = $this->getProducts()
            ->orderby('name')
            ->get();
        $pdf = PDF::loadView('products.report', ['products' => $products]);
        return $pdf->stream();
    }
}
