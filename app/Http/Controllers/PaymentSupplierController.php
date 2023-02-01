<?php

namespace App\Http\Controllers;

use App\Models\PaymentSupplier;
use App\Models\Supplier;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Traits\FiltersTrait;
use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;

use App\Facades\DataCommonFacade;
use App\Facades\ProcessPaymentSupplierFacade;

use App\Http\Requests\StorePaymentSupplierRequest;
use App\Http\Requests\UpdatePaymentSupplierRequest;

use PDF;
use Carbon\Carbon;

class PaymentSupplierController extends Controller
{
    use FiltersTrait, GetDataCommonTrait, CalculateMountsTrait;

    public function __construct()
    {
        $this->middleware('role');
    }

    public function index()
    {
        $paymentsuppliers = PaymentSupplier::GetPayments()->get();
        $data = ['data_filter' => $this->data_filter([]), 'header' => 'Pagos Realizados a Proveedores'];
        $data_common = DataCommonFacade::index('PaymentSupplier', $data);
        return view('payment-suppliers.index', compact('paymentsuppliers', 'data_common'));
    }

    public function create()
    {
        $data = ProcessPaymentSupplierFacade::getDataPayment();
        $data_common = DataCommonFacade::create('PaymentSupplier', $data);

        return view('payment-suppliers.create', compact('data', 'data_common'));
    }

    public function store(StorePaymentSupplierRequest $request)
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
        // return ProcessPaymentSupplierFacade::store_payment_supplier($request); // solo para pruebas
        return redirect()
            ->route('paymentsuppliers.index')
            ->with('message_status', $continue ? ProcessPaymentSupplierFacade::storePayment($request) : 'Registro ya existente espere 5 minuto para intentar nuevamente');
    }

    public function show($id)
    {
        $data = ProcessPaymentSupplierFacade::getDataPayment($id);
        $data_common = DataCommonFacade::edit('PaymentSupplier', $data);
        return view('payment-suppliers.show', compact('data', 'data_common'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $payment = PaymentSupplier::with('Supplier')->find($id);
        $calc_coin = $this->get_base_coin('calc_currency_purchase')->first();
        $mount = $payment->coin_id != $calc_coin->id ? $this->mount_other($payment, $calc_coin) : $payment->mount;
        Supplier::where('id', $payment->supplier->id)->update(['balance' => $payment->supplier->balance + $mount]);
        PaymentSupplier::where('id', $payment->id)->update(['status' => 'Anulado']);
        DB::commit();
        return redirect()
            ->route('paymentsuppliers.index')
            ->with('message_status', 'Pago del Proveedor ' . $payment->supplier->name . " por un monto de $payment->mount " . $payment->coin->symbol . ' Anulado. Actualizado Balance del Cliente');
    }

    // public function load_payments($filter=[]) {
    //     if (count($filter) == 0)
    //         return PaymentSupplier::orderBy('payment_date','desc')->orderBy('created_at','desc');
    //     else
    //         return PaymentSupplier::where($filter)->orderBy('payment_date','desc')->orderBy('created_at','desc');
    // }

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
