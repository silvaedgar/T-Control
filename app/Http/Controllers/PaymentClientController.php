<?php

namespace App\Http\Controllers;

use App\Models\PaymentClient;
use App\Models\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests\StorePaymentClientRequest;
use App\Http\Requests\UpdatePaymentClientRequest;

use App\Traits\FiltersTrait;
use App\Traits\GetDataCommonTrait;
use App\Traits\CalculateMountsTrait;

use App\Facades\DataCommonFacade;
use App\Facades\ProcessPaymentClientFacade;

use PDF;
use Carbon\Carbon;

class PaymentClientController extends Controller
{
    use FiltersTrait, GetDataCommonTrait, CalculateMountsTrait;

    public function __construct()
    {
        $this->middleware('role')->only('index', 'create', 'edit', 'store', 'update', 'report');
    }

    public function index()
    {
        $payments = PaymentClient::GetPayments()->get();
        $data = ['data_filter' => $this->data_filter([]), 'header' => 'Pagos Realizados de Clientes'];
        $data_common = DataCommonFacade::index('PaymentClient', $data);
        return view('payment-clients.index', compact('payments', 'data_common'));
    }

    public function create()
    {
        $data = ProcessPaymentClientFacade::getDataPayment();
        $data_common = DataCommonFacade::create('PaymentClient', $data);
        return view('payment-clients.create', compact('data', 'data_common'));
    }

    public function store(StorePaymentClientRequest $request)
    {
        $continue = true;
        $exist_payment = PaymentClient::where('client_id', $request->client_id)
            ->where('mount', $request->mount)
            ->where('payment_date', $request->payment_date)
            ->first();
        if ($exist_payment != '') {
            $fecha1 = new Carbon($exist_payment->created_at);
            if ($fecha1->diffInMinutes(now()) < 40) {
                $continue = false;
            }
        }
        return redirect()
            ->route('paymentclients.index')
            ->with('message_status', $continue ? ProcessPaymentClientFacade::storePayment($request) : 'Registro ya existente espere 5 minuto para intentar nuevamente');
    }

    public function show($id)
    {
        $continue = true;
        $payment = PaymentClient::with('Client')
            ->where('id', $id)
            ->first();
        if (Auth::user()->hasRole('Client')) {
            $user_client = $this->get_user_clients(Auth::user()->id)->first();
            $continue = $user_client->client_id == $payment->client_id;
        }
        if ($continue) {
            $data = ProcessPaymentClientFacade::getDataPayment($id);
            $data_common = DataCommonFacade::edit('PaymentClient', $data);
            return view('payment-clients.show', compact('data', 'data_common'));
        } else {
            $exist_client = $user_client == '' ? false : true;
            return view('home-auth', compact('exist_client'));
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $payment = PaymentClient::with('Client')->find($id);
        $calc_coin = $this->get_base_coin('calc_currency_sale')->first();
        $mount = $payment->coin_id != $calc_coin->id ? $this->mount_other($payment, $calc_coin) : $payment->mount;
        Client::where('id', $payment->client->id)->update(['balance' => $payment->client->balance + $mount]);
        PaymentClient::where('id', $payment->id)->update(['status' => 'Anulado']);
        DB::commit();
        return redirect()
            ->route('paymentclients.index')
            ->with('message_status', 'Pago del Cliente ' . $payment->client->names . ' Anulado. Actualizado Balance del Cliente');
        //
    }

    public function filter(Request $request)
    {
        $filter = $this->create_filter($request, 'payment_date');
        $payments = PaymentClient::GetPayments($filter)->get();
        $data = ['data_filter' => $this->data_filter($filter), 'header' => 'Pagos Realizados de Clientes'];
        $data_common = DataCommonFacade::index('PaymentClient', $data);
        if ($request->option == 'Report') {
            $pdf = PDF::loadView('shared.payment-list-report', ['models' => $payments, 'data_common' => $data_common]);
            return $pdf->stream();
        } else {
            return view('payment-clients.index', compact('payments', 'data_common'));
        }
    }
}
