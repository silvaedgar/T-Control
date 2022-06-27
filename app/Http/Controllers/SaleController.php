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
use \Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use App\Traits\FiltersTrait;
use App\Traits\GetDataCommonTrait;


class SaleController extends Controller
{
    use FiltersTrait, GetDataCommonTrait;

    public function __construct() {
        $this->middleware('role')->only('index','create','edit','store','update','report');
    }


    public function load_sales($filter) {
        if (!is_array($filter))
            return Sale::select('sales.*','clients.names as cliente','coins.name as moneda','coins.symbol as simbolo')
                    ->join('clients','sales.client_id','clients.id')->join('coins','sales.coin_id','coins.id')
                    ->where('sales.id',$filter);
        if (count($filter) ==0)
            return Sale::select('sales.*','sales.sale_date as date')
                ->orderBy('sale_date','desc')->orderBy('created_at','desc');
        else
            return Sale::select('sales.*','sales.sale_date as date')
                ->where($filter)->orderBy('sale_date','desc')->orderBy('created_at','desc');
    }

    public function filter(Request $request) {
        $filter = $this->create_filter($request,'sale_date');
        $sales = $this->load_sales($filter)->get();
        $data_common = ['header' => 'Facturas de Ventas','buttons' =>[['message' => 'Reporte',
            'icon' => 'print','url' => route('sales.filter')], ['message' => 'Crear Factura de Venta',
            'icon' => 'person_add','url' => route('sales.create')]],
            'links' => [['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]],
            'data_filter' => $this->data_filter($filter),
            'controller' => 'Sale'];
        if ($request->option == "Report") {
            $pdf = PDF::loadView('shared.payment-list-report',['models' => $sales,'data_common'=> $data_common]);
            return $pdf->stream();
        }
        else
            return view('sales.index',compact('sales','data_common'));
    }

    public function index()
    {
        $sales = $this->load_sales([])->get();
        $data_common = ['header' => 'Facturas de Ventas','buttons' =>[['message' => 'Reporte',
            'icon' => 'print','url' => route('sales.filter')], ['message' => 'Crear Factura de Venta',
            'icon' => 'person_add','url' => route('sales.create')]],
            'links' => [['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]],
            'data_filter' => $this->data_filter([]), 'cols' => 2,
            'controller' => 'Sale'];
        return view('sales.index',compact('sales','data_common'));
    }

    public function create()
    {
        $products = Product::GetProducts()->get();
        $clients = Client::where('status','Activo')->orderby('names')->get();

        $base_coin = $this->get_base_coin('base_currency')->first();
        $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        $rate = $this->get_base_coin_rate($calc_coin->id);
        $coins = $this->get_coins_invoice_payment($rate,'calc_currency_sale')->get();
        $rate = $rate->first();
        $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
            'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->sale_price,
            'controller' => 'Sale', 'header' => 'Crear Factura de Venta',
            'sub_header' => "Moneda de Calculo: ".$calc_coin->symbol.' - Tasa :'.number_format($rate->sale_price,2),
            'message_title' => '0.00 '.$calc_coin->symbol,
            'message_subtitle' => ($calc_coin->symbol != $base_coin->symbol ? '0.00 '.$base_coin->symbol : ''),
            'links_header' => ['message' => 'Atras','url' => url()->previous()],
            'links_create' => [['message' => 'Generar Pago', 'url' => route('paymentclients.create')]], 'cols'=>'3'];
        return view('sales.create',compact('clients','products','coins','data_common'));
    }

    public function store(StoreSaleRequest $request)
    {
        $continue = true;
        $exist_sale = Sale::where('client_id',$request->client_id)->where('mount',$request->mount)
            ->where('sale_date',$request->sale_date)->first();
        if ($exist_sale != '') {
            $fecha1 = new Carbon($exist_sale->created_at);
            if ($fecha1->diffInMinutes(now()) < 40)
                $continue = false;
        }
        if ($continue) {
            DB::beginTransaction();
            try {
                $rate_exchange = $request->rate_exchange;
                if ($request->rate_exchange == 1) {
                    // la taza es automatica cuando la Factura es la moneda de calculo
                    $rate_exchange = $request->rate_exchange_date;
                }
                $sale = Sale::create($request->all());
                $sale->update(["rate_exchange" => $rate_exchange]); // Actualiza la tasa en caso de ser 1
                $status = 'Cancelada';
                $mount = $request->mount;
                $client = Client::find($request->client_id);
                $last_balance = $client->balance;
                if($request->conditions == "Credito") {
                    $status = 'Pendiente';
                    if ($request->rate_exchange <> 1 && $client->count_in_bs != 'S') {
                        $mount = $request->mount / $request->rate_exchange;
                    }
                    if ($client->count_in_bs == 'S' && $request->coin_id != 1) { // si tiene cuenta solo en bs y la factura es diferente del bolivar
                        // rate exchange tiene la tasa del dia
                        $balance_client = $client->balance + ($mount * $rate_exchange);
                    }
                    else {
                        $balance_client = $client->balance + $mount;
                    }
                    $client->balance = $balance_client;
                    $client->save();
                    if ($last_balance < 0) {   // cliente con saldo a favor antes de la venta
                        $status = ($balance_client <= 0 ? 'Cancelada' : 'Parcial');
                        $mount = ($balance_client <= 0 ? $request->mount : -1 * $last_balance);
                    }
                    if ($balance_client == 0) {
                        $status = "Historico";
                        PaymentClient::where('client_id',$client->id)->update([
                            'status' => 'Historico'
                        ]);
                    }
                    $sale->update([
                        'status' => $status,
                        'paid_mount' => $mount
                    ]); // Actualiza la tasa en caso de ser 1
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
                DB::rollback();
                // echo $th;
                $message =  'Error_Error generando Factura de Venta. Consulte con el Administrador del Sistema';
            }
            // echo $message;
            return redirect()->route('sales.index')->with('status',$message);
        }
        else {
            // return "Registro existente";
            return back()->with('status','Error_Registro ya existente espere 5 minuto para intentar nuevamente');
        }
    }

    public function show($id)
    {
        $continue = true;
        $invoice = $this->load_sales($id,true)->first();
        if (Auth::user()->hasRole('Client')) { 
           $user_client = $this->get_user_clients(Auth::user()->id)->first();
           $continue = $user_client->client_id == $invoice->client_id;
        }
        if ($continue)
        {
            $filter = ['sales.id',$id];
            $invoice_details = $this->load_sale_details($id)->get();
            $clients = Client::where('status','Activo')->where('id',$invoice->client_id)->orderBy('names')->get();

            $base_coin = $this->get_base_coin('base_currency')->first();
            $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
            $rate = $this->get_base_coin_rate($calc_coin);
            $coins = $this->get_coins_invoice_payment($rate,'calc_currency_sale')->get();
            $rate = $rate->first();
            $mount_other = '';
            if ($calc_coin->id != $base_coin->id)
                $mount_other = "Monto en ".($invoice->simbolo == $calc_coin->symbol ?
                    $base_coin->symbol.': '.number_format($invoice->mount * $invoice->rate_exchange,2) :
                    $calc_coin->symbol.': '.number_format($invoice->mount / $invoice->rate_exchange,2));
            $data_common = ['base_coin_id'=> $base_coin->id, 'base_coin_symbol'=> $base_coin->symbol,
                'calc_coin_id' => $calc_coin->id, 'calc_coin_symbol' => $calc_coin->symbol, 'rate' => $rate->sale_price,
                'controller' => 'Sale', 'header' => 'Detalle Factura de Venta',
                'sub_header' => "Facturada en ".$invoice->simbolo.' - Tasa :'.number_format($invoice->rate_exchange,2),
                'message_title' => "Monto: ".$invoice->mount." ".$invoice->simbolo,
                'message_subtitle' => $mount_other, 'cols'=> 3,
                'links_header' => ['message' => 'Atras','url' => url()->previous()],
                'links_create' => [['message' => 'Crear Factura', 'url' =>route('sales.create')],
                        ['message' => 'Generar Pago de Cliente', 'url' =>route('paymentclients.create')]]];
            return view('sales.show',compact('invoice','invoice_details','clients','coins','data_common'));
        }
        else 
        {   $exist_client = ($user_client == ''?false :true);
            return view('home-auth',compact('exist_client'));
        }


    }


    public function load_sale_details ($id) {
        return  SaleDetail::select('sale_details.*','products.name')
            ->join('products','sale_details.product_id','products.id')
            ->where('sale_details.sale_id',$id)
            ->where('sale_details.status','Activo')->orderBy('item');
    }

    public function print($id) {
        $sale = $this->loadsale($id);
        $sale_details = $this->load_sale_details($id)->get();
        // Si el usuario es de tipo cliente verifica cual cliente_id tiene asignado
        // para verificar que no imprima una factura que no corresponda
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('User')) {
            $exist_client = true;
            $userclient =   Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
                ->where('user_clients.user_id',Auth::user()->id)->first();
            if ($sale->client_id <> $userclient->client_id)
                return view('home-auth',compact('exist_client'));
        }
        $client = Client::find($sale->client_id);
        $coin = Coin::find($sale->coin_id);
        $pdf = PDF::loadView('sales.printinvoice',['sale' =>$sale,'sale_details'=>$sale_details,'client'=>$client]);
        return $pdf->stream();
    }

    public function report(Request $request) {
        $filter = $this->create_filter($request);
        $sales = $this->load_sales($request->status,$filter)->get();
        $pdf = PDF::loadView('sales.report',['sales' => $sales,'filter'=> $filter]);
        return $pdf->stream();
    }

    public function edit(Sale $sale)
    {
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();
        $message="";
        if ($sale->conditions == "Credito") {

            $client = Client::find($sale->client_id);
            Client::where('id',$sale->client_id)->update(['balance' => $client->balance - $sale->mount]);
            $message = "Actualizado Balance del Cliente";
        }
        Sale::where('id',$sale->id)->update(['status' => 'Anulada']);
        DB::commit();
        return redirect()->route('sales.index')->with('status',"Ok_Factura de Venta Anulada. ");

    }
}
