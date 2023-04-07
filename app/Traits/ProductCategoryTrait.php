<?php

namespace App\Traits;

use App\Models\ProductCategory;
use App\Models\ProductGroup;

use App\Http\Requests\StoreProductCategoryRequest;

trait ProductCategoryTrait
{
    // public function getProductCategories($filter = [])
    // {
    //     return ProductCategory::with('productGroup')
    //         ->where($filter)
    //         ->orderBy('description')
    //         ->orderBy(ProductGroup::select('description')->whereColumn('product_groups.id', 'product_categories.group_id'));
    // }

    public function saveProductCategory(StoreProductCategoryRequest $request)
    {
        $response = $this->saveModel('ProductCategory', $request);
        if ($response['success']) {
            $response['message'] = "Categoria de Grupo $request->description " . ($request->id == 0 ? 'creado' : 'actualizado') . ' con exito';
        }
        return $response;
    }
}
