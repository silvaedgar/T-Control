<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductCategory;
use App\Models\Tax;
use App\Models\Coin;
use App\Models\UnitMeasure;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use PDF;

class ProductController extends Controller
{
    public function product_price($id) {
        return  DB::table('products')->select('products.*','percent')
            ->join('taxes','taxes.id','products.tax_id')->where('products.id',$id)->first();
    }

    public function index()
    {
        $symbolcoin = Coin::where([['calc_currency_purchase','S'],['status','Activo']])
            ->orwhere([['calc_currency_sale','S'],['status','Activo']])
            ->orderBy('calc_currency_purchase')->get();
        $products = DB::table('products')->select('products.*','product_categories.description as category','product_groups.description')
                    ->join('product_categories','products.category_id','=','product_categories.id')
                    ->join('product_groups','product_categories.group_id','=','product_groups.id')
                    ->orderby('name')->get();
        return view('products.index',compact('products','symbolcoin'));
    }

    public function create()
    {
        $groups = ProductGroup::where('status','Activo')->orderBy('description')->get();
        $taxes = Tax::where('status','Activo')->get();
        $unitmeasures = UnitMeasure::where('status','Activo')->get();
        return view('products.create',compact('groups','taxes','unitmeasures'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->all());
        return redirect()->route('products.index')->with('status',"Ok_Creación del producto $request->name");
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {

        $product = Product::find($product->id);
        $group_id = DB::table('products')->select('product_groups.id as id')
            ->join('product_categories','products.category_id', 'product_categories.id')
            ->join('product_groups','product_categories.group_id', 'product_groups.id')
            ->where('products.id', '=',$product->id)->get();
        $category_tmp = DB::table('products')->select('product_groups.id as group_id')
                    ->join('product_categories','products.category_id','=','product_categories.id')
                    ->join('product_groups','product_categories.group_id','=','product_groups.id')
                    ->where('products.id','=',$product->id)
                    ->first();
        $categories =  ProductCategory::where('group_id',$category_tmp->group_id)->get();  // esta es una API en productgroup controller hay que llamar la api
        $groups = ProductGroup::where('status','Activo')->orderby('description')->get();
        $taxes = Tax::where('status','Activo')->get();

        return view('products.edit',compact('product','groups','taxes','categories','group_id'));
    }

    public function update(UpdateProductRequest $request)
    {
        $request->validate ([
            'code' => "required|string|min:5|unique:products,code,$request->id"
        ]);
        $product = Product::find($request->id);
        $product->update($request->all());
        return redirect()->route('products.index')->with('status',"Ok_Actualización del producto $request->name");
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->status = 'Inactivo';
        // $product->save();

        return redirect()->route('products.index')->with("status","Ok_Se elimino el producto $product->names con exito. ACTIVAR SAVE");

        //
    }

    public function listprint() {

        $products = Product::orderBy('name')->where('status','Activo')->get();
        $pdf = PDF::loadView('products.report',['products' => $products]);
        // return view('products.report',compact('products'));
        // $pdf = PDF::loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }

}
