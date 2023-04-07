<?php

namespace App\Traits;

use App\Models\PaymentForm;
use App\Http\Requests\StorePaymentFormRequest;

trait PaymentFormTrait
{
    public function getPaymentForms($filter = [])
    {
        return PaymentForm::where($filter)->orderby('payment_form');
    }

    public function savePaymentForm(StorePaymentFormRequest $request)
    {
        $response = $this->saveModel('PaymentForm', $request);
        if ($response['success']) {
            $response['message'] = "Forma de pago $request->description " . ($request->id == 0 ? 'creado' : 'actualizado') . ' con exito';
        }
        return redirect()
            ->route('maintenance.paymentforms.index')
            ->with('message_status', $response['message']);
    }

    // public function getDisplayInfoPaymentForm($update=false, $paymentForm=null)
    // {
    //     return
    //     [
    //         'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo Formas de Pago'],
    //         'header' => ['title' => '', 'subTitle' => 'Detalle de la Forma de Pago', 'form' => 'maintenance.payment-forms.form'],
    //         'buttons' => ['title' => 'Crear Forma de Pago', 'url' => 'maintenance.paymentforms.create', 'icon' => 'person_add'],
    //         'table' => ['header' => ['Item', 'Descripción', 'Grupo', 'Acción'], 'include' => 'maintenance.payment-forms.table'],
    //         'router' => ['routePost' => 'maintenance.paymentforms.'.($update ? 'update' : 'store'), 'routeIndex' => 'maintenance.paymentforms.index',
    //                 'item' => '$paymentform'],
    //         'data' => ['collection' => $paymentForm, 'update' => $update]
    //     ];
    // }
}
