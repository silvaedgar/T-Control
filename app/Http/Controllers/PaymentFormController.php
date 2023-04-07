<?php

namespace App\Http\Controllers;

use App\Models\PaymentForm;

use App\Http\Requests\StorePaymentFormRequest;

use App\Facades\Config;

use App\Traits\PaymentFormTrait;
use App\Traits\SharedTrait;

class PaymentFormController extends Controller
{
    use PaymentFormTrait, SharedTrait;

    public function __construct()
    {
        // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
    }

    public function index()
    {
        $config = Config::labels('PaymentForms', $this->getPaymentForms()->get());
        $config['header']['title'] = 'Listado de Formas de Pago';
        $config['isFormIndex'] = true;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('PaymentForms');
        $config['header']['title'] = 'Creacion de Formas de Pago';
        return view('maintenance.shared.create-edit', compact('config'));
    }

    public function store(StorePaymentFormRequest $request)
    {

        return $this->savePaymentForm($request);
        //     ->with('status', "Ok_Forma de Pago $request->description. Creado exitosamente");
    }

    public function edit(PaymentForm $paymentform)
    {
        $config = Config::labels('PaymentForms', $paymentform, true);
        $config['header']['title'] = 'Editando Forma de Pago: ' . $paymentform->description;
        return view('maintenance.shared.create-edit', compact('paymentform', 'config'));
    }

    public function update(StorePaymentFormRequest $request)
    {
        return $this->savePaymentForm($request);
        //     ->with('status', "Ok_Forma de Pago $request->description. Actualizado exitosamente");
        //
    }

    public function destroy(PaymentForm $paymentform)
    {
        $paymentform->activo = !$paymentform->activo;
        $paymentform->save();
        return redirect()
            ->route('maintenance.paymentforms.index')
            ->with('status', (!$paymentform->activo ? 'Ok_Eliminación' : 'Ok_Activacion') . " de Forma de Pago $paymentform->description");
    }
}
