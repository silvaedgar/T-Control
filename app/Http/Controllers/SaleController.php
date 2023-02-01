<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;

use App\Models\Sale;
use App\Models\Client;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;
use PDF;

use App\Traits\FiltersTrait;
use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;

use App\Facades\DataCommonFacade;
use App\Facades\SaleFacade;

class SaleController extends Controller
{
    use FiltersTrait, GetDataCommonTrait, CalculateMountsTrait;

    public function __construct() {
        $this->middleware('role')->only('index','create','edit','store','update','report');
    }

    public function index()
    {
        $sales = Sale::GetSales()->get();
        $data = ['data_filter' => $this->data_filter([]), 'header' => 'Facturas de Venta' ];
        $data_common = DataCommonFacade::index('Sale',$data);
        return view('sales.index',compact('sales','data_common'));
    }

    public function create()
    {
        $data['header'] = 'Creando Factura de Venta';
        $data = SaleFacade::getData();
        $data_common = DataCommonFacade::create('Sale',$data);
        return view('sales.create',compact('data','data_common'));
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
        return redirect()->route('sales.index')->with('message_status', $continue ? SaleFacade::storeSale($request)
                : 'Registro ya existe espere 5 minuto para intentar nuevamente');
    }

    public function show($id)
    {
        $continue = true;
        $invoice = Sale::with('Client')->where('id',$id)->first(); //para verificar la consulta de clientes y que sea el cliente
        if (Auth::user()->hasRole('Client')) {
           $user_client = $this->get_user_clients(Auth::user()->id)->first();
           $continue = $user_client->client_id == $invoice->client->id;
        }
        if ($continue)
        {
            $data = SaleFacade::getData($id);
            $data_common = DataCommonFacade::edit('Sale',$data);
            return view('sales.show',compact('data','data_common'));
        }
        else
        {   $exist_client = ($user_client == ''?false :true);
            return view('home-auth',compact('exist_client'));
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $message="";
        $sale = Sale::with('Client','Coin')->find($id);
        if ($sale->conditions == "Credito") {
            $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
            $mount = $sale->coin_id != $calc_coin->id ? $this->mount_other($sale,$calc_coin) : $sale->mount;
            Client::where('id',$sale->client->id)->update(['balance' => $sale->client->balance - $mount]);
            $message = "Actualizado Balance del Cliente";
        }
        $sale->status = "Anulada";
        $sale->save();
        // Sale::where('id',$sale->id)->update(['status' => 'Anulada']);
        DB::commit();
        return redirect()->route('sales.index')->with('message_status',"Factura de Venta del Cliente: ".$sale->Client->names
                ." por un monto de: ".$sale->mount." ".$sale->Coin->symbol." Anulada con exito. ".$message);
    }

    public function filter(Request $request) {
        $filter = $this->create_filter($request,'sale_date');
        $sales = Sale::GetSales($filter)->get();
        $data = ['data_filter' => $this->data_filter($filter), 'header' => 'Facturas de Venta' ];
        $data_common = DataCommonFacade::index('Sale',$data);
        if ($request->option == "Report") {
            $pdf = PDF::loadView('shared.payment-list-report',['models' => $sales,'data_common'=> $data_common]);
            return $pdf->stream();
        }
        else
            return view('sales.index',compact('sales','data_common'));
    }

    public function print($id) {
        $filter = ['id',$id]; // aun no se para que es
        $sale = Sale::with('SaleDetails','Coin','Client')->where('id',$id)->get();
        // Si el usuario es de tipo cliente verifica cual cliente_id tiene asignado
        // para verificar que no imprima una factura que no corresponda
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('User')) {
            $exist_client = true;
            $userclient =   Client::select('*')->join('user_clients','clients.id','user_clients.client_id')
                ->where('user_clients.user_id',Auth::user()->id)->first();
            if ($sale->client_id <> $userclient->client_id)
                return view('home-auth',compact('exist_client'));
        }
        $pdf = PDF::loadView('sales.printinvoice',['sale' =>$sale]);
        return $pdf->stream();
    }
}
