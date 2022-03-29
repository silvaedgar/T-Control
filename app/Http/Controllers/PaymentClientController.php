<?php

namespace App\Http\Controllers;

use App\Models\PaymentClient;
use App\Models\PaymentForm;
use App\Models\Sale;
use App\Models\Coin;
use App\Models\Client;
use \Illuminate\Support\Facades\DB;

use App\Http\Requests\StorePaymentClientRequest;
use App\Http\Requests\UpdatePaymentClientRequest;

class PaymentClientController extends Controller
{
    public function index()
    {
        $paymentclients = PaymentClient::orderBy('client_id')->get();
        return view('payment-clients.index',compact('paymentclients'));
        //
    }

    public function create()
    {
        $paymentforms = PaymentForm::where('status','Activo')->get();
        $clients = Client::where('status','Activo')->get();
        // $coins = Coin::where('status','=','Activo')->get();
        $pendingsales = Sale::where('status','Parcial')->orwhere('status','Pendiente')
            ->orderBy('client_id')->get();
        $base = Coin::where('calc_currency_sale','S')->orwhere('base_currency','S')
            ->where('status','Activo')->orderBy('base_currency')->get();
        $base_coins = ['base_id' => $base[0]->id, 'base_name' => $base[0]->name, 'base_calc_id'=> isset($base[1]->id)?$base[1]->id:$base[0]->id, 'base_calc_name'=> isset($base[1]->name)?$base[1]->name:$base[0]->name];

        return view('payment-clients.create',compact('clients','paymentforms','base_coins','pendingsales'));
    }


    private  function calc_balance_client(StorePaymentClientRequest $request,$coin_base)
    {
        $paymentcurrency = $request->coin_id;
        if ($paymentcurrency == $coin_base ) { // moneda de pago es moneda de calculo
            return ($request->mount);
        }
        return  ($request->mount * $request->rate_exchange);
    }

    private function calc_balance_sale($mount, $balance) {
        if ($mount >= $balance) {
            return  ['paid_mount' => $balance,'status' => 'Cancelada','mount'=> $mount - $balance];
        }
        return  ['paid_mount' => $mount,'status' => 'Parcial','mount'=> 0];
    }

    private function verify_data_sale(StorePaymentClientRequest $request,Sales $sale,$currency_calc)
    {

        $paymentcurrency = $request->coin_id;
        $balance_sale = $sale->mount - $sale->paid_mount;
        echo "BALANCE: $balance_sale";
        if ($paymentcurrency == $sale->coin_id){ //Moneda de Pago es igual a la FC
            return $this->calc_balance_sale($request->mount,$balance_sale);
        }
        if ($paymentcurrency == $currency_calc) {
            $mount_payment_currency = $request->mount * $request->rate_exchange;
            return  $this->calc_balance_sale($mount_payment_currency,$balance_sale);
        }
        $mount_payment_calc = $request->mount / $request->rate_exchange;
        return  $this->calc_balance_sale($mount_payment_calc,$balance_sale);
    }

    private function update_client(StorePaymentClientRequest $request,Client $client,$coin_calc_id) {

        $balance_client = $client->balance - $this->calc_balance_client($request,$coin_calc_id);
        $client->balance = $balance_client;
        $client->save();
    }


    public function store(StorePaymentClientRequest $request)
    {
        DB::beginTransaction();
        try {
            $paymentclient = PaymentClient::create($request->all());
            $client = Client::find($request->client_id);

            $base = Coin::where('calc_currency_sale','S')->orwhere('base_currency','S')
                    ->where('status','Activo')->orderBy('base_currency')->get();

            $coin_calc_id = isset($base[1]->id)?$base[1]->id:$base[0]->id;

            $this->update_client($request,$client,$coin_calc_id);
            $sale_pendings =  Sale::where('client_id',$request->client_id)->where('status','Pendiente')
                ->orwhere('status','Parcial')->orderBy('sale_date')->get();
            $sale_update = [];
            $monto = $request->mount;
            foreach ($sale_pendings as $key => $value) {
                if ($monto > 0) {


                    $sale =  $sale_pendings[$key];
                    $paymentcurrency = $request->coin_id;
                    $balance_sale = $sale->mount - $sale->paid_mount;
                    if ($paymentcurrency == $sale->coin_id){ //Moneda de Pago es igual a la FC
                        $balance =  $this->calc_balance_sale($request->mount,$balance_sale);
                    }
                    else {  // OJPO HAY QUE REVISAR LO DE MULTIPLICAR O DIVIDIR PO
                        if ($paymentcurrency == $coin_calc_id) {
                            $mount_payment_currency = $request->mount / $request->rate_exchange;
                            $balance =  $this->calc_balance_sale($mount_payment_currency,$balance_sale);
                        }
                        else {
                            $mount_payment_calc = $request->mount * $request->rate_exchange;
                            $balance = $this->calc_balance_sale($mount_payment_calc,$balance_sale);
                        }
                    }
                    array_push($sale_update,$balance);

                    // array_push($sale_update,$this->verify_data_sale($request,$sale_pendings[$key],$coin_calc_id));
                    // dump($sale_update);
                    $sale_pendings[$key]->paid_mount = $sale_pendings[$key]->paid_mount + $sale_update[$key]['paid_mount'];
                    $sale_pendings[$key]->status = $sale_update[$key]['status'];

                    $sale_pendings[$key]->save();
                    $monto = $sale_update[$key]['mount'];
                }
            }
            $message = "Ok_Pago del Cliente $client->names  procesado con exito. Se actualizaron Balance y Facturas de Venta";
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            echo $th;
            $message =  "Error_Error generando Pago de cliente $client->names verifique";
        }
        // echo $message;
        return redirect()->route('paymentclients.index')->with('status',$message);
    }

    public function show(PaymentClient $paymentClient)
    {
        //
    }

    public function edit(PaymentClient $paymentClient)
    {
        //
    }

    public function update(UpdatePaymentClientRequest $request, PaymentClient $paymentClient)
    {
        //
    }

    public function destroy(PaymentClient $paymentClient)
    {
        //
    }
}
