<?php

namespace App\Http\Controllers;

use App\Models\PaymentClient;
use App\Models\PaymentForm;
use App\Models\Sale;
use App\Models\Coin;
use App\Models\Client;
use \Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use PDF;
use App\Http\Requests\StorePaymentClientRequest;
use App\Http\Requests\UpdatePaymentClientRequest;
use App\Traits\FiltersTrait;
use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;

class PaymentClientController extends Controller
{
    use FiltersTrait, GetDataCommonTrait, CalculateMountsTrait;

    public function __construct() {
        $this->middleware('role')->only('index','create','edit','store','update','report');

        // $this->middleware('role');
    }

    public function load_payments($filter=[]) {
        if (count($filter) == 0)
            return PaymentClient::select('payment_clients.*','payment_clients.payment_date as date')
                ->orderBy('payment_date','desc')->orderBy('created_at','desc')->get();
        else
            return  PaymentClient::select('payment_clients.*','payment_clients.payment_date as date')
                ->where($filter)->orderBy('payment_date','desc')->orderBy('created_at','desc')->get();
    }

    public function filter(Request $request) {
        $filter = $this->create_filter($request,'payment_date');
        $paymentclients = $this->load_payments($filter);
        $data_common = ['header' => 'Pagos Realizados por Clientes','buttons' =>[['message' => 'Reporte',
            'icon' => 'print','url' => route('paymentclients.filter')], ['message' => 'Generar Pago de Cliente',
            'icon' => 'person_add','url' => route('paymentclients.create')]],
            'links' => [['message' => 'Crear Factura', 'url' =>route('sales.create')]],
            'data_filter' => $this->data_filter($filter),
            'controller' => 'PaymentClient'];
        if ($request->option == "Report") {
            $pdf = PDF::loadView('shared.payment-list-report',['models' => $paymentclients,'data_common'=> $data_common]);
            return $pdf->stream();
        }
        else
            return view('payment-clients.index',compact('paymentclients','data_common'));
    }

    public function index()
    {
        $paymentclients = $this->load_payments([]);
        $data_common = ['header' => 'Pagos Realizados por Clientes','buttons' =>[['message' => 'Reporte',
            'icon' => 'print','url' => route('paymentclients.filter')], ['message' => 'Generar Pago de Cliente',
            'icon' => 'person_add','url' => route('paymentclients.create')]],
            'links' => [['message' => 'Crear Factura', 'url' =>route('sales.create')]],
            'data_filter' => $this->data_filter([]),
            'controller' => 'PaymentClient'];
        return view('payment-clients.index',compact('paymentclients','data_common'));
    }

    // public function prueba($id,$fieldjoin,$fieldwhere) {

    // return Coin::select('coins.*','currency_values.purchase_price','currency_values.sale_price')
    //     ->join('currency_values','coins.id',$fieldjoin)->where('coins.status','Activo')
    //     ->where('currency_values.status','Activo')->where($fieldwhere,$id);
    // }

