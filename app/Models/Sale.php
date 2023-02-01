<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class Sale extends Model
{
    use HasFactory, LogsActivity;

    protected  static $logAttributes = ['client_id', 'coin_id','rate_exchange','sale_date','mount','paid_mount'];

    protected $fillable= ['user_id','client_id','coin_id','rate_exchange','sale_date','invoice','mount',
    'tax_mount','conditions','observations','status'];

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function SaleDetails()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id', 'id');
    }

    public function Coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    public function scopeGetPendings($query) {   // creo no se utiliza las pendientes se obtienen con api fetch
        return $query->where('status','<>','Historico')->where('status','<>','Anulada')
            ->orderBy('sale_date','desc')->orderBy('created_at','desc');
    }

    public function scopeGetSales($query,$filter=[]) {
        if (count($filter) == 0)
            return $query->orderBy('sale_date','desc')->orderBy('id','desc');
        return $query->where($filter)->orderBy('sale_date','desc')->orderBy('id','desc');
    }


}
