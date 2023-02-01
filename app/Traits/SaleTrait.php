<?php

namespace App\Traits;

use App\Models\Sale;


trait SaleTrait {

    public function getSale($with,$filter) {
        return Sale::with($with);
        if (!is_array($filter))
            return Sale::with($with)->where('id',$id);
        if (count($filter) ==0)
            return Sale::orderBy('sale_date','desc')->orderBy('created_at','desc');
        else
            return Sale::where($filter)->orderBy('sale_date','desc')->orderBy('created_at','desc');
    }

}
