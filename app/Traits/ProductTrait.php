<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Purchase;
use App\Models\Tax;

use App\Http\Requests\ProductRequest;

trait ProductTrait
{
    public function getProducts($filter = [])
    {
        return Product::with('ProductCategory', 'ProductCategory.ProductGroup', 'tax')->where($filter);
    }

    // public function getDataProduct($product_id = 0)
    // {
    //     //     // las dos sentencias hacen uso de LocalScope en el Modelo
    //     $data['categories'] = ProductCategory::LoadCategoryAndGroup()
    //         ->orderBy('description')
    //         ->get();
    //     $data['taxes'] = Tax::GetTaxes()->get(); // Ejemplo de LocalScope
    //     if ($product_id > 0) {
    //         $data['product'] = $this->GetProducts()
    //             ->where('id', $product_id)
    //             ->orderBy('name')
    //             ->first();
    //         $data['purchases'] = Purchase::with(['Coin', 'Supplier', 'PurchaseDetails'])
    //             ->whereHas('PurchaseDetails', function ($query) use ($product_id) {
    //                 $query->where('product_id', $product_id);
    //             })
    //             ->orderBy('purchase_date', 'desc')
    //             ->get();
    //     }
    //     return $data;
    // }

    public function saveProduct(ProductRequest $request)
    {
        $response = $this->saveModel('Product', $request);
        if ($response['success']) {
            // ojo aun que hacer que se borre el archivo existente. Y mas importante que se quite si existia
            if ($request->hasFile('image_file')) {
                $response['collection']->image_file = $this->saveImage($request);
                $response['collection']->save();
            }
            $response['message'] = "Producto $request->name " . ($request->id == 0 ? 'creado' : 'actualizado') . ' con exito';
        }
        return redirect()
            ->route('products.index')
            ->with('message_status', $response['message']);
    }

    public function saveImage(ProductRequest $request)
    {
        $image = $request->file('image_file');
        $destinationPath = 'images/product/';
        $nameImgProduct = $request->code . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $nameImgProduct);
        $nameImgProduct = $destinationPath . $nameImgProduct;
        return $nameImgProduct;
        // return Storage::url($request->file('imagefile')->store('public/images'));
        // las lineas de arriba son de coders freee con el link de storage
    }
}
