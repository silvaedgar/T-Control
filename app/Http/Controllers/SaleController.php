<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\SaleDetail;
use App\Models\Client;
use App\Models\Product;
use App\Models\Coin;
use \Illuminate\Support\Facades\DB;



class SaleController extends Controller
{

    public function clients($id) {

        return  Client::select('sales.*','clients.balance','coins.symbol')->leftjoin('sales','clients.id','sales.client_id')
        ->leftjoin('coins','sales.coin_id','coins.id')->where('clients.id',$id)->get();

        // return  Client::select('sales.*','clients.balance','coins.symbol')->leftjoin('sales','clients.id','sales.client_id')
        //     ->join('coins','sales.coin_id','coins.id')
        //     ->where([['client_id',$id],['sales.status','Pendiente']])
        //     ->orwhere([['client_id',$id],['sales.status','Parcial']])->orderBy('sale_date')->orderBy('sales.id')
        //     ->get();


        // return  Sale::where('client_id',$id)->where('status','Pendiente')
        //     ->orwhere('status','Parcial')->orderBy('sale_date')->get();
    }

    public function index()
    {
        $sales = Sale::orderBy('id','desc')->get();
        return view('sales.index',compact('sales'));
        //
    }

    public function create()
    {
        $products = Product::where('status','Activo')->orderBy('name')->get();
        $clients = Client::where('status','Activo')->get();
        $base = Coin::where('calc_currency_sale','S')->orwhere('base_currency','S')
                ->where('status','Activo')->orderBy('base_currency')->get();
        if (count($base) > 2) {
            $message = 'Error_Verifique la Configuración de las Monedas. Consulte con el administrador';
            $sales = Sale::orderBy('id','desc')->get();
            return view('sales.index',compact('sales','message'));
        }
        $base_coins = ['base_id' => $base[0]->id, 'base_name' => $base[0]->name,'base_calc_id'=>
                isset($base[1]->id)?$base[1]->id:$base[0]->id, 'base_calc_name'=> isset($base[1]->name)?$base[1]->name:$base[0]->name];
        return view('sales.create',compact('clients','products','base_coins'));
    }

    public function store(StoreSaleRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->status = ($request->condition == "Credito" ? 'Pendiente' : 'Cancelada');

            $sale = Sale::create($request->all());
            $client = Client::find($request->client_id);

            $amount = $request->mount;
            if($request->conditions == "Credito") {
                if ($request->rate_exchange <> 1) {
                    $amount = $request->mount / $request->rate_exchange;
                }
                $balance_client = $client->balance + $amount;

                $client->balance = $balance_client;
                $client->save();
            }
            $item = 1;
            foreach ($request->product_id as $key => $value) {
                $results[] = array("product_id"=>$request->product_id[$key], "tax_id"=>$request->tax_id[$key],
                     "item"=>$item,"quantity"=>$request->quantity[$key], "price"=>$request->price[$key],
                     "tax"=>$request->tax[$key]);
                $item++;
            }
            $sale->sale_details()->createMany($results);

            $message = 'Ok_Factura de Venta creada con exito';
            DB::commit();
        } catch (\Throwable $th) {
            echo $th;
            DB::rollback();
            $message =  'Error_Error generando Factura de Venta. Consulte con el Administrador del Sistema';
        }
        // echo $message;
        return redirect()->route('sales.index')->with('status',$message);
    }

    public function show($id)
    {

        $clients = Client::where('status','Activo')->get();
        $coins = Coin::where('status','Activo')->get();
        $sale = Sale::select('sales.*','clients.names as cliente','coins.name as moneda')
                ->join('clients','sales.client_id','clients.id')
                ->join('coins','sales.coin_id','coins.id')
                ->where('sales.id',$id)->first();

        $sale_details = SaleDetail::select('sale_details.*','products.name')
            ->join('products','sale_details.product_id','products.id')
            ->where('sale_details.sale_id',$id)
            ->where('sale_details.status','Activo')->orderBy('item')->get();

        return view('sales.show',compact('sale','sale_details','clients','coins'));
    }

    public function edit(Sale $sale)
    {
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
    }

    public function destroy(Sale $sale)
    {
    }
}
