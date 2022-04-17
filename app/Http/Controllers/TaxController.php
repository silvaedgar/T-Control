<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Http\Requests\StoreTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class TaxController extends Controller
{
    public function __construct() {      // Manera de proteger ruta en RoleController hay otra forma
        $this->middleware('role.admin');
        // $this->middleware('can:users.create')->only('create');
    }

    public function index()
    {
        $taxes = Tax::all();
        return view('maintenance.taxes.index',compact('taxes'));

        // return view(product_rubric.index);
        //
    }

    public function create()
    {
      return view('maintenance.taxes.create');
        //
    }

    public function store(StoreTaxRequest $request)
    {
        Tax::create($request->all());
        return redirect()->route('maintenance.taxes.index')->with('status',"Ok_Creación de Impuesto a $request->percent %");
    }
    public function show(Tax $tax)
    {
        return view('maintenance.taxes.index');
        //
    }

    public function edit($id)
    {
        $tax = Tax::find($id);
        return view('maintenance.taxes.edit',compact('tax'));
        //
    }

    public function update(Request $request)
    {
        $request->validate ([
            'percent' => ["min:0",Rule::unique('taxes')->ignore($request->id)],
            'description' => ["required",Rule::unique('taxes')->ignore($request->id)]
        ]);
        // if ($request->base_tax == 'S')
        // {

        // }
        $tax = Tax::find($request->id);
        $tax->update($request->all());
        return redirect()->route('maintenance.taxes.index')->with("Ok_Creación de Impuesto a $request->percent %");
        //
    }

    public function destroy(Tax $productRubric)
    {
        //
    }
}
