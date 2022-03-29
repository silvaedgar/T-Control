<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\ProductCategory;
use App\Models\Tax;
use App\Models\UnitMeasure;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function product_price($id) {
        return  DB::table('products')->select('products.*','percent')
            ->join('taxes','taxes.id','=','products.tax_id')->where('products.id',$id)->first();
    }

    public function index()
    {
        $products = DB::table('products')->select('products.*','product_categories.description as category','product_groups.description')
                    ->join('product_categories','products.category_id','=','product_categories.id')
                    ->join('product_groups','product_categories.group_id','=','product_groups.id')
                    ->get();
        return view('products.index',compact('products'));
    }

    public function create()
    {
        $groups = ProductGroup::where('status','=','Activo')->get();
        $taxes = Tax::where('status','=','Activo')->get();
        $unitmeasures = UnitMeasure::where('status','=','Activo')->get();
        return view('products.create',compact('groups','taxes','unitmeasures'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->all());
        return redirect()->route('products.index')->with('status',"Ok_Creación del producto $request->name");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
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
        $groups = ProductGroup::where('status','Activo')->get();
        $taxes = Tax::where('status','Activo')->get();

        return view('products.edit',compact('product','groups','taxes','categories','group_id'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $request->validate ([
            'code' => "required|unique:products,code,$request->id",
            'name' => "required|unique:products,name,$request->id"
        ]);
        $product1 = Product::find($request->id);
        $product1->update($request->all());
        return redirect()->route('products.index')->with('status',"Ok_Actualización del producto $request->name");
    }

    public function destroy(Product $product)
    {
        //
    }
}
