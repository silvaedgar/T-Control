<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable= ['user_id','supplier_id','coin_id','rate_exchange','purchase_date','invoice','mount',
        'tax_mount','conditions','observations','status'];

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function PurchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    public function Coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    public function scopeGetPurchases($query,$filter=[]) {
        if (count($filter) == 0)
            return $query->orderBy('purchase_date','desc')->orderBy('id','desc');
        return $query->where($filter)->orderBy('purchase_date','desc')->orderBy('id','desc');
    }


}
