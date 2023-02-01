<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tax;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\ProductCategory;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


use App\Facades\DataCommonFacade;
use App\Facades\ProductFacade;

use App\Traits\GetDataCommonTrait;
use App\Traits\ProductTrait;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;


use PDF;

class ProductController extends Controller
{
    use GetDataCommonTrait, ProductTrait;

    public function __construct() {
        $this->middleware('role');
    }

    public function index()
    {
        $products = $this->GetProducts()->orderBy('name')->get();
        $data_common =  ProductFacade::getDataSharedProduct('index','Listado de Productos');
        return view('products.index',compact('products','data_common'));
    }

    public function create()
    {
        $data = ProductFacade::getDataProduct(0);
        $data_common = ProductFacade::getDataSharedProduct('create','Crear Productos');
        return view('products.create',compact('data','data_common'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $image =  ProductFacade::save_image($request);
            $product = Product::create($request->all());
            if ($image != '') {
                $product->image_file = $image;
                $product->save();
            }
            return redirect()->route('products.index')->with('message_status',"Creado con exito producto $request->name ");
        } catch (\Throwable $th) {
            return redirect()->route('products.index')->with('message_status',"Producto $request->name NO pudo Crearse. Verifique por favor. Error: ".$th->getMessage());
            // echo $th->getMessage();
        }
    }

    public function edit(Product $product)
    {
        $data = ProductFacade::getDataProduct($product->id);
        $data_common = ProductFacade::getDataSharedProduct('edit', 'Editar Producto');
        return view('products.edit', compact('data', 'data_common'));
    }

    public function update(UpdateProductRequest $request)
    {
        $product = Product::find($request->id);
        try {
            $image = ProductFacade::save_image($request);
            $product->update($request->all());
            if ($image != '')
                $product->image_file = $image;
            $product->save();
            return redirect()->route('products.index')->with('message_status',"Datos del Producto $request->name. ACTUALIZADO CON EXITO. ");
        } catch (\Throwable $th) {
            return redirect()->route('products.index')->with('message_status',"Datos del Producto $request->name. NO se pudieron ACTUALIZAR. Verifique por favor. Error: ".$th->getMessage());
            // echo $th->getMessage();
        }

    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->status = $product->status == 'Inactivo' ? 'Activo' : 'Inactivo';
        $product->save();
        $message = $product->status == 'Inactivo' ? 'elimino' : 'restauro';
        return redirect()->route('products.index')->with("message_status","Se $message el producto $product->name con exito.");
    }

    public function list_print() {

        $products = Product::orderBy('name')->where('status','Activo')->get();
        $pdf = PDF::loadView('products.report',['products' => $products]);
        return $pdf->stream();
    }

}
