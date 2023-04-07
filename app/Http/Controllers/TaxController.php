<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Http\Requests\StoreTaxRequest;

use App\Facades\Config;

use App\Traits\TaxTrait;

class TaxController extends Controller
{
    use TaxTrait;

    public function __construct()
    {
        // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
    }

    public function index()
    {
        $config = Config::labels('Taxes', Tax::GetTaxes()->get());
        $config['header']['title'] = 'Listado de Impuestos';
        $config['isFormIndex'] = true;
        return view('shared.index', compact('config'));
    }

    public function create()
    {
        $config = Config::labels('Taxes');
        $config['header']['title'] = 'Creando Impuesto';
        return view('maintenance.shared.create-edit', compact('config'));
    }

    public function store(StoreTaxRequest $request)
    {
        return $this->saveTax($request);
        // Tax::create($request->all());
        // return redirect()
        //     ->route('maintenance.taxes.index')
        //     ->with('status', "Ok_Creación de Impuesto a $request->percent %");
    }

    public function edit(Tax $tax)
    {
        $config = Config::labels('Taxes', $tax, true);
        $config['header']['title'] = 'Editando Impuesto: ' . $tax->description;
        return view('maintenance.shared.create-edit', compact('tax', 'config'));
    }

    public function update(StoreTaxRequest $request)
    {
        return $this->saveTax($request);
        //
    }

    public function destroy(Tax $tax)
    {
        $tax->activo = !$tax->activo;
        $tax->save();
        return redirect()
            ->route('maintenance.taxes.index')
            ->with('status', (!$tax->activo ? 'Ok_Eliminación' : 'Ok_Activacion') . " de Impuesto $tax->description");
    }
}
