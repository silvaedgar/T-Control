<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'supplier_id', 'coin_id', 'rate_exchange', 'purchase_date', 'invoice', 'mount','associated_costs', 'tax_mount', 'conditions', 'observations', 'status'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }

    public function user()
    {
        return $this->belongsTo(Coin::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function scopeGetPurchases($query, $filter = [])
    {
        if (count($filter) == 0) {
            return $query->orderBy('purchase_date', 'desc')->orderBy('id', 'desc');
        }
        return $query
            ->where($filter)
            ->orderBy('purchase_date', 'desc')
            ->orderBy('id', 'desc');
    }
}