    public function create()
    {
        $payment_forms = PaymentForm::where('status','Activo')->get();
        $clients = Client::where('status','Activo')->orderBy('names')->get();
        $pending_sales = Sale::where('status','Parcial')->orwhere('status','Pendiente')
            ->orderBy('client_id')->get();

        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id);
        $coins = $this->get_coins_invoice_payment($rate,'calc_currency_sale')->get();
        $rate = $rate->first();
        // return response()->json([
        //     'base_coin' => $base_coin,
        //     'calc_coin' => $calc_coin,
        //     'rate' => $rate,
        //     'coins' => $coins
        // ], 200);
        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->sale_price,
            'controller' => 'PaymentClient', 'header' => 'Pago de Clientes',
            'sub_header' => "Moneda de Calculo: ".$calc_coin->symbol.' - Tasa :'.number_format($rate->sale_price,2),
            'message_title' => '', 'message_subtitle' => '',
            'links_header' => ['message' => 'Atras','url' => url()->previous()],
            'links_create' => [['message' => 'Crear Factura', 'url' => route('sales.create')]],'cols'=> 3];
        return view('payment-clients.create',compact('clients','payment_forms','pending_sales','coins','data_common'));
    }


    private function calc_balance_client(StorePaymentClientRequest $request,Client $client,$rate_exchange)
    {
        $paymentcurrency = $request->coin_id;
        if ($client->count_in_bs == 'S') {
            if ($request->coin_id != 1 )
                $mount = $request->mount * $rate_exchange;
            else
                $mount = $request->mount;
            return $mount;
        }
        if ($request->calc_currency_id == $request->coin_id )  // moneda de pago es moneda de calculo
            return ($request->mount);
        if ($request->calc_currency_id == 1) // la moneda de calculo es Bs pago en $ u otra
            $mount = $request->mount * $rate_exchange;
        else
            $mount =  ($request->mount / $rate_exchange);
        return $mount;
    }

    private function calc_balance_sale($mount, $balance) {
        if ($mount >= $balance) {
            return  ['paid_mount' => $balance,'status' => 'Cancelada','mount'=> $mount - $balance];
        }
        return  ['paid_mount' => $mount,'status' => 'Parcial','mount'=> 0];
    }

    private function verify_data_sale(StorePaymentClientRequest $request,Sale $sale)
    {
        $paymentcurrency = $request->coin_id;
        $balance_sale = $sale->mount - $sale->paid_mount;
        if ($paymentcurrency == $sale->coin_id){ //Moneda de Pago es igual a la FC
            return $this->calc_balance_sale($request->mount,$balance_sale);
        }
        if ($paymentcurrency == $request->calc_currency_id) {
            $mount_payment_currency = $request->mount * $request->rate_exchange;
            return  $this->calc_balance_sale($mount_payment_currency,$balance_sale);
        }
        $mount_payment_calc = $request->mount / $request->rate_exchange;
        return  $this->calc_balance_sale($mount_payment_calc,$balance_sale);
    }

    private function update_client(StorePaymentClientRequest $request,Client $client,$rate_exchange) {

        $balance_client = $client->balance - $this->calc_balance_client($request,$client,$rate_exchange);
        $client->balance = $balance_client;
        $client->save();
        if ($balance_client == 0) {
            Sale::where('client_id',$client->id)->update([
                'status' => 'Historico'
            ]);
            PaymentClient::where('client_id',$client->id)->update([
                'status' => 'Historico'
            ]);
        }
    }

    public function store(StorePaymentClientRequest $request)
    {
        $continue = true;
        $exist_payment = PaymentClient::where('client_id',$request->client_id)->where('mount',$request->mount)
            ->where('payment_date',$request->payment_date)->first();
        if ($exist_payment != '') {
            $fecha1 = new Carbon($exist_payment->created_at);
            if ($fecha1->diffInMinutes(now()) < 40)
                $continue = false;
        }
        if ($continue) {
            DB::beginTransaction();
            try {
                $rate_exchange = ($request->rate_exchange == 1 ? $request->rate_date : $request->rate_exchange);
                $paymentclient = PaymentClient::create($request->all());
                $paymentclient->update(["rate_exchange" => $rate_exchange]); // Actualiza la tasa en caso de ser 1
                $client = Client::find($request->client_id);
                $sale_pendings =  Sale::where('client_id',$request->client_id)
                    ->whereIn('status',['Pendiente','Parcial'])->orderBy('sale_date')->get();
                // ver paymentsuppliercontroller mismo metodo otra forma de hacer la consulta anterior
                $sale_update = [];
                $monto = $request->mount;
                foreach ($sale_pendings as $key => $value) {
                    if ($monto > 0) {
                        array_push($sale_update,$this->verify_data_sale($request,$sale_pendings[$key]));
                        $sale_pendings[$key]->paid_mount = $sale_pendings[$key]->paid_mount + $sale_update[$key]['paid_mount'];
                        $sale_pendings[$key]->status = $sale_update[$key]['status'];
                        $sale_pendings[$key]->save();
                        $monto = $sale_update[$key]['mount'];
                    }
                }
                $this->update_client($request,$client,$rate_exchange);
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
        else {
            return back()->with('status','Error_Registro ya existente espere 5 minuto para intentar nuevamente');
        }
    }

    public function show($id)
    {
        $continue = true;
        $payment = PaymentClient::select('payment_clients.*','clients.names as cliente','coins.name as moneda','coins.symbol as simbolo')
                ->join('clients','payment_clients.client_id','clients.id')
                ->join('coins','payment_clients.coin_id','coins.id')
                ->where('payment_clients.id',$id)->first();    
        if (Auth::user()->hasRole('Client')) { 
           $user_client = $this->get_user_clients(Auth::user()->id)->first();
           $continue = $user_client->client_id == $payment->client_id;
        }
        if ($continue)
        {
            $payment_forms = PaymentForm::where('status','Activo')->get();
            $clients = Client::where('status','Activo')->where('id',$payment->client_id)->get();
            $base_coin = $this->get_base_coin('base_currency')->first();
            $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
            $rate = $this->get_base_coin_rate($calc_coin->id);
            $coins = $this->get_coins_invoice_payment($rate,'calc_currency_sale')->get();
            $rate = $rate->first();
            $mount_other = $this->mount_other($payment,$calc_coin,$base_coin);


            // $mount_other = $payment->simbolo == 'BsD' ? '$ '.number_format($payment->mount / $payment->rate_exchange,2)  :
            //                             "BsD ".number_format($payment->mount * $payment->rate_exchange,2);

            $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
                'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->sale_price,
                'controller' => 'PaymentClient', 'header' => 'Pago de Clientes',
                'sub_header' => "Pagado en ".$payment->simbolo.' - Tasa :'.number_format($payment->rate_exchange,2),
                'message_title' => 'Saldo: '.$clients[0]->balance.' '.$calc_coin->symbol,
                'message_subtitle' => "Monto en ".$mount_other, 'cols' => 3,
                'links_header' => ['message' => 'Atras','url' => url()->previous()],
                'links_create' => [['message' => 'Crear Factura', 'url' =>route('sales.create')],
                        ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]]];

            return view('payment-clients.show',compact('payment','clients','coins','data_common','payment_forms'));
        }
        else {
           $exist_client = ($user_client == ''?false :true);
           return view('home-auth',compact('exist_client'));
        }
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
        return "ACTUALIZAR SALDO PAYMENT CLIENT";
        //
    }
}
