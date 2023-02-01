<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable=['user_id','document_type','document','name','contact','address','phone',
     'balance','status'];

    public function Purchase()
    {
        return $this->hasMany(Purchase::class, 'supplier_id', 'id');
    }

    public function PaymentSupplier()
    {
        return $this->hasMany(PaymentSupplier::class, 'supplier_id', 'id');
    }

    public function scopeGetSuppliers($query,$filter='') {
        if ($filter == '') {
            return $query->where('status','Activo')->orderBy('name');
        }
        return $query->where('status',$filter)->orderBy('name');
    }

    public function scopeBalance($query,$operator,$mount) {
        return $query->where('balance',$operator,$mount);
    }

//  Funcion que busca las compras del proveedor llamada desde el controlador y desde la API
    public function scopeGetDataPurchases($query,$id,$calc_coin_id,$base_coin_id,$filter='') {
        if ($calc_coin_id == $base_coin_id)
            $column_balance_purchases = "(CASE WHEN purchases.coin_id = ".$base_coin_id." THEN mount ELSE mount * rate_exchange END) as mount_balance";
        else
            $column_balance_purchases = "(CASE WHEN purchases.coin_id = ".$base_coin_id." THEN mount / rate_exchange ELSE mount END) as mount_balance ";

        return  Supplier::select('purchase_date as date','mount','balance','rate_exchange','purchases.id',
            'purchases.created_at as create','suppliers.name','suppliers.id as supplier_id',
            'coins.symbol','coins.id as coin_id')->selectRaw(" 'N' as count_in_bs")
            ->selectRaw("'Compras' as type")->selectRaw($column_balance_purchases)
            ->join('purchases','suppliers.id','purchases.supplier_id')->join('coins','purchases.coin_id','coins.id')
            ->where('supplier_id',$id);
    }

//  Funcion que busca los pagos  del proveedor
    public function scopeGetDataPayments($query,$id,$calc_coin_id,$base_coin_id) {
        if ($calc_coin_id == $base_coin_id)
            $column_balance_payments = "(CASE WHEN payment_suppliers.coin_id = ".$base_coin_id." THEN mount ELSE mount * rate_exchange END) as mount_balance ";
        else
            $column_balance_payments = "(CASE WHEN payment_suppliers.coin_id = ".$base_coin_id." THEN mount / rate_exchange ELSE mount  END) as mount_balance ";

        return  Supplier::select('payment_date as date','mount','balance','rate_exchange','payment_suppliers.id',
            'payment_suppliers.created_at as create','suppliers.name','suppliers.id as supplier_id',
            'coins.symbol','coins.id as coin_id')->selectRaw("'N' as count_in_bs")
            ->selectRaw("'Pagos' as type")->selectRaw($column_balance_payments)
            ->join('payment_suppliers','suppliers.id','payment_suppliers.supplier_id')
            ->join('coins','payment_suppliers.coin_id','coins.id')->where('supplier_id',$id);
    }
}
