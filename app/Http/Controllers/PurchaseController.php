<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\PaymentSupplier;
use App\Models\Supplier;
use App\Models\Product;

use App\Http\Requests\PurchaseRequest;

use Illuminate\Support\Facades\DB;
use App\Facades\PurchaseFacade;
use App\Facades\Config;

use App\Traits\PaymentFormTrait;
use App\Traits\FiltersTrait;
use App\Traits\CoinTrait;
use App\Traits\CalculateMountsTrait;

use Carbon\Carbon;
use PDF;

class PurchaseController extends Controller
{
    use FiltersTrait, CoinTrait, CalculateMountsTrait, PaymentFormTrait;

    public function __construct()
    {
        $this->middleware('role');
    }

    public function fieldsFill()
    {
        return ['field' => 'calc_currency_purchase', 'price' => 'purchase_price', 'isPayment' => false];
    }

    public function index(Request $request)
    {
        $report = false;
        if (count($request->all()) > 0) {
            $filter = $this->createFilter($request, 'purchase_date');
            $report = $request->option == 'Report';
        } else {
            $filter = $this->createFilter(['status' => 'Pendiente'], 'purchase_date');
        }

        $config = Config::labels('Purchases', Purchase::GetPurchases($filter, 'purchase_date')->get(), false, $filter);
        $config = $this->headerInfoFill($config, $this->fieldsFill());

        $config['isFormIndex'] = 'true';
        $config['hasFilter'] = true;
        $config['data']['status'] = ['Todos', 'Pendiente', 'Cancelada', 'Anulada', 'Historico'];
        if ($report) {
            $pdf = PDF::loadView('shared.payment-list-report', ['models' => $purchases, 'data_common' => $data_common]);
            return $pdf->stream();
        }
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('Purchases');
        $config['header']['title'] = 'Creando Factura de Compra';
        $config['cols'] = 3;
        $config['data']['products'] = Product::GetProducts([['activo', '=', 1]])->get();
        $config['data']['suppliers'] = Supplier::GetSuppliers([['activo', '=', 1]])->get();
        $config['data']['paymentForms'] = $this->getPaymentForms([['activo', '=', 1]])->get();
        $config['var_header']['table'] = $config['data']['suppliers'];
        $config = $this->headerInfoFill($config, $this->fieldsFill());

        return view('shared.create', compact('config'));
    }

    public function store(PurchaseRequest $request)
    {
        $continue = true;
        $exist_purchase = Purchase::where('supplier_id', $request->supplier_id)
            ->where('mount', $request->mount)
            ->where('purchase_date', $request->purchase_date)
            ->first();
        if ($exist_purchase != '') {
            $fecha1 = new Carbon($exist_purchase->created_at);
            if ($fecha1->diffInMinutes(now()) < 5) {
                $continue = false;
            }
        }
        // return PurchaseFacade::storePurchase($request);
        return redirect()
            ->route('purchases.index')
            ->with('message_status', $continue ? PurchaseFacade::storePurchase($request) : 'Error_Registro ya existente espere 5 minuto para intentar nuevamente');
    }

    public function show(Purchase $purchase)
    {
        $invoice = $purchase;
        $config = Config::labels('Purchases');
        $config['header']['title'] = 'Detalle Factura de Compra';
        $config['cols'] = 3;
        $config['data']['products'] = null;
        $config['data']['suppliers'] = Supplier::GetSuppliers([['id', '=', $invoice->supplier_id]])->get(); // localscope del modelo
        $config['data']['coins'] = $this->getCoins([['id', '=', $invoice->coin_id]])->get(); // llamada al trais que tiene el localscope
        $config['var_header']['table'] = $config['data']['suppliers'];
        $config['data']['update'] = true;
        $config = $this->headerInfoFill($config, $this->fieldsFill(), $invoice);
        return view('shared.create', compact('config', 'invoice'));
    }

    //// El destroy anula una factura
    public function destroy($id)
    {
        DB::beginTransaction();
        $purchase = Purchase::with('Supplier', 'Coin')->find($id);
        $message = '';
        if ($purchase->conditions == 'Credito') {
            $calc_coin = $this->getBaseCoin('calc_currency_sale')->first();
            $mount = $purchase->coin_id != $calc_coin->id ? $this->mount_other($purchase, $calc_coin) : $purchase->mount;
            Supplier::where('id', $purchase->supplier->id)->update(['balance' => $purchase->supplier->balance - $mount]);
            $message = 'Actualizado Balance del Cliente';
        }
        Purchase::where('id', $purchase->id)->update(['status' => 'Anulada']);
        DB::commit();
        return redirect()
            ->route('purchases.index')
            ->with('message_status', 'Factura de Compra del Proveedor: ' . $purchase->Supplier->name . ' por un monto de: ' . $purchase->mount . ' ' . $purchase->Coin->symbol . ' Anulada con exito. ' . $message);
    }
}
