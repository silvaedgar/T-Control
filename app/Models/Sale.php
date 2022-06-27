<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable= ['user_id','client_id','coin_id','rate_exchange','sale_date','invoice','mount',
    'tax_mount','conditions','observations','status'];

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id', 'id');
    }

    public function Coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    public function scopeGetPendings($query) {
        return $query->where('status','<>','Historico')->where('status','<>','Anulada')
            ->orderBy('sale_date','desc')->orderBy('created_at','desc');
    }

}
