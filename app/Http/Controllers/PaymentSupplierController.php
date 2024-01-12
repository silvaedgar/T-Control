<?php

namespace App\Http\Controllers;

use App\Models\PaymentSupplier;
use App\Models\PaymentForm;
use App\Models\Supplier;

use App\Http\Requests\PaymentSupplierRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Facades\Config;

use App\Traits\FiltersTrait;
use App\Traits\CoinTrait;
use App\Traits\CalculateMountsTrait;
use App\Traits\PaymentSupplierTrait;
use App\Traits\SharedTrait;
use App\Traits\PaymentFormTrait;

use PDF;
use Carbon\Carbon;

class PaymentSupplierController extends Controller
{
    use FiltersTrait, CoinTrait, CalculateMountsTrait, PaymentSupplierTrait, SharedTrait, PaymentFormTrait;

    public function __construct()
    {
        $this->middleware('role');
    }

    public function fieldsFill()
    {
        return ['field' => 'calc_currency_purchase', 'price' => 'purchase_price', 'isPayment' => true];
    }

    public function index(Request $request)
    {
        $report = false;
        if (count($request->all()) > 0) {
            $filter = $this->createFilter($request, 'payment_date');
            $report = $request->option == 'Report';
        } else {
            $filter = $this->createFilter(['status' => 'Procesado'], 'payment_date');
        }
        $config = Config::labels('PaymentSuppliers', PaymentSupplier::GetPayments($filter)->get(), false, $filter);
        $config['isFormIndex'] = 'true';
        $config['hasFilter'] = true;
        $config['data']['status'] = ['Todos', 'Procesado', 'Anulado', 'Historico'];

        // $config = $this->headerInfoFill($config);
        $config = $this->headerInfoFill($config, $this->fieldsFill());

        if ($report) {
            $pdf = PDF::loadView('shared.payment-list-report', ['models' => $purchases, 'data_common' => $data_common]);
            return $pdf->stream();
        }
        // return $config;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('PaymentSuppliers');
        $config['header']['title'] = 'Creando Pago de Proveedores';
        $config['cols'] = 3;

        // llevar esto al getsupplier
        // PaymentForm::where('activo', 1)
        //             ->orderBy('payment_form')
        //             ->get();

        $config['data']['paymentForms'] = $this->getPaymentForms([['activo', '=', 1]])->get();
        $config['data']['suppliers'] = Supplier::GetSuppliers([['activo', 1]])->get();
        $config['var_header']['table'] = $config['data']['suppliers'];

        $config = $this->headerInfoFill($config, $this->fieldsFill());

        return view('shared.create', compact('config'));
    }

    public function store(PaymentSupplierRequest $request)
    {
        $continue = true;
        $exist_payment = PaymentSupplier::where('supplier_id', $request->supplier_id)
            ->where('mount', $request->mount)
            ->where('payment_date', $request->payment_date)
            ->first();
        if ($exist_payment != '') {
            $fecha1 = new Carbon($exist_payment->created_at);
            if ($fecha1->diffInMinutes(now()) < 40) {
                $continue = false;
            }
        }
        // return $this->storePayment($request); // solo para pruebas
        return redirect()
            ->route('paymentsuppliers.index')
            ->with('message_status', $continue ? $this->storePayment($request) : 'Registro ya existente espere 5 minuto para intentar nuevamente');
    }

    public function show(PaymentSupplier $paymentsupplier)
    {
        $invoice = $paymentsupplier;
        // $invoice->load(['coin', 'supplier', 'paymentForm']);
        $config = Config::labels('PaymentSuppliers');
        $config['cols'] = 3;
        // $config = $this->headerInfoFill($config, $invoice);
        $config = $this->headerInfoFill($config, $this->fieldsFill(), $invoice);

        $config['var_header']['table'] = null;
        $config['var_header']['name'] = $invoice->supplier->name;
        $config['data']['update'] = true;
        return view('shared.create', compact('config', 'invoice'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $payment = PaymentSupplier::with('Supplier')->find($id);
        $calc_coin = $this->getBaseCoin('calc_currency_purchase')->first();
        $mount = $payment->coin_id != $calc_coin->id ? $this->mount_other($payment, $calc_coin) : $payment->mount;
        Supplier::where('id', $payment->supplier->id)->update(['balance' => $payment->supplier->balance + $mount]);
        PaymentSupplier::where('id', $payment->id)->update(['status' => 'Anulado']);
        DB::commit();
        return redirect()
            ->route('paymentsuppliers.index')
            ->with('message_status', 'Pago del Proveedor ' . $payment->supplier->name . " por un monto de $payment->mount " . $payment->coin->symbol . ' Anulado. Actualizado Balance del Cliente');
    }

    public function filter(Request $request)
    {
        $filter = $this->create_filter($request, 'payment_date');
        $paymentsuppliers = PaymentSupplier::GetPayments($filter)->get();

        // $paymentsuppliers = $this->load_payments($filter)->get();
        $data = ['data_filter' => $this->data_filter($filter), 'header' => 'Pagos Realizados a Proveedores'];
        $data_common = DataCommonFacade::index('PaymentSupplier', $data);
        if ($request->option == 'Report') {
            $pdf = PDF::loadView('shared.payment-list-report', ['models' => $paymentsuppliers, 'data_common' => $data_common]);
            return $pdf->stream();
        } else {
            return view('payment-suppliers.index', compact('paymentsuppliers', 'data_common'));
        }
    }
}
