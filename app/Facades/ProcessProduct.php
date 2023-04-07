<?php

namespace App\Facades;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\UnitMeasure;
use App\Models\Purchase;
use App\Models\ProductCategory;
use App\Models\Tax;

use App\Traits\CoinTrait;
use App\Traits\ProductTrait;

class ProcessProduct
{
    use CoinTrait, ProductTrait;

    public function getDataSharedProduct($option, $header)
    {
        $data_purchase = $this->generateDataCoin('calc_currency_purchase');
        $data_sale = $this->generateDataCoin('calc_currency_sale');
        if ($option != 'index') {
            $data_sale['rate'] = $data_sale['calc_coin']->id != $data_sale['base_coin']->id ? $data_sale['rate'] : 1 / $data_sale['rate'];
            $data_purchase['rate'] = $data_purchase['calc_coin']->id != $data_purchase['base_coin']->id ? $data_purchase['rate'] : 1 / $data_purchase['rate'];
        }

        $data = ['base_coin' => $data_sale['base_coin'], 'calc_coin' => $data_sale['calc_coin'], 'calc_coin_other' => $data_purchase['calc_coin'], 'rate' => $data_sale['rate'], 'rate_other' => $data_purchase['rate'], 'header' => $header];
        switch ($option) {
            case 'index':
                return DataCommonFacade::index('Product', $data);
                break;
            case 'create':
                return DataCommonFacade::create('Product', $data);
                break;
            case 'edit':
                return DataCommonFacade::edit('Product', $data);
        }
    }

    public function save_image($request)
    {
        if ($image = $request->imagefile) {
            $route_save_img = 'images/data/';
            $img_product = $request->code . '.' . $image->getClientOriginalExtension();
            $image->move($route_save_img, $img_product);
            $img_product = $route_save_img . $img_product;
            return $img_product;
        }
        // return Storage::url($request->file('imagefile')->store('public/images'));
        // las lineas de arriba son de coders freee con el link de storage
        return '';
    }
}
