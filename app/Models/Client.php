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







            // if ($symbol == "BsD" || $symbol == '') {
        //     $column_balance_sales = "(CASE WHEN sales.coin_id = 1 THEN mount ELSE mount * rate_exchange END) as mountbalance";
        //     $column_balance_payments = "(CASE WHEN payment_clients.coin_id = 1 THEN mount ELSE mount * rate_exchange END) as mountbalance ";
        // }
        // else { // ojo el id en una conversion
        //     $column_balance_sales = "(CASE WHEN sales.coin_id = 1 THEN mount / rate_exchange ELSE mount * rate_exchange END) as mountbalance ";
        //     $column_balance_payments = "(CASE WHEN payment_clients.coin_id = 1 THEN mount / rate_exchange ELSE mount * rate_exchange END) as mountbalance ";
        // }
        // if ($mensaje == 'Mostrar Historicos') {
        //     $saleclient = Client::select('sale_date as date','symbol','mount','clients.names','clients.count_in_bs',
        //             'balance','sales.id','clients.id as client_id','sales.created_at as create')
        //         ->selectRaw("'Compras' as type")->selectRaw($column_balance_sales)
        //         ->join('sales','clients.id','sales.client_id')->join('coins','sales.coin_id','coins.id')
        //         ->where('client_id',$id)->where('sales.status','<>','Historico')->where('sales.status','<>','Anulada');
        //     $movements =  Client::select('payment_date as date','symbol','mount','clients.names','clients.count_in_bs',
        //             'balance','payment_clients.id','clients.id as client_id','payment_clients.created_at as create')
        //         ->selectRaw("'Pagos' as type")->selectRaw($column_balance_payments)
        //         ->join('payment_clients','clients.id','payment_clients.client_id')
        //         ->join('coins','payment_clients.coin_id','coins.id')->where('client_id',$id)
        //         ->where('payment_clients.status','<>','Historico')->where('payment_clients.status','<>','Anulado')
        //         ->union($saleclient)->orderBy('date','desc')->orderBy('create','desc')->get();
        // }
        // else {
        //     $saleclient = Client::select('sale_date as date','symbol','mount','clients.names','clients.count_in_bs',
        //             'balance','sales.id','clients.id as client_id','sales.created_at as create')
        //         ->selectRaw("'Compras' as type")->selectRaw($column_balance_sales)
        //         ->join('sales','clients.id','sales.client_id')->join('coins','sales.coin_id','coins.id')
        //         ->where('client_id',$id)->where('sales.status','<>','Anulada');
        //     $movements =  Client::select('payment_date as date','symbol','mount','clients.names','clients.count_in_bs',
        //             'balance','payment_clients.id','clients.id as client_id','payment_clients.created_at as create')
        //         ->selectRaw("'Pagos' as type")->selectRaw($column_balance_payments)
        //         ->join('payment_clients','clients.id','payment_clients.client_id')
        //         ->join('coins','payment_clients.coin_id','coins.id')->where('client_id',$id)
        //         ->where('payment_clients.status','<>','Anulado')
        //         ->union($saleclient)->orderBy('date','desc')->orderBy('create','desc')->get();
        // }
        // if (count($movements) == 0) {  // esto solo sucede cuando solo existe balance inicial
        //         $movements = Client::select('*','clients.id as client_id')->selectRaw("'Balance' as type")->where('id',$id)->get();
        // }
        // return $movements;


    // public function scopeLoadClients() {
    //     return Client::where('clients.status','Activo')->orderby('names');
    // }

    // public function scopeLoadUserClients($query,$id) {
    //     return
        // Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
        //     ->where('user_clients.user_id',$id)->get(); consulta original
    // }



