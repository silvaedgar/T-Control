<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;

use App\Http\Requests\SaleRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Facades\Config;

use App\Traits\CalculateMountsTrait;
use App\Traits\FiltersTrait;
use App\Traits\PaymentFormTrait;
use App\Traits\SaleTrait;
use App\Traits\CoinTrait;

use Carbon\Carbon;
use PDF;

class SaleController extends Controller
{
    use FiltersTrait, CoinTrait, CalculateMountsTrait, SaleTrait, PaymentFormTrait;

    public function __construct()
    {
        $this->middleware('role')->only('index', 'create', 'edit', 'store', 'update', 'report');
    }

    public function fieldsFill()
    {
        return ['field' => 'calc_currency_sale', 'price' => 'sale->price', 'isPayment' => false];
    }

    public function index(Request $request)
    {
        $report = false;
        if (count($request->all()) > 0) {
            $filter = $this->createFilter($request, 'sale_date');
            $report = $request->option == 'Report';
        } else {
            $filter = $this->createFilter(['status' => 'Pendiente'], 'sale_date');
        }
        $config = Config::labels('Sales', Sale::GetSales($filter, 'sale_date')->get(), false, $filter);
        $config['isFormIndex'] = 'true';
        $config['hasFilter'] = true;
        $config['data']['status'] = ['Todos', 'Pendiente', 'Cancelada', 'Anulada', 'Historico'];
        $config = $this->headerInfoFill($config, $this->fieldsFill());
        // $reportOption = ['option' => 'Report'];
        // $config['buttons'][0]['url'] = route('sales.index', $reportOption);
        if ($report) {
            $pdf = PDF::loadView('sales.report', ['config' => $config]);
            return $pdf->stream();
        }
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('Sales');
        $config['header']['title'] = 'Creando Factura de Venta';
        $config = $this->headerInfoFill($config, $this->fieldsFill());

        $config['cols'] = 3;
        $config['data']['products'] = Product::GetProducts([['activo', '=', 1]])->get();
        $config['data']['clients'] = Client::GetClients([['activo', '=', 1]])->get();
        $config['data']['paymentForms'] = $this->getPaymentForms([['activo', '=', 1]])->get();

        $config['var_header']['table'] = $config['data']['clients'];
        return view('shared.create', compact('config'));
    }

    public function store(SaleRequest $request)
    {
        $continue = true;
        $exist_sale = Sale::where('client_id', $request->client_id)
            ->where('mount', $request->mount)
            ->where('sale_date', $request->sale_date)
            ->first();
        if ($exist_sale != '') {
            $fecha1 = new Carbon($exist_sale->created_at);
            if ($fecha1->diffInMinutes(now()) < 40) {
                $continue = false;
            }
        }
        return redirect()
            ->route('sales.index')
            ->with('message_status', $continue ? $this->saveSale($request) : 'Registro ya existe espere 5 minuto para intentar nuevamente');
    }

    public function show(Sale $sale)
    {
        $invoice = $this->getSale($sale->id)->first();

        $config = Config::labels('Sales');
        $config['header']['title'] = 'Detalle Factura de Venta';
        $config['cols'] = 3;
        $config['data']['products'] = null;
        $config['data']['clients'] = Client::GetClients([['id', '=', $invoice->client_id]])->get();
        $config['data']['coins'] = $this->getCoins([['id', '=', $invoice->coin_id]])->get();
        $config['data']['update'] = true;
        $config['var_header']['table'] = $config['data']['clients'];
        $config = $this->headerInfoFill($config, $this->fieldsFill(), $invoice);

        return view('shared.create', compact('config', 'invoice'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $message = '';
        $sale = Sale::with('Client', 'Coin')->find($id);
        if ($sale->conditions == 'Credito') {
            $calc_coin = $this->getBaseCoin('calc_currency_sale')->first();
            $mount = $sale->coin_id != $calc_coin->id ? $this->mount_other($sale, $calc_coin) : $sale->mount;
            Client::where('id', $sale->client->id)->update(['balance' => $sale->client->balance - $mount]);
            $message = 'Actualizado Balance del Cliente';
        }
        $sale->status = 'Anulada';
        $sale->save();
        // Sale::where('id',$sale->id)->update(['status' => 'Anulada']);
        DB::commit();
        return redirect()
            ->route('sales.index')
            ->with('message_status', 'Factura de Venta del Cliente: ' . $sale->Client->names . ' por un monto de: ' . $sale->mount . ' ' . $sale->Coin->symbol . ' Anulada con exito. ' . $message);
    }

    public function print($id)
    {
        $filter = ['id', $id]; // aun no se para que es
        $sale = Sale::with('SaleDetails', 'Coin', 'Client')
            ->where('id', $id)
            ->get();
        // Si el usuario es de tipo cliente verifica cual cliente_id tiene asignado
        // para verificar que no imprima una factura que no corresponda
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('User')) {
            $exist_client = true;
            $userclient = Client::select('*')
                ->join('user_clients', 'clients.id', 'user_clients.client_id')
                ->where('user_clients.user_id', Auth::user()->id)
                ->first();
            if ($sale->client_id != $userclient->client_id) {
                return view('home-auth', compact('exist_client'));
            }
        }
        $pdf = PDF::loadView('sales.printinvoice', ['sale' => $sale]);
        return $pdf->stream();
    }

    public function printList(Request $request)
    {
        return 'EDGAR';
        return $request;
    }
}
