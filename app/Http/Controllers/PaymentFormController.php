<?php

namespace App\Http\Controllers;

use App\Models\PaymentForm;
use App\Http\Requests\StorePaymentFormRequest;
use App\Http\Requests\UpdatePaymentFormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class PaymentFormController extends Controller
{

    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
    }

    public function index()
    {
        $paymentforms = PaymentForm::all();
        return view('maintenance.payment-forms.index',compact('paymentforms'));
        // return PaymentForm::all();  //para la api

    }

    public function create()
    {
      return view('maintenance.payment-forms.create');
        //
    }

    public function store(StorePaymentFormRequest $request)
    {
        PaymentForm::create($request->all());
        return redirect()->route('maintenance.paymentforms.index')->with('status',"Ok_Forma de Pago $request->description. Creado exitosamente");
    }
    public function show(PaymentForm $paymentform)
    {
        return view('maintenance.paymentforms.index');
        //
    }

    public function edit($id)
    {
        $paymentform = PaymentForm::find($id);
        return view('maintenance.payment-forms.edit',compact('paymentform'));
        //
    }

    public function update(UpdatePaymentFormRequest $request)
    {
        // $request->validate ([
        //     'payment_form' => ["required","max:20",Rule::unique('payment_forms')->ignore($request->id)]
        // ]);
        $paymentform = PaymentForm::find($request->id);
        $paymentform->update($request->all());
        return redirect()->route('maintenance.paymentforms.index')->with('status',"Ok_Forma de Pago $request->description. Actualizado exitosamente");;
        //
    }

    public function destroy($id)
    {
        $paymentform = PaymentForm::find($id);
        return redirect()->route('maintenance.paymentforms.index')->with('status',"Ok_Eliminación de Forma de Pago  $paymentform->description. NO AFECTA BD");
    }

}
