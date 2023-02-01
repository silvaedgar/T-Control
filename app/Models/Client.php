<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable=['user_id','document_type','document','names','address'];

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'client_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function UserClient()
    {
        return $this->hasMany(UserClient::class, 'client_id', 'id');
    }

    public function scopeGetClients($query,$all_clients=false,$filter='') {
        if ($all_clients)
            return $query->orderBy('names');
        if ($filter == '')
            return $query->where('status','Activo')->orderBy('names');
        return $query->where('status',$filter)->orderBy('names');
    }

//  Funcion que busca las compras del cliente
    public function scopeGetDataSales($query,$id,$calc_coin_id,$base_coin_id) {
        if ($calc_coin_id == $base_coin_id) {
            $column_balance_sales = "(CASE WHEN sales.coin_id = ".$base_coin_id." THEN mount ELSE mount * rate_exchange END) as mount_balance";
        }
        else {
            $column_balance_sales = "(CASE WHEN sales.coin_id = ".$base_coin_id." THEN mount / rate_exchange ELSE mount END) as mount_balance ";
        }
        return  Client::select('sale_date as date','sales.id','sales.created_at as create','mount',
            'rate_exchange', 'balance','clients.id as client_id', 'clients.count_in_bs','clients.names',
            'coins.symbol','coins.id as coin_id')->selectRaw("'Compras' as type")->selectRaw($column_balance_sales)
            ->join('sales','clients.id','sales.client_id')->join('coins','sales.coin_id','coins.id')
            ->where('client_id',$id);
    }

    public function scopeGetDataPayments($query,$id,$calc_coin_id,$base_coin_id) {
        if($calc_coin_id == $base_coin_id) {
            $column_balance_payments = "(CASE WHEN payment_clients.coin_id = ".$base_coin_id." THEN mount ELSE mount * rate_exchange END) as mount_balance ";
        }
        else { // ojo el id en una conversion
            $column_balance_payments = "(CASE WHEN payment_clients.coin_id = ".$base_coin_id." THEN mount / rate_exchange ELSE mount  END) as mount_balance ";
        }
        return  Client::select('payment_date as date','payment_clients.id','payment_clients.created_at as create',
            'mount','rate_exchange', 'balance','clients.id as client_id', 'clients.count_in_bs','clients.names',
            'coins.symbol','coins.id as coin_id')->selectRaw("'Pagos' as type")->selectRaw($column_balance_payments)
            ->join('payment_clients','clients.id','payment_clients.client_id')
            ->join('coins','payment_clients.coin_id','coins.id')->where('client_id',$id);
    }
}
