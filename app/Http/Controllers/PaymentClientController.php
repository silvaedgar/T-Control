<?php

namespace App\Http\Controllers;

use App\Models\PaymentClient;
use App\Models\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Facades\Config;

use Illuminate\Http\Request;

use App\Http\Requests\PaymentClientRequest;

use App\Facades\DataCommonFacade;
use App\Facades\ProcessPaymentClientFacade;

use App\Traits\FiltersTrait;
use App\Traits\CoinTrait;
use App\Traits\CalculateMountsTrait;
use App\Traits\PaymentClientTrait;
use App\Traits\PaymentFormTrait;
use App\Traits\ClientTrait;
use PDF;
use Carbon\Carbon;

class PaymentClientController extends Controller
{
    use FiltersTrait, CoinTrait, CalculateMountsTrait, PaymentClientTrait, ClientTrait, PaymentFormTrait;

    public function __construct()
    {
        $this->middleware('role')->only('index', 'create', 'edit', 'store', 'update', 'report');
    }

    public function fieldsFill()
    {
        return ['field' => 'calc_currency_sale', 'price' => 'sale_price', 'isPayment' => true];
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
        $config = Config::labels('PaymentClients', PaymentClient::GetPayments($filter)->get(), false, $filter);
        $config['isFormIndex'] = 'true';
        $config['hasFilter'] = true;
        $config['data']['status'] = ['Todos', 'Procesado', 'Anulado', 'Historico'];
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
        $config = Config::labels('PaymentClients');
        $config['header']['title'] = 'Creando Pago de Cliente';
        $config = $this->headerInfoFill($config, $this->fieldsFill());
        $config['cols'] = 3;
        $config['data']['paymentForms'] = $this->getPaymentForms([['activo', 1]])->get();
        $config['data']['clients'] = $this->getClients([['activo', 1]])->get();
        $config['var_header']['table'] = $config['data']['clients'];

        return view('shared.create', compact('config'));
    }

    public function store(PaymentClientRequest $request)
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

    public function show(PaymentClient $paymentclient)
    {
        $invoice = $paymentclient;
        // PaymentClient::with('client', 'coin', 'paymentForm')->where('id', $paymentclient->id)->first();
        $continue = true;
        if (Auth::user()->hasRole('Client')) {
            $userClient = $this->get_user_clients(Auth::user()->id)->first();
            $continue = $userClient->client_id == $paymentclient->client_id;
        }
        if ($continue) {
            $config = Config::labels('PaymentClients');
            $config = $this->headerInfoFill($config, $this->fieldsFill(), $invoice);

            $config['cols'] = 3;
            $config['var_header']['table'] = null;
            $config['var_header']['name'] = $invoice->client->names;
            $config['data']['update'] = true;
            return view('shared.create', compact('config', 'invoice'));
        } else {
            $exist_client = $user_client == '' ? false : true;
            return view('home-auth', compact('exist_client'));
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $payment = PaymentClient::with('Client')->find($id);
        $calc_coin = $this->getBaseCoin('calc_currency_sale')->first();
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
