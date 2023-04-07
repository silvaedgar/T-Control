<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;

class Sale extends Model
{
    use HasFactory;
    // , LogsActivity;

    protected static $logAttributes = ['client_id', 'coin_id', 'rate_exchange', 'sale_date', 'mount', 'paid_mount'];

    protected $fillable = ['user_id', 'client_id', 'coin_id', 'rate_exchange', 'sale_date', 'invoice', 'mount','associated_costs', 'tax_mount', 'conditions', 'observations', 'status'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    public function scopeGetPendings($query)
    {
        // creo no se utiliza las pendientes se obtienen con api fetch
        return $query
            ->where('status', '<>', 'Historico')
            ->where('status', '<>', 'Anulada')
            ->orderBy('sale_date', 'desc')
            ->orderBy('created_at', 'desc');
    }

    public function scopeGetSales($query, $filter = [])
    {
        if (count($filter) == 0) {
            return $query->orderBy('sale_date', 'desc')->orderBy('id', 'desc');
        }
        //Pendiente el detalle de status Pendiente no toma los parcialmente canceladas
        return $query
            ->where($filter)
            ->orderBy('sale_date', 'desc')
            ->orderBy('id', 'desc');
    }
}
