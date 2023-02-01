<?php

namespace App\Traits;

use App\Models\Product;

trait ProductTrait {

    public function GetProducts() {
        return Product::with('ProductCategory','ProductCategory.ProductGroup');
    }
}
