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
use PDF;


class SaleController extends Controller
{
    public function __construct() {
        $this->middleware('role')->only('index','create','edit','store','update','report');
    }

    public function clients($id) {

        return  Client::select('sales.*','clients.balance','coins.symbol')->leftjoin('sales','clients.id','sales.client_id')
        ->leftjoin('coins','sales.coin_id','coins.id')->where('clients.id',$id)->get();

    }

    public function loadcoinbase()
    {
        $base = Coin::where('calc_currency_sale','S')->orwhere('base_currency','S')
                ->where('status','Activo')->orderBy('base_currency')->get();
        if (count($base) > 2) {
            $message = 'Error_Verifique la Configuración de las Monedas. Consulte con el administrador';
            $purchases = Sale::orderBy('id','desc')->get();
            return '';
        }
        $base_coins = ['base_id' => $base[0]->id, 'base_name' => $base[0]->name, 'base_symbol' => $base[0]->symbol,
        'base_calc_id'=> isset($base[1]->id)?$base[1]->id:$base[0]->id,
        'base_calc_name'=> isset($base[1]->name)?$base[1]->name:$base[0]->name,
        'base_calc_symbol'=> isset($base[1]->symbol)?$base[1]->symbol:$base[0]->symbol];
        return $base_coins;
    }

    public function createfilter($data) {
        $filter = [];
        if ($data->status != 'Select' && $data->status != 'Todos' ) {
            $filtro = ['status','=', $data->status];
            array_push($filter,$filtro);
        }
        if (!empty($data->startdate)) {
            $filtro = ['sale_date','>=',$data->startdate];
            array_push($filter,$filtro);
        }
        if (!empty($data->enddate)) {
            $filtro = ['sale_date','<=',$data->enddate];
            array_push($filter,$filtro);
        }
        return $filter;

    }

    public function filtersale(Request $request) {
        $filter = $this->createfilter($request);
        $sales = $this->index($filter);
        return view('sales.index',compact('sales','filter'));
    }

    public function index($filter = [])
    {
        if (count($filter) == 0)
            $sales = Sale::orderBy('id','desc')->where('status','<>','Historico')->get();
        else
            return Sale::where($filter)->where('status','<>','Historico')->orderBy('id','desc')->get();
        return view('sales.index',compact('sales'));
    }

    public function create()
    {
        $products = Product::where('status','Activo')->orderBy('name')->get();
        $clients = Client::where('status','Activo')->orderby('names')->get();
        $base_coins = $this->loadcoinbase();
        return view('sales.create',compact('clients','products','base_coins'));
    }

    public function store(StoreSaleRequest $request)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::create($request->all());
            $status = 'Cancelada';
            $paid_mount = $request->mount;
            $client = Client::find($request->client_id);
            $last_balance = $client->balance;
            if($request->conditions == "Credito") {
                $status = 'Pendiente';
                if ($request->rate_exchange <> 1) {
                    $paid_mount = $request->mount / $request->rate_exchange;
                }
                $balance_client = $client->balance + $paid_mount;
                $client->balance = $balance_client;
                $client->save();
                if ($last_balance < 0) {   // cliente con saldo s favor antes de la venta
                    $balance = $last_balance + $request->mount;
                    $status = ($balance <= 0 ? 'Cancelada' : 'Parcial');
                    $paid_mount = ($balance <= 0 ? $request->mount : -1 * $last_balance);
                }
            }
            if ($client->balance == 0) {
                Sale::where('client_id',$client->id)->update([
                    'status' => 'Historico',
                    'paid_mount' => $paid_mount
                ]);
                PaymentClient::where('client_id',$client->id)->update([
                    'status' => 'Historico'
                ]);
            }
            else {
                $sale->status = $status;
                $sale->paid_mount = $paid_mount;
                $sale->save();
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
            $message =  'Error_Error generando Factura de Venta. Consulte con el Administrador del Sistema';
        }
        // echo $message;
        return redirect()->route('sales.index')->with('status',$message);
    }

    public function show($id)
    {
        $clients = Client::where('status','Activo')->get();
        $coins = Coin::where('status','Activo')->get();
        $sale = $this->loadsale($id);
        $sale_details = $this->loadsaledetails($id);
        $base_coins = $this->loadcoinbase();
        return view('sales.show',compact('sale','sale_details','clients','coins','base_coins'));
    }

    public function loadsale($id) {
        return Sale::select('sales.*','clients.names as cliente','coins.name as moneda','coins.symbol as simbolo')
        ->join('clients','sales.client_id','clients.id')->join('coins','sales.coin_id','coins.id')
        ->where('sales.id',$id)->first();
    }

    public function loadsaledetails ($id) {
        return  SaleDetail::select('sale_details.*','products.name')
            ->join('products','sale_details.product_id','products.id')
            ->where('sale_details.sale_id',$id)
            ->where('sale_details.status','Activo')->orderBy('item')->get();
    }

    public function print($id) {


        $sale = $this->loadsale($id);
        // Si el usuario es de tipo cliente verifica cual cliente_id tiene asignado
        // para verificar que no imprima una factura que no corresponda
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('User')) {
            $exist_client = true;
            $userclient =   Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
                ->where('user_clients.user_id',Auth::user()->id)->first();
            if ($sale->client_id <> $userclient->client_id)
                return view('home-auth',compact('exist_client'));
        }
        $client = Client::where('id',$sale->client_id)->first();
        $sale_details = $this->loadsaledetails($id);
        $coin = Coin::where('id',$sale->coin_id)->first();
        $pdf = PDF::loadView('sales.printinvoice',['sale' =>$sale,'sale_details'=>$sale_details,'client'=>$client]);
        return $pdf->stream();
    }

    public function report(Request $request) {

        $filter = $this->createfilter($request);
        $sales = $this->index($filter);
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
    }
}
